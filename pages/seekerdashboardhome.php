<?php
session_start();
include "../database/conn.php";

$sql =
    "SELECT * FROM job ORDER BY job_id DESC";


$result =
    mysqli_query(
        $conn,
        $sql
    );

$pfp = "select * from jobseeker where jobseeker_id = {$_SESSION['jobseeker_id']}";
$pfpresult = mysqli_query($conn, $pfp);
if (!($pfpresult && mysqli_num_rows($pfpresult) > 0)) {
    echo "<script>
            alert('Please complete your profile first!');
            window.location.href = 'editprofile.php';
          </script>";
}

$pfpdata = mysqli_fetch_assoc($pfpresult);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seeker</title>
    <link rel="stylesheet" href="../css//seekerdashboardhome.css" />
</head>

<body>
    <div class="main">
        <div class="left">

            <aside class="sidebar">
                <div style="
    position: fixed;
    top: 0;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
">
                    <div>

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
                            <a href="../pages/seekernotificaion.php" class="nav-item ">

                                <span>Notifications</span>
                            </a>


                        </nav>

                    </div>
                    <div class="sidebar-bottom">

                        <div class="provider-small">


                            <div>
                                <strong><?php echo $_SESSION['Full_name'] ?></strong>
                                <small>Employee</small>
                            </div>

                        </div>

                        <div class="logout"><a href="../pages/logincompany.php">Log out</a>
                        </div>

                    </div>

                </div>
            </aside>

        </div>
        <div class="right">



            <div class="dashboard-header">

                <div>
                    <h1>Welcome back, Job Seeker!</h1>
                    <p>Find your next opportunity and manage your applications.</p>
                </div>

                <div class="profile">


                    <div>
                        <strong><?php echo $_SESSION['Full_name'] ?></strong>
                        <span>Employee</span>
                    </div>
                </div>

            </div>

            <div class="seeker-profile">

                <div class="section-title">

                    <a href="../pages/editprofile.php">Edit Profile</a>
                </div>


                <div class="seeker-info">


                    <div class="seeker-about">



                        <div>
                            <h2><?php echo $_SESSION['Full_name'] ?></h2>
                            <!-- <p><?php echo $pfpdata['bio'] ?></p> -->

                            <div class="seeker-location">
                                <?php echo $pfpdata['address'] ?>
                            </div>
                        </div>

                    </div>


                    <!-- Information -->
                    <div class="info-item">


                        <div>
                            <small>Skills</small>
                            <p>HTML, CSS, JavaScript, PHP, MySQL</p>
                        </div>

                    </div>


                    <div class="info-item">



                        <div>
                            <small>Languages</small>
                            <p>English, Nepali, Hindi</p>
                        </div>

                    </div>


                    <div class="info-item">



                        <div>
                            <small>Education</small>
                            <p>Bachelor in Computer Science</p>
                        </div>

                    </div>


                    <div class="info-item">



                        <div>
                            <small>Experience</small>
                            <p>2 Years Experience</p>
                        </div>

                    </div>


                    <div class="info-item">



                        <div>
                            <small>Email</small>
                            <p>jobseeker@example.com</p>
                        </div>

                    </div>


                </div>

            </div>





            <div class="dashboard-content">



                <div class="recommended">
                    <div class="section-title">
                        <h2>Recommended Jobs</h2>
                        <a href="#">View all</a>
                    </div>



                    <?php

                    if (mysqli_num_rows($result) > 0) {

                        while ($row = mysqli_fetch_assoc($result)) {

                    ?>





                            <div class="job-card">

                                <div class="company-logo">
                                    G
                                </div>

                                <div class="job-info">

                                    <h3>Frontend Developer</h3>

                                    <p>Google</p>

                                    <div class="job-details">

                                        <span> Kathmandu</span>

                                        <span> Rs. 50K - 80K</span>

                                        <span> Full Time</span>

                                    </div>

                                </div>

                                <button class="apply-btn">
                                    Apply Now
                                </button>

                            </div>

                    <?php };
                    } ?>





                </div>





                <div class="applications">

                    <div class="section-title">

                        <h2>Recent Applications</h2>

                        <a href="#">View all</a>

                    </div>




                    <div class="application-item">

                        <div>

                            <h3>Web Developer</h3>

                            <p>ABC Technologies</p>

                        </div>

                        <span class="status pending">
                            Pending
                        </span>

                    </div>

                    <div class="application-item">

                        <div>

                            <h3>Software Engineer</h3>

                            <p>Tech Nepal</p>

                        </div>

                        <span class="status shortlisted">
                            Shortlisted
                        </span>

                    </div>

                    <div class="application-item">

                        <div>

                            <h3>UI Designer</h3>

                            <p>Creative Studio</p>

                        </div>

                        <span class="status rejected">
                            Rejected
                        </span>

                    </div>


                    <!-- Application 4 -->

                    <div class="application-item">

                        <div>

                            <h3>PHP Developer</h3>

                            <p>Digital Solutions</p>

                        </div>

                        <span class="status pending">
                            Pending
                        </span>

                    </div>

                </div>

            </div>


        </div>



    </div>


</body>

</html>