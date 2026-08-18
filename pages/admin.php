<?php

include "../database/conn.php";
$selectedJobseeker = null;

if (isset($_GET['view_id'])) {

    $jobseekerId = (int) $_GET['view_id'];

    $detailsSql = "
        SELECT
            jobseeker_id,
            Full_name,
            email,
            phone,
            address,
            gender,
            Resume,
            Citizenship,
            profile_image
        FROM jobseeker
        WHERE jobseeker_id = ?
    ";

    $stmt = mysqli_prepare($conn, $detailsSql);

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $jobseekerId
    );

    mysqli_stmt_execute($stmt);

    $detailsResult = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($detailsResult) > 0) {

        $selectedJobseeker =
            mysqli_fetch_assoc($detailsResult);
    }
}
$sql = "
    SELECT 
        j.jobseeker_id,
        j.Full_name,
        j.email,
        j.phone,
        j.address,
        j.gender,
        j.Language,
        j.Resume,
        j.Citizenship,
        j.profile_image,
        s.skill_name
    FROM jobseeker j
    LEFT JOIN skill s 
        ON j.skill_id = s.skill_id
    ORDER BY j.jobseeker_id DESC
";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}



    // jobseeker total count

$totalJobseekers = mysqli_num_rows($result);




?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta 
        name="viewport" 
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin Dashboard - LaboraNova</title>

    <link rel="stylesheet" href="../css/admin.css">

</head>

<body>

<div class="main">


<!-- sidebar -->

    <div class="left">

        <nav>


            <div class="top">

                <img
                    class="img-top"
                    src="../assets/lavoranovaaa.png"
                    alt="LABORANOVA"
                >

            </div>




            <div class="center">

                <a href="admin.php" class="active">
                    Jobseeker
                </a>

                <a href="../pages/adminprovider.php">
                    Job Provider
                </a>

            </div>



            <a href="../pages/landing.php" class="logout">
                Log out
            </a>

        </nav>

    </div>




    <div class="right">

        <div class="content-card">

            <h2 class="card-title-standard">
                All Jobseekers (<?php echo $totalJobseekers; ?>)
            </h2>


            <div class="table-responsive">

                <table class="simple-table">

                    <thead>

                        <tr>

                            <th>JOBSEEKER</th>

                            <th>SKILL</th>

                            <th>LOCATION</th>

                            <th>ACTIONS</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php

                    if ($totalJobseekers > 0) {

                        while ($row = mysqli_fetch_assoc($result)) {

                            $name = $row['Full_name'];

                            $email = $row['email'];

                            $phone = $row['phone'];

                            $address = $row['address'];

                            $gender = $row['gender'];

                            $language = 
                                $row['Language'] ?? ''
                            ;

                            $resume = 
                                $row['Resume'] ?? ''
                            ;

                            $citizenship = 
                                $row['Citizenship'] ?? ''
                            ;

                            $skill = !empty($row['skill_name'])
                                ? ($row['skill_name'])
                                : "N/A";

                            

    

                            ?>

                            <tr>

                                <!-- JOBSEEKER -->

                                <td>

                                    <div class="table-user-cell">

                                        


                                        <strong>
                                            <?php echo $name; ?>
                                        </strong>

                                    </div>

                                </td>


                                <!-- SKILL -->

                                <td>
                                    <?php echo $skill; ?>
                                </td>


                                <!-- LOCATION -->

                                <td>
                                    <?php echo $address; ?>
                                </td>


                                <!-- ACTION -->

                                <td>
    <a
        href="admin.php?view_id=<?php echo $row['jobseeker_id']; ?>"
        class="btn-table-view"
    >
        View
    </a>
</td>

                            </tr>

                            <?php

                        }

                    } else {

                    ?>

                        <tr>

                            <td colspan="4" style="text-align:center;">
                                No jobseekers found.
                            </td>

                        </tr>

                    <?php

                    }

                    ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<?php if ($selectedJobseeker != null) { ?>

<div class="modal-overlay">

    <div class="modal-box">

        <div class="modal-header">

            <h2>Jobseeker Information</h2>


        </div>


        <!-- PROFILE IMAGE -->

        <div class="profile-section">

            <?php if (!empty($selectedJobseeker['profile_image'])) { ?>

                <img
                    src="../<?php echo htmlspecialchars($selectedJobseeker['profile_image']); ?>"
                    class="profile-image"
                    alt="Profile Image"
                >

            <?php } else { ?>

                <div class="profile-placeholder">

                    <?php
                    echo strtoupper(
                        substr(
                            $selectedJobseeker['Full_name'],
                            0,
                            1
                        )
                    );
                    ?>

                </div>

            <?php } ?>


            <div>

                <h3>
                    <?php
                    echo htmlspecialchars(
                        $selectedJobseeker['Full_name']
                    );
                    ?>
                </h3>

                <p>Jobseeker</p>

            </div>

        </div>


        <!-- DETAILS -->

        <div class="details-container">


            <div class="detail-item">

                <span>Full Name</span>

                <strong>
                    <?php
                    echo htmlspecialchars(
                        $selectedJobseeker['Full_name']
                    );
                    ?>
                </strong>

            </div>


            <div class="detail-item">

                <span>Email</span>

                <strong>
                    <?php
                    echo htmlspecialchars(
                        $selectedJobseeker['email']
                    );
                    ?>
                </strong>

            </div>


            <div class="detail-item">

                <span>Phone</span>

                <strong>
                    <?php
                    echo htmlspecialchars(
                        $selectedJobseeker['phone']
                    );
                    ?>
                </strong>

            </div>


            <div class="detail-item">

                <span>Address</span>

                <strong>
                    <?php
                    echo htmlspecialchars(
                        $selectedJobseeker['address']
                    );
                    ?>
                </strong>

            </div>


            <div class="detail-item">

                <span>Gender</span>

                <strong>
                    <?php
                    echo htmlspecialchars(
                        $selectedJobseeker['gender']
                    );
                    ?>
                </strong>

            </div>


        </div>


        <!-- DOCUMENTS -->

        <div class="documents-section">

            <h3>Documents</h3>


            <!-- RESUME -->

            <div class="document-item">

                <span>Resume</span>


                <?php if (!empty($selectedJobseeker['Resume'])) { ?>

                    <a
                        href="../<?php echo htmlspecialchars($selectedJobseeker['Resume']); ?>"
                        target="_blank"
                    >
                        View Resume
                    </a>

                <?php } else { ?>

                    <strong>N/A</strong>

                <?php } ?>

            </div>


            <!-- CITIZENSHIP -->

            <div class="document-item">

                <span>Citizenship</span>


                <?php if (!empty($selectedJobseeker['Citizenship'])) { ?>

                    <a
                        href="../<?php echo htmlspecialchars($selectedJobseeker['Citizenship']); ?>"
                        target="_blank"
                    >
                        View Citizenship
                    </a>

                <?php } else { ?>

                    <strong>N/A</strong>

                <?php } ?>

            </div>


        </div>


        <div class="modal-footer">

            <a
                href="admin.php"
                class="close-button"
            >
                Close
            </a>

        </div>


    </div>

</div>

<?php } ?>



</body>

</html>