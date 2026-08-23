<?php

session_start();

include "../database/conn.php";


/* CHECK JOB PROVIDER LOGIN*/

if (!isset($_SESSION['company_name'])) {
    header("Location: logincompany.php");
    exit();
}

$company_name = $_SESSION['company_name'];


/* APPROVE / DECLINE APPLICATION */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $application_id = $_POST['application_id'];
    $status = $_POST['status'];

    if ($status == "Approved" || $status == "Declined") {

        $sql = "UPDATE application
                SET app_status = ?
                WHERE Application_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $application_id);
        $stmt->execute();

        $stmt->close();

        header("Location: providerapplication.php");
        exit();
    }
}


/* VIEW JOB SEEKER INFORMATION */

$view_id = null;
$jobseeker = null;

if (isset($_GET['view'])) {

    $view_id = intval($_GET['view']);

    $sql = "
        SELECT
            a.Application_id,
            a.app_status,
            a.application_date,

            js.jobseeker_id,
            js.Full_name,
            js.email,
            js.phone,
            js.address,
            js.gender,
            js.Language,
            js.experience,
            js.education,
            js.Resume,
            js.Citizenship,

            s.skill_name,

            so.socialmedia_name,
            so.platform,

            t.training_name,
            t.certificate,

            j.job_title

        FROM application a

        INNER JOIN jobseeker js
            ON a.jobseeker_id = js.jobseeker_id

        INNER JOIN job j
            ON a.job_id = j.job_id

        INNER JOIN jobprovider jp
            ON j.jobprovider_id = jp.jobprovider_id

        LEFT JOIN skill s
            ON js.skill_id = s.skill_id

        LEFT JOIN social so
            ON js.social_id = so.social_id

        LEFT JOIN training t
            ON js.training_id = t.training_id

        WHERE a.Application_id = ?
        AND jp.company_name = ?
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "is",
        $view_id,
        $company_name
    );

    $stmt->execute();

    $view_result = $stmt->get_result();

    if ($view_result->num_rows > 0) {
        $jobseeker = $view_result->fetch_assoc();
    }

    $stmt->close();
}


/* GET APPLICATIONS*/

$sql = "
    SELECT
        a.Application_id,
        a.application_date,
        a.app_status,
        js.Full_name,
        js.email,
        js.phone,
        j.job_title,
        jp.company_name

    FROM application a

    INNER JOIN jobseeker js
        ON a.jobseeker_id = js.jobseeker_id

    INNER JOIN job j
        ON a.job_id = j.job_id

    INNER JOIN jobprovider jp
        ON j.jobprovider_id = jp.jobprovider_id

    WHERE jp.company_name = ?

    ORDER BY a.Application_id DESC
";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "s",
    $company_name
);

$stmt->execute();

$result = $stmt->get_result();


/* TOTAL PENDING APPLICATIONS */

$total_applications = 0;

while ($temp_row = $result->fetch_assoc()) {

    if ($temp_row['app_status'] == "Pending") {
        $total_applications++;
    }
}

$result->data_seek(0);

?>




<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Applications</title>

    <link rel="stylesheet"
        href="../css/providerapplication.css">

</head>


<body>


    <div class="dashboard">


        <aside class="sidebar">

            <div class="logo">

                <span><img src="../assets/lavoranovaaa.png" alt=""></span>
            </div>

            <nav class="navigation">

                <a href="../pages/providerhome.php" class="nav-item active">

                    <span>Home</span>
                </a>

                <a href="../pages/providerpostjob.php" class="nav-item">

                    <span>Post Job</span>
                </a>

                <a href="../pages/providerapplication.php" class="nav-item">

                    <span>Applications</span>
                </a>

            </nav>

            <div class="sidebar-bottom">

                <div class="provider-small">


                    <div>
                        <strong><?php echo $_SESSION['company_name'] ?></strong>
                        <small>Company</small>
                    </div>

                </div>

                <div class="logout"><a href="../pages/logincompany.php">Log out</a>
                </div>

            </div>

        </aside>




        <main class="main-content">


            <!-- APPLICATION CARD -->

            <div class="application-card">


                <!-- HEADER -->

                <div class="card-header">

                    <div>

                        <h1>

                            All Applications
                            (<?php echo $total_applications; ?>)

                        </h1>


                        <p>

                            View applications submitted
                            for your job postings.

                        </p>

                    </div>

                </div>



                <!-- TABLE -->

                <div class="table-container">


                    <table>


                        <thead>

                            <tr>

                                <th>
                                    APPLICANT
                                </th>

                                <th>
                                    EMAIL
                                </th>

                                <th>
                                    PHONE
                                </th>

                                <th>
                                    JOB TITLE
                                </th>

                                <th>
                                    APPLICATION DATE
                                </th>

                                <th>
                                    ACTIONS
                                </th>

                            </tr>

                        </thead>



                        <tbody>


                            <?php if ($result->num_rows > 0): ?>


                                <?php while ($row = $result->fetch_assoc()): 
                                        if($row['app_status']=="Pending"){

                                    ?>

                                    <tr>


                                        <!-- APPLICANT -->

                                        <td>

                                            <div class="applicant">


                                                <div class="applicant-avatar">

                                                    <?php

                                                    $name =
                                                        $row['Full_name'];

                                                    $initials =
                                                        strtoupper(
                                                            substr(
                                                                $name,
                                                                0,
                                                                2
                                                            )
                                                        );

                                                    echo htmlspecialchars(
                                                        $initials
                                                    );

                                                    ?>

                                                </div>


                                                <strong>

                                                    <?php

                                                    echo htmlspecialchars(
                                                        $row['Full_name']
                                                    );

                                                    ?>

                                                </strong>


                                            </div>

                                        </td>



                                        <!-- EMAIL -->

                                        <td>

                                            <?php

                                            echo htmlspecialchars(
                                                $row['email']
                                            );

                                            ?>

                                        </td>



                                        <!-- PHONE -->

                                        <td>

                                            <?php

                                            echo htmlspecialchars(
                                                $row['phone']
                                            );

                                            ?>

                                        </td>



                                        <!-- JOB TITLE -->

                                        <td class="job-title">

                                            <?php

                                            echo htmlspecialchars(
                                                $row['job_title']
                                            );

                                            ?>

                                        </td>



                                        <!-- APPLICATION DATE -->

                                        <td>

                                            <?php

                                            echo date(
                                                "M d, Y",
                                                strtotime(
                                                    $row['application_date']
                                                )
                                            );

                                            ?>

                                        </td>



                                        <!-- ACTION -->

                                        <td>

                                            <a
    href="providerapplication.php?view=<?php echo $row['Application_id']; ?>"
    class="view-btn">
    View
</a>
<?php if ($jobseeker): ?>

<dialog open class="jobseeker-dialog">

    <div class="dialog-header">

        <div>
            <h2>
                <?php echo htmlspecialchars($jobseeker['Full_name']); ?>
            </h2>

            <p>
                Application for:
                <strong>
                    <?php echo htmlspecialchars($jobseeker['job_title']); ?>
                </strong>
            </p>
        </div>

        <a
            href="providerapplication.php"
            class="close-btn">
            ×
        </a>

    </div>


    <div class="jobseeker-info">

        <!-- BASIC INFORMATION -->

        <div class="info-section">

            <h3>Personal Information</h3>

            <div class="info-grid">

                <div class="info-item">
                    <label>Full Name</label>
                    <p>
                        <?php
                        echo htmlspecialchars(
                            $jobseeker['Full_name']
                        );
                        ?>
                    </p>
                </div>


                <div class="info-item">
                    <label>Email</label>
                    <p>
                        <?php
                        echo htmlspecialchars(
                            $jobseeker['email']
                        );
                        ?>
                    </p>
                </div>


                <div class="info-item">
                    <label>Phone</label>
                    <p>
                        <?php
                        echo htmlspecialchars(
                            $jobseeker['phone']
                        );
                        ?>
                    </p>
                </div>


                <div class="info-item">
                    <label>Gender</label>
                    <p>
                        <?php
                        echo htmlspecialchars(
                            $jobseeker['gender']
                        );
                        ?>
                    </p>
                </div>


                <div class="info-item">
                    <label>Address</label>
                    <p>
                        <?php
                        echo htmlspecialchars(
                            $jobseeker['address']
                        );
                        ?>
                    </p>
                </div>


                <div class="info-item">
                    <label>Language</label>
                    <p>
                        <?php
                        echo htmlspecialchars(
                            $jobseeker['Language'] ?? 'Not provided'
                        );
                        ?>
                    </p>
                </div>

            </div>

        </div>


        <!-- EDUCATION & EXPERIENCE -->

        <div class="info-section">

            <h3>Education & Experience</h3>

            <div class="info-grid">

                <div class="info-item">
                    <label>Education</label>

                    <p>
                        <?php
                        echo htmlspecialchars(
                            $jobseeker['education'] ?? 'Not provided'
                        );
                        ?>
                    </p>
                </div>


                <div class="info-item">
                    <label>Experience</label>

                    <p>
                        <?php
                        echo htmlspecialchars(
                            $jobseeker['experience'] ?? 'Not provided'
                        );
                        ?>
                    </p>
                </div>

            </div>

        </div>


        <!-- SKILL -->

        <div class="info-section">

            <h3>Skill</h3>

            <div class="info-item">

                <p>
                    <?php
                    echo htmlspecialchars(
                        $jobseeker['skill_name'] ?? 'Not provided'
                    );
                    ?>
                </p>

            </div>

        </div>


        <!-- SOCIAL -->

        <div class="info-section">

            <h3>Social</h3>

            <div class="info-grid">

                <div class="info-item">

                    <label>Platform</label>

                    <p>
                        <?php
                        echo htmlspecialchars(
                            $jobseeker['platform'] ?? 'Not provided'
                        );
                        ?>
                    </p>

                </div>


                <div class="info-item">

                    <label>Social Media</label>

                    <p>
                        <?php
                        echo htmlspecialchars(
                            $jobseeker['socialmedia_name'] ?? 'Not provided'
                        );
                        ?>
                    </p>

                </div>

            </div>

        </div>


        <!-- TRAINING -->

        <div class="info-section">

            <h3>Training</h3>

            <div class="info-grid">

                <div class="info-item">

                    <label>Training</label>

                    <p>
                        <?php
                        echo htmlspecialchars(
                            $jobseeker['training_name'] ?? 'Not provided'
                        );
                        ?>
                    </p>

                </div>


                <div class="info-item">

                    <label>Certificate</label>

                    <p>
                        <?php
                        echo htmlspecialchars(
                            $jobseeker['certificate'] ?? 'Not provided'
                        );
                        ?>
                    </p>

                </div>

            </div>

        </div>


        <!-- DOCUMENTS -->

        <div class="info-section">

            <h3>Documents</h3>

            <div class="documents">

                <div class="document-item">

                    <label>Resume</label>

                    <?php if (!empty($jobseeker['Resume'])): ?>

                        <a
                            href="../uploads/resume/<?php echo htmlspecialchars($jobseeker['Resume']); ?>"
                            target="_blank"
                            class="document-btn">
                            View Resume
                        </a>

                    <?php else: ?>

                        <p>Not provided</p>

                    <?php endif; ?>

                </div>


                <div class="document-item">

                    <label>Citizenship</label>

                    <?php if (!empty($jobseeker['Citizenship'])): ?>

                        <a
                            href="../uploads/citizenship/<?php echo htmlspecialchars($jobseeker['Citizenship']); ?>"
                            target="_blank"
                            class="document-btn">
                            View Citizenship
                        </a>

                    <?php else: ?>

                        <p>Not provided</p>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>


    <!-- ACTION BUTTONS -->

    <div class="dialog-actions">

        <form method="POST">

            <input
                type="hidden"
                name="application_id"
                value="<?php echo $jobseeker['Application_id']; ?>">

            <input
                type="hidden"
                name="status"
                value="Declined">

            <button
                type="submit"
                class="decline-btn">

                Decline

            </button>

        </form>


        <form method="POST">

            <input
                type="hidden"
                name="application_id"
                value="<?php echo $jobseeker['Application_id']; ?>">

            <input
                type="hidden"
                name="status"
                value="Approved">

            <button
                type="submit"
                class="approve-btn">

                Approve

            </button>

        </form>

    </div>

</dialog>

<?php endif; ?>

                                        </td>


                                    </tr>


                                <?php } endwhile; ?>


                            <?php else: ?>


                                <tr>

                                    <td
                                        colspan="6"
                                        class="no-data">

                                        No applications found.

                                    </td>

                                </tr>


                            <?php endif; ?>


                        </tbody>


                    </table>


                </div>


            </div>


        </main>


    </div>


</body>

</html>