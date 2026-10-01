<?php
session_start();
include "../database/conn.php";

// Check if jobseeker is logged in
if (!isset($_SESSION['jobseeker_id'])) {
    header("Location: loginseeker.php");
    exit();
}

$jobseeker_id = (int)$_SESSION['jobseeker_id'];

// Fetch seeker details
$pfp_sql = "
    SELECT js.*, s.skill_name 
    FROM jobseeker js 
    LEFT JOIN skill s ON js.skill_id = s.skill_id 
    WHERE js.jobseeker_id = ?
";
$pfp_stmt = mysqli_prepare($conn, $pfp_sql);
mysqli_stmt_bind_param($pfp_stmt, "i", $jobseeker_id);
mysqli_stmt_execute($pfp_stmt);
$pfpresult = mysqli_stmt_get_result($pfp_stmt);

if (!$pfpresult || mysqli_num_rows($pfpresult) === 0) {
    echo "<script>
            alert('Please complete your profile first!');
            window.location.href = 'editprofile.php';
          </script>";
    exit();
}

$pfpdata = mysqli_fetch_assoc($pfpresult);
$seekerName = !empty($pfpdata['Full_name']) ? $pfpdata['Full_name'] : ($_SESSION['Full_name'] ?? 'Job Seeker');

// Fetch 3 recommended jobs in descending order
$sql = "
    SELECT j.*, jp.company_name 
    FROM job j 
    INNER JOIN jobprovider jp ON j.jobprovider_id = jp.jobprovider_id 
    ORDER BY j.job_id DESC 
    LIMIT 3
";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Seeker Dashboard - LaboraNova</title>
    <link rel="stylesheet" href="../css/seekerdashboardhome.css" />
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
                    width: 240px;
                ">
                    <div>
                        <div class="logo">
                            <span><img src="../assets/lavoranovaaa.png" alt="LaboraNova"></span>
                        </div>

                        <nav class="navigation">
                            <a href="seekerdashboardhome.php" class="nav-item active">
                                <span>Home</span>
                            </a>

                            <a href="seekerdashboardbrowsejob.php" class="nav-item">
                                <span>Browse Jobs</span>
                            </a>

                            <a href="seekernotificaion.php" class="nav-item">
                                <span>Notifications</span>
                            </a>

                            <a href="editprofile.php" class="nav-item">
                                <span>Edit Profile</span>
                            </a>
                        </nav>
                    </div>

                    <div class="sidebar-bottom">
                        <div class="provider-small">
                            <div>
                                <strong><?php echo htmlspecialchars($seekerName); ?></strong>
                                <small>Job Seeker</small>
                            </div>
                        </div>

                        <div class="logout">
                            <a href="loginseeker.php">Log out</a>
                        </div>
                    </div>
                </div>
            </aside>
        </div>

        <div class="right">
            <div class="dashboard-header">
                <div>
                    <h1>Welcome back, <?php echo htmlspecialchars($seekerName); ?>!</h1>
                    <p>Find your next opportunity and manage your applications.</p>
                </div>

                <div class="profile">
                    <?php 
                    $profileImg = $pfpdata['profile_image'] ?? '';
                    if (!empty($profileImg)) {
                        $pImgPath = (strpos($profileImg, '../') === 0) ? $profileImg : '../' . $profileImg;
                    ?>
                        <img src="<?php echo htmlspecialchars($pImgPath); ?>" alt="Profile" class="profile-avatar" style="object-fit: cover;">
                    <?php } else { ?>
                        <div class="profile-avatar">
                            <?php echo strtoupper(substr($seekerName, 0, 1)); ?>
                        </div>
                    <?php } ?>
                    <div>
                        <strong><?php echo htmlspecialchars($seekerName); ?></strong>
                        <span>Job Seeker</span>
                    </div>
                </div>
            </div>

            <!-- SEEKER PROFILE SUMMARY -->
            <div class="seeker-profile">
                <div class="section-title">
                    <h2>My Profile</h2>
                    <a href="editprofile.php">Edit Profile</a>
                </div>

                <div class="seeker-info">
                    <div class="seeker-about">
                        <?php 
                        if (!empty($profileImg)) {
                            $pImgPath = (strpos($profileImg, '../') === 0) ? $profileImg : '../' . $profileImg;
                        ?>
                            <img src="<?php echo htmlspecialchars($pImgPath); ?>" alt="Avatar" class="large-avatar" style="object-fit: cover;">
                        <?php } else { ?>
                            <div class="large-avatar">
                                <?php echo strtoupper(substr($seekerName, 0, 1)); ?>
                            </div>
                        <?php } ?>

                        <div>
                            <h2><?php echo htmlspecialchars($seekerName); ?></h2>
                            <div class="seeker-location">
                                 <?php echo htmlspecialchars(!empty($pfpdata['address']) ? $pfpdata['address'] : 'Location not set'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="info-item">
                        <div>
                            <small>Skills</small>
                            <p><?php echo htmlspecialchars(!empty($pfpdata['skill_name']) ? $pfpdata['skill_name'] : 'Not provided'); ?></p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div>
                            <small>Languages</small>
                            <p><?php echo htmlspecialchars(!empty($pfpdata['Language']) ? $pfpdata['Language'] : 'Not provided'); ?></p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div>
                            <small>Education</small>
                            <p><?php echo htmlspecialchars(!empty($pfpdata['education']) ? $pfpdata['education'] : 'Not provided'); ?></p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div>
                            <small>Experience</small>
                            <p><?php echo htmlspecialchars(!empty($pfpdata['experience']) ? $pfpdata['experience'] : 'Not provided'); ?></p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div>
                            <small>Email</small>
                            <p><?php echo htmlspecialchars(!empty($pfpdata['email']) ? $pfpdata['email'] : 'Not provided'); ?></p>
                        </div>
                    </div>

                    <div class="info-item">
                        <div>
                            <small>Phone</small>
                            <p><?php echo htmlspecialchars(!empty($pfpdata['phone']) ? $pfpdata['phone'] : 'Not provided'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RECOMMENDED JOBS (TOP 3 IN DESCENDING ORDER) -->
            <div class="dashboard-content">
                <div class="recommended">
                    <div class="section-title">
                        <h2>Recommended Jobs</h2>
                        <a href="seekerdashboardbrowsejob.php">View All Jobs &rarr;</a>
                    </div>

                    <?php
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $companyInitial = strtoupper(substr($row['company_name'] ?? 'C', 0, 1));
                    ?>
                            <div class="job-card">
                                <div class="company-logo">
                                    <?php echo $companyInitial; ?>
                                </div>

                                <div class="job-info">
                                    <h3><?php echo htmlspecialchars($row['job_title']); ?></h3>
                                    <p><?php echo htmlspecialchars($row['company_name']); ?></p>

                                    <div class="job-details">
                                        <span> <?php echo htmlspecialchars($row['job_location']); ?></span>
                                        <span> Rs. <?php echo number_format((float)$row['salary'], 2); ?></span>
                                        <span> <?php echo htmlspecialchars($row['job_type']); ?></span>
                                        <span> Due: <?php echo htmlspecialchars($row['due_date']); ?></span>
                                        <span> Openings: <?php echo htmlspecialchars($row['no_of_opening']); ?></span>
                                    </div>
                                </div>

                                <a href="seekerdashboardbrowsejob.php" class="apply-btn">
                                    View & Apply
                                </a>
                            </div>
                    <?php 
                        }
                    } else { 
                    ?>
                        <div style="text-align: center; padding: 40px 20px; color: #888;">
                            <p>No jobs available at the moment. Please check back soon!</p>
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