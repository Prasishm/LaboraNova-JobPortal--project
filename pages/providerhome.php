<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Provider Dashboard</title>
    <link rel="stylesheet" href="../css/providerhome.css">
</head>

<body>

<div class="dashboard">

    <!-- SIDEBAR -->
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
                    <strong><?php echo $_SESSION ['company_name']?></strong>
                    <small>Company</small>
                </div>

            </div>

            <div class="logout"><a href="../pages/logincompany.php">Log out</a>
                </div>

        </div>

    </aside>


    <!-- MAIN CONTENT -->
    <main class="main-content">

        <!-- HEADER -->
        <div class="top-header">

            <div>
                <h1>Welcome back, Job Provider!</h1>

                <p>
                    Manage your job postings and find the right candidates.
                </p>
            </div>



        </div>





        <!-- RECENT JOBS -->
        <div class="content-card">

            <div class="card-header">

                <div>
                    <h2>Recent Job Postings</h2>

                    <p>
                        Overview of your latest job postings.
                    </p>
                </div>

                <a href="../pages/providerpostjob.php" class="view-link">
                    Post New Job
                </a>

            </div>


            <div class="table-container">

                <table>

                    <thead>

                        <tr>
                            <th>Job Title</th>
                            <th>Applicants</th>
                            <th>Posted</th>
                            <th>Status</th>
                        </tr>

                    </thead>

                    <tbody>

                        <tr>
                            <td class="job-name">
                                Senior Frontend Engineer
                            </td>

                            <td>64</td>

                            <td>6 days ago</td>

                            <td>
                                <span class="badge live">
                                    Live
                                </span>
                            </td>
                        </tr>


                        <tr>
                            <td class="job-name">
                                Product Designer
                            </td>

                            <td>39</td>

                            <td>2 weeks ago</td>

                            <td>
                                <span class="badge closing">
                                    Closing soon
                                </span>
                            </td>
                        </tr>


                        <tr>
                            <td class="job-name">
                                DevOps Engineer
                            </td>

                            <td>21</td>

                            <td>3 days ago</td>

                            <td>
                                <span class="badge live">
                                    Live
                                </span>
                            </td>
                        </tr>


                        <tr>
                            <td class="job-name">
                                Data Analyst
                            </td>

                            <td>88</td>

                            <td>1 month ago</td>

                            <td>
                                <span class="badge paused">
                                    Paused
                                </span>
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </main>

</div>

</body>
</html>