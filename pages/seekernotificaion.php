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

$jobseeker_id = $_SESSION['jobseeker_id'];


// =====================================
// GET APPLICATION NOTIFICATIONS
// =====================================

$sql = "
    SELECT
        a.Application_id,
        a.application_date,
        a.app_status,
        j.job_title,
        jp.company_name
    FROM application a

    INNER JOIN job j
        ON a.job_id = j.job_id

    INNER JOIN jobprovider jp
        ON j.jobprovider_id = jp.jobprovider_id

    WHERE a.jobseeker_id = ?

    AND a.app_status IN ('Approved', 'Declined')

    ORDER BY a.Application_id DESC
";


$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {

    die("Prepare failed: " . mysqli_error($conn));

}

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $jobseeker_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Notifications</title>

    <link
        rel="stylesheet"
        href="../css/seekernotification.css"
    >

</head>


<body>


<div class="main">


    <!-- =====================================
         LEFT SIDEBAR
    ====================================== -->

    <div class="left">

        <aside class="sidebar">


            <!-- LOGO -->

            <div class="logo">

                <span>

                    <img
                        src="../assets/lavoranovaaa.png"
                        alt="LaboraNova"
                    >

                </span>

            </div>


            <!-- NAVIGATION -->

            <nav class="navigation">

            <a href="../pages/seekerdashboardhome.php" class="nav-item">

                <span>Home</span>
            </a>

            <a href="../pages/seekerdashboardbrowsejob.php" class="nav-item">

                <span>Browse Job</span>
            </a>

            <a href="../pages/seekernotificaion.php" class="nav-item active">

                <span>Notifications</span>
</a>

        </nav>


            <!-- =====================================
                 SIDEBAR BOTTOM
            ====================================== -->

            <div class="sidebar-bottom">


                <div class="provider-small">

                    <div>

                        <strong>
                            <?php
                            echo htmlspecialchars(
                                $_SESSION['Full_name']
                            );
                            ?>
                        </strong>

                        <br>

                        <small>Employee</small>

                    </div>

                </div>


                <div class="logout">

                    <a href="../pages/loginseeker.php">
                        Log out
                    </a>

                </div>


            </div>


        </aside>

    </div>



    <!-- =====================================
         RIGHT SIDE
    ====================================== -->

    <div class="right">


        <!-- PAGE TITLE -->

        <div class="text">

            <h1>Notifications</h1>

            <p>
                Check the latest updates about your job applications.
            </p>

        </div>



        <!-- =====================================
             NOTIFICATION LIST
        ====================================== -->

        <div class="notification-list">


            <?php

            if (mysqli_num_rows($result) > 0) {

                while ($row = mysqli_fetch_assoc($result)) {

                    $status = $row['app_status'];

            ?>


                <!-- NOTIFICATION CARD -->

                <div
                    class="notification-card
                    <?php
                    echo ($status == "Approved")
                        ? "approved"
                        : "declined";
                    ?>"
                >




                    <!-- NOTIFICATION INFORMATION -->

                    <div class="notification-info">


                        <h2>

                            <?php

                            echo htmlspecialchars(
                                $row['job_title']
                            );

                            ?>

                        </h2>


                        <p>

                            <strong>Company:</strong>

                            <?php

                            echo htmlspecialchars(
                                $row['company_name']
                            );

                            ?>

                        </p>


                        <p>

                            <strong>Application Date:</strong>

                            <?php

                            echo htmlspecialchars(
                                $row['application_date']
                            );

                            ?>

                        </p>


                        <p class="status-text">

                            Your application has been

                            <strong>

                                <?php

                                echo htmlspecialchars(
                                    $status
                                );

                                ?>

                            </strong>.

                        </p>


                    </div>


                    <!-- STATUS -->

                    <div class="status-badge">

                        <?php

                        echo htmlspecialchars(
                            $status
                        );

                        ?>

                    </div>


                </div>


            <?php

                }

            } else {

            ?>


                <!-- NO NOTIFICATION -->

                <div class="no-notification">


                    <div class="empty-icon">
                        🔔
                    </div>


                    <h2>
                        No notifications
                    </h2>


                    <p>
                        You don't have any application updates yet.
                    </p>


                </div>


            <?php

            }

            ?>


        </div>


    </div>


</div>


</body>

</html>