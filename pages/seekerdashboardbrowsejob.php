<?php

session_start();

include "../database/conn.php";


// =====================================
// CHECK JOBSEEKER LOGIN
// =====================================

if (!isset($_SESSION['jobseeker_id'])) {

    header("Location: loginseeker.php");
    exit();

}

$jobseeker_id =
    $_SESSION['jobseeker_id'];


// =====================================
// APPLY FOR JOB
// =====================================

if (
    $_SERVER["REQUEST_METHOD"] == "POST"
    &&
    isset($_POST['apply_job'])
) {

    $job_id =
        intval($_POST['job_id']);


    // Get company name
    // through jobprovider_id

    $sql = "
        SELECT
            jobprovider.company_name
        FROM job
        INNER JOIN jobprovider
            ON job.jobprovider_id =
               jobprovider.jobprovider_id
        WHERE job.job_id = ?
    ";


    $stmt =
        mysqli_prepare(
            $conn,
            $sql
        );


    if (!$stmt) {

        die(
            "Prepare failed: " .
            mysqli_error($conn)
        );

    }


    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $job_id
    );


    mysqli_stmt_execute(
        $stmt
    );


    $result_company =
        mysqli_stmt_get_result(
            $stmt
        );


    $job =
        mysqli_fetch_assoc(
            $result_company
        );


    if (!$job) {

        die("Job not found.");

    }


    $company_name =
        $job['company_name'];


    // Check duplicate application

    $check_sql = "
        SELECT Application_id
        FROM application
        WHERE jobseeker_id = ?
        AND job_id = ?
    ";


    $check_stmt =
        mysqli_prepare(
            $conn,
            $check_sql
        );


    mysqli_stmt_bind_param(
        $check_stmt,
        "ii",
        $jobseeker_id,
        $job_id
    );


    mysqli_stmt_execute(
        $check_stmt
    );


    $check_result =
        mysqli_stmt_get_result(
            $check_stmt
        );


    if (
        mysqli_num_rows(
            $check_result
        ) > 0
    ) {

        $message =
            "You have already applied for this job.";

    }

    else {

        $insert_sql = "
            INSERT INTO application
            (
                company_name,
                jobseeker_id,
                job_id,
                application_date
            )
            VALUES
            (?, ?, ?, CURDATE())
        ";


        $insert_stmt =
            mysqli_prepare(
                $conn,
                $insert_sql
            );


        mysqli_stmt_bind_param(
            $insert_stmt,
            "sii",
            $company_name,
            $jobseeker_id,
            $job_id
        );


        if (
            mysqli_stmt_execute(
                $insert_stmt
            )
        ) {

            $message =
                "Application submitted successfully.";

        }

        else {

            $message =
                "Failed to submit application.";

        }

    }

}


// =====================================
// GET ALL JOBS
// =====================================

$sql =
    "SELECT * FROM job ORDER BY job_id DESC";


$result =
    mysqli_query(
        $conn,
        $sql
    );

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Browse Jobs</title>

    <link rel="stylesheet" href="../css/seekerdashboardbrowsejob.css">

</head>

<body>

<div class="main">

    <!-- LEFT SIDEBAR -->
    <div class="left">

        <aside class="sidebar">

        <div class="logo">

            <span><img src="../assets/lavoranovaaa.png" alt=""></span>
        </div>

        <nav class="navigation">

            <a href="../pages/seekerdashboardhome.php" class="nav-item active">

                <span>Home</span>
            </a>

            <a href="../pages/seekerdashboardbrowsejob.php" class="nav-item">

                <span>Browse Job</span>
            </a>


        </nav>

        <div class="sidebar-bottom">

            <div class="provider-small">


                <div>
                    <strong><?php echo $_SESSION ['Full_name']?></strong><br>
                    <small>Employee</small>
                </div>

            </div>

            <div class="logout"><a href="../pages/logincompany.php">Log out</a>
                </div>

        </div>

    </aside>

    </div>


    <!-- RIGHT SIDE -->
    <div class="right">

        <div class="browseJob" id="browseJob">

            <div class="text">

                <h1>Find Your next role</h1>

                <p>

                    <?php echo mysqli_num_rows($result); ?>

                    live roles from companies

                </p>

            </div>


            <!-- SEARCH BAR -->
            <div class="search-bar">

                <div class="search">

                    <img
                        src="../assets/search.svg"
                        alt=""
                        class="img_logo"
                    >

                    <input
                        type="text"
                        name="jobsearch"
                        placeholder="Job title, Company"
                    >

                </div>


                <div class="search">

                    <img
                        src="../assets/map-pin.svg"
                        alt=""
                        class="img_logo"
                    >

                    <input
                        type="text"
                        name="location"
                        placeholder="Location or Remote"
                    >

                </div>


                <button type="button">

                    Search

                    <img
                        src="../assets/arrow-narrow-right.svg"
                        class="img_logo"
                        alt=""
                    >

                </button>

            </div>


            <!-- JOB LIST -->
            <div class="job-list">

                <?php

                if (mysqli_num_rows($result) > 0) {

                    while ($row = mysqli_fetch_assoc($result)) {

                ?>

                    <!-- JOB CARD -->

                    <div class="job-card">


                        <!-- JOB TITLE -->

                        <div class="job-info">

                            <h2>

                                <?php

                                echo htmlspecialchars(
                                    $row['job_title']
                                );

                                ?>

                            </h2>


                            <!-- NUMBER OF OPENINGS -->

                            <p>

                                <strong>
                                    Opening(s):
                                </strong>

                                <?php

                                echo htmlspecialchars(
                                    $row['no_of_opening']
                                );

                                ?>

                            </p>


                            <!-- DEADLINE -->

                            <p>

                                <strong>
                                    Deadline:
                                </strong>

                                <?php

                                echo htmlspecialchars(
                                    $row['due_date']
                                );

                                ?>

                            </p>

                        </div>


                        <!-- VIEW JOB BUTTON -->

                        <button
                            type="button"
                            class="apply-btn"
                            onclick="openJob(<?php echo $row['job_id']; ?>)"
                        >

                            View Job

                        </button>


                        <!-- HIDDEN COMPLETE JOB DETAILS -->

                        <div
                            id="job-<?php echo $row['job_id']; ?>"
                            class="job-full-details"
                            style="display: none;"
                        >

                            <h2>

                                <?php

                                echo htmlspecialchars(
                                    $row['job_title']
                                );

                                ?>

                            </h2>


                            <p>

                                <strong>
                                    Position:
                                </strong>

                                <?php

                                echo htmlspecialchars(
                                    $row['position']
                                );

                                ?>

                            </p>


                            <p>

                                <strong>
                                    Location:
                                </strong>

                                <?php

                                echo htmlspecialchars(
                                    $row['job_location']
                                );

                                ?>

                            </p>


                            <p>

                                <strong>
                                    Job Type:
                                </strong>

                                <?php

                                echo htmlspecialchars(
                                    $row['job_type']
                                );

                                ?>

                            </p>


                            <p>

                                <strong>
                                    Salary:
                                </strong>

                                Rs.

                                <?php

                                echo htmlspecialchars(
                                    $row['salary']
                                );

                                ?>

                            </p>


                            <p>

                                <strong>
                                    Experience:
                                </strong>

                                <?php

                                echo htmlspecialchars(
                                    $row['experience']
                                );

                                ?>

                            </p>


                            <p>

                                <strong>
                                    Number of Openings:
                                </strong>

                                <?php

                                echo htmlspecialchars(
                                    $row['no_of_opening']
                                );

                                ?>

                            </p>


                            <p>

                                <strong>
                                    Deadline:
                                </strong>

                                <?php

                                echo htmlspecialchars(
                                    $row['due_date']
                                );

                                ?>

                            </p>


                            <div class="description">

    <strong>
        Job Description:
    </strong>

    <p>

        <?php

        echo nl2br(
            htmlspecialchars(
                $row['job_description']
            )
        );

        ?>

    </p>

</div>


<form method="POST">

    <input
        type="hidden"
        name="job_id"
        value="<?php echo $row['job_id']; ?>"
    >

    <button
        type="submit"
        name="apply_job"
        class="modal-apply-btn"
    >
        Apply for this Job
    </button>

</form>
                        </div>

                    </div>


                <?php

                    }

                } else {

                ?>


                    <!-- NO JOB -->

                    <div class="no-job">

                        <h2>
                            No jobs available
                        </h2>

                        <p>
                            There are currently no job vacancies available.
                        </p>

                    </div>


                <?php

                }

                ?>

            </div>

        </div>

    </div>

</div>



<!-- ============================= -->
<!-- JOB DETAILS MODAL -->
<!-- ============================= -->

<div id="jobModal" class="job-modal">

    <div class="job-modal-content">


        <!-- CLOSE BUTTON -->

        <button
            class="close-btn"
            onclick="closeJob()"
        >

            &times;

        </button>


        <!-- JOB DETAILS WILL APPEAR HERE -->

        <div id="modalJobContent"></div>


    </div>

</div>



<!-- ============================= -->
<!-- JAVASCRIPT -->
<!-- ============================= -->

<script>

function openJob(jobId)
{

    // Get the hidden job details

    const job = document.getElementById(
        "job-" + jobId
    );


    // Get modal content area

    const modalContent =
        document.getElementById(
            "modalJobContent"
        );


    // job detail model ma halcha

    modalContent.innerHTML =
        job.innerHTML;


    // model dekhaune

    document.getElementById(
        "jobModal"
    ).style.display = "flex";

}



function closeJob()
{

    // Hide modal

    document.getElementById(
        "jobModal"
    ).style.display = "none";

}



// Close modal when clicking outside

window.onclick = function(event)
{

    const modal =
        document.getElementById(
            "jobModal"
        );


    if (event.target === modal)
    {

        modal.style.display = "none";

    }

}

</script>


</body>

</html>