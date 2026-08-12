<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css//seekerdashboardhome.css" />
</head>
<body>
        <div class="main">
    <div class="left">
    <nav>
    <div class="top">
        <img  class="img-top" src="../assets/lavoranovaaa.png" alt="">
    </div>
    <div class="center">
    <a href="../pages//seekerdashboardhome.php" class="grey">Home</a><br>
    <a href="../pages//seekerdashboardbrowsejob.php" class="grey">Browse Job</a><br>
    <a href="../pages//seekerbashboardcompany.php" class="grey">Browse Company</a><br>
    </div>
    <a href="../pages/login.php" class="logout">Log out</a><br>
    </nav>
    </div>
    <div class="right">



    <div class="dashboard-header">

        <div>
            <h1>Welcome back, Job Seeker!</h1>
            <p>Find your next opportunity and manage your applications.</p>
        </div>

        <div class="profile">
            <div class="profile-avatar">JS</div>

            <div>
                <strong>Job Seeker</strong>
                <span>Candidate</span>
            </div>
        </div>

    </div>




    <div class="search-box">

        <div class="search-input">
            <span> <img src="../assets//search.svg" alt="" class="icon"> </span>
            <input type="text" placeholder="Search for jobs, skills or keywords">
        </div>

        <div class="location-input">
            <span><img src="../assets//map-pin.svg" alt="" class="icon"> </span>
            <input type="text" placeholder="Location">
        </div>

        <button class="search-btn">
            Search Jobs
        </button>

    </div>


    <div class="stats">

        <div class="stat-card">
            <div class="stat-icon orange"><img src="../assets//briefcase-2.svg" alt=""></div>

            <div>
                <p>Applied Jobs</p>
                <h2>12</h2>
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-icon orange"><img src="../assets//star.svg" alt=""></div>

            <div>
                <p>Saved Jobs</p>
                <h2>8</h2>
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-icon orange"><img src="../assets//code.svg" alt=""></div>

            <div>
                <p>Skills</p>
                <h2>5</h2>
            </div>
        </div>


        <div class="stat-card">
            <div class="stat-icon orange"><img src="../assets//calendar-event.svg" alt=""></div>

            <div>
                <p>Experience</p>
                <h2>2 Year</h2>
            </div>
        </div>

    </div>



    <div class="seeker-profile">

        <div class="section-title">
            <h2>About Me</h2>
            <a href="#">Edit Profile</a>
        </div>


        <div class="seeker-info">


            <div class="seeker-about">

                <div class="large-avatar">
                    JS
                </div>

                <div>
                    <h2>Job Seeker</h2>
                    <p>Frontend Developer</p>

                    <div class="seeker-location">
                        Kathmandu, Nepal
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


            <!-- Job 2 -->

            <div class="job-card">

                <div class="company-logo microsoft">
                    M
                </div>

                <div class="job-info">

                    <h3>UI/UX Designer</h3>

                    <p>Microsoft</p>

                    <div class="job-details">

                        <span> Lalitpur</span>

                        <span> Rs. 45K - 70K</span>

                        <span> Full Time</span>

                    </div>

                </div>

                <button class="apply-btn">
                    Apply Now
                </button>

            </div>


            <!-- Job 3 -->

            <div class="job-card">

                <div class="company-logo amazon">
                    A
                </div>

                <div class="job-info">

                    <h3>Backend Developer</h3>

                    <p>Amazon</p>

                    <div class="job-details">

                        <span> Kathmandu</span>

                        <span> Rs. 60K - 90K</span>

                        <span> Full Time</span>

                    </div>

                </div>

                <button class="apply-btn">
                    Apply Now
                </button>

            </div>

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