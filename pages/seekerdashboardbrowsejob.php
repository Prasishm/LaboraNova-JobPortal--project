<?php

include "../database/conn.php";

// Get all jobs
$sql = "SELECT * FROM job ORDER BY job_id DESC";
$result = mysqli_query($conn, $sql);

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

        <nav>

            <div class="top">
                <img
                    class="img-top"
                    src="../assets/lavoranovaaa.png"
                    alt=""
                >
            </div>

            <div class="center">

                <a href="../pages/seekerdashboardhome.php" class="grey">
                    Home
                </a>

                <br>

                <a href="../pages/seekerdashboardbrowsejob.php" class="grey">
                    Browse Job
                </a>

                <br>

                <a href="../pages/seekerbashboardcompany.php" class="grey">
                    Browse Company
                </a>

                <br>

            </div>

            <a href="../pages/login.php" class="logout">
                Log out
            </a>

        </nav>

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
                        <div class="job-container">
                        <div class="job-card">
                            <div class="job-info">
                                <h2>
                                    <?php echo htmlspecialchars($row['job_title']); ?>
                                </h2>
                                <p class="position">
                                    <?php echo htmlspecialchars($row['position']); ?>
                                </p>
                                <p>
                                    <?php echo htmlspecialchars($row['job_location']); ?>
                                </p>
                            </div>

                            <div class="job-details">

                                <span>
                                    <?php echo htmlspecialchars($row['job_type']); ?>
                                </span>

                                <span>
                                    Rs. <?php echo htmlspecialchars($row['salary']); ?>
                                </span>

                                <span>
                                    <?php echo htmlspecialchars($row['experience']); ?>
                                </span>

                            </div>


                            <div class="job-description">

                                <p>
                                    <?php echo htmlspecialchars($row['job_description']); ?>
                                </p>

                            </div>


                            <div class="job-footer">

                                <span>
                                    <?php echo $row['no_of_opening']; ?>
                                    Opening(s)
                                </span>

                                <span>
                                    Deadline:
                                    <?php echo $row['due_date']; ?>
                                </span>

                            </div>


                            <button class="apply-btn">
                                View Job
                            </button>

                        </div>
</div>
                <?php

                    }

                } else {

                ?>

                    <div class="no-job">

                        <h2>No jobs available</h2>

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

</body>

</html>