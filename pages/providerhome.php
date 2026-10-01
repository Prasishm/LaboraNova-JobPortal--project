<?php 
session_start();
include "../database/conn.php";

// Check if job provider is logged in
if (!isset($_SESSION['company_name']) && !isset($_SESSION['jobprovider_id'])) {
    header("Location: logincompany.php");
    exit();
}

$company_name = $_SESSION['company_name'] ?? 'Company';
$jobprovider_id = $_SESSION['jobprovider_id'] ?? null;

// If jobprovider_id not set in session, retrieve from DB
if (!$jobprovider_id && !empty($company_name)) {
    $stmt = mysqli_prepare($conn, "SELECT jobprovider_id FROM jobprovider WHERE company_name = ?");
    mysqli_stmt_bind_param($stmt, "s", $company_name);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($res)) {
        $jobprovider_id = (int)$row['jobprovider_id'];
        $_SESSION['jobprovider_id'] = $jobprovider_id;
    }
}

// Handle Employee Details View Modal
$selectedEmployee = null;
if (isset($_GET['view_emp']) && $jobprovider_id) {
    $emp_id = (int)$_GET['view_emp'];
    $emp_detail_sql = "
        SELECT 
            js.*,
            s.skill_name,
            t.training_name,
            t.certificate,
            so.socialmedia_name,
            so.platform,
            j.job_title,
            j.job_type,
            j.salary,
            a.Application_id,
            a.application_date,
            a.app_status
        FROM application a
        INNER JOIN job j ON a.job_id = j.job_id
        INNER JOIN jobseeker js ON a.jobseeker_id = js.jobseeker_id
        LEFT JOIN skill s ON js.skill_id = s.skill_id
        LEFT JOIN training t ON js.training_id = t.training_id
        LEFT JOIN social so ON js.social_id = so.social_id
        WHERE j.jobprovider_id = ? AND js.jobseeker_id = ? AND a.app_status = 'Approved'
        LIMIT 1
    ";
    $ed_stmt = mysqli_prepare($conn, $emp_detail_sql);
    mysqli_stmt_bind_param($ed_stmt, "ii", $jobprovider_id, $emp_id);
    mysqli_stmt_execute($ed_stmt);
    $ed_res = mysqli_stmt_get_result($ed_stmt);
    if ($ed_res && mysqli_num_rows($ed_res) > 0) {
        $selectedEmployee = mysqli_fetch_assoc($ed_res);
    }
}

// Fetch all jobs posted by this company
$jobs_sql = "
    SELECT 
        j.*,
        COUNT(a.Application_id) AS total_applicants
    FROM job j
    LEFT JOIN application a ON j.job_id = a.job_id
    WHERE j.jobprovider_id = ?
    GROUP BY j.job_id
    ORDER BY j.job_id DESC
";
$j_stmt = mysqli_prepare($conn, $jobs_sql);
mysqli_stmt_bind_param($j_stmt, "i", $jobprovider_id);
mysqli_stmt_execute($j_stmt);
$jobs_result = mysqli_stmt_get_result($j_stmt);
$totalJobs = mysqli_num_rows($jobs_result);

// Fetch employees working in the company (Approved candidates)
$employees_sql = "
    SELECT 
        js.jobseeker_id,
        js.Full_name,
        js.email,
        js.phone,
        js.address,
        js.gender,
        js.Language,
        js.experience,
        js.education,
        js.profile_image,
        js.Resume,
        s.skill_name,
        j.job_title,
        j.job_type,
        a.Application_id,
        a.application_date,
        a.app_status
    FROM application a
    INNER JOIN job j ON a.job_id = j.job_id
    INNER JOIN jobseeker js ON a.jobseeker_id = js.jobseeker_id
    LEFT JOIN skill s ON js.skill_id = s.skill_id
    WHERE j.jobprovider_id = ? AND a.app_status = 'Approved'
    ORDER BY a.Application_id DESC
";
$e_stmt = mysqli_prepare($conn, $employees_sql);
mysqli_stmt_bind_param($e_stmt, "i", $jobprovider_id);
mysqli_stmt_execute($e_stmt);
$employees_result = mysqli_stmt_get_result($e_stmt);
$totalEmployees = mysqli_num_rows($employees_result);

// Fetch total applications received
$totalApplications = 0;
$app_cnt_sql = "
    SELECT COUNT(a.Application_id) AS app_count
    FROM application a
    INNER JOIN job j ON a.job_id = j.job_id
    WHERE j.jobprovider_id = ?
";
$a_stmt = mysqli_prepare($conn, $app_cnt_sql);
mysqli_stmt_bind_param($a_stmt, "i", $jobprovider_id);
mysqli_stmt_execute($a_stmt);
$a_res = mysqli_stmt_get_result($a_stmt);
if ($a_row = mysqli_fetch_assoc($a_res)) {
    $totalApplications = (int)$a_row['app_count'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Provider Dashboard - LaboraNova</title>
    <link rel="stylesheet" href="../css/providerhome.css">
</head>

<body>

<div class="dashboard">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div>
            <div class="logo">
                <span><img src="../assets/lavoranovaaa.png" alt="LaboraNova"></span>
            </div>

            <nav class="navigation">
                <a href="providerhome.php" class="nav-item active">
                    <span>Home</span>
                </a>

                <a href="providerpostjob.php" class="nav-item">
                    <span>Post Job</span>
                </a>

                <a href="providerapplication.php" class="nav-item">
                    <span>Applications</span>
                </a>

                <a href="providereditprofile.php" class="nav-item">
                    <span>Edit Profile</span>
                </a>
            </nav>
        </div>

        <div class="sidebar-bottom">
            <div class="provider-small">
                <div>
                    <strong><?php echo htmlspecialchars($company_name); ?></strong>
                    <small>Job Provider</small>
                </div>
            </div>

            <div class="logout">
                <a href="logincompany.php">Log out</a>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">

        <!-- HEADER -->
        <div class="top-header">
            <div>
                <h1>Welcome back, <?php echo htmlspecialchars($company_name); ?>!</h1>
                <p>Manage your job postings, track applications, and view your company workforce.</p>
            </div>

            <div class="profile-mini">
                <div class="profile-avatar">
                    <?php echo strtoupper(substr($company_name, 0, 1)); ?>
                </div>
                <div>
                    <strong><?php echo htmlspecialchars($company_name); ?></strong>
                    <span><a href="providereditprofile.php" style="color: #f15a3a; font-size: 12px; font-weight: 600;">Edit Profile &rarr;</a></span>
                </div>
            </div>
        </div>

        <!-- STATS CARDS -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon stat-icon-orange">J</div>
                <div class="stat-info">
                    <span class="stat-label">Total Jobs Posted</span>
                    <strong class="stat-value"><?php echo $totalJobs; ?></strong>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon stat-icon-blue">A</div>
                <div class="stat-info">
                    <span class="stat-label">Total Applications</span>
                    <strong class="stat-value"><?php echo $totalApplications; ?></strong>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon stat-icon-green">E</div>
                <div class="stat-info">
                    <span class="stat-label">Working in Company</span>
                    <strong class="stat-value"><?php echo $totalEmployees; ?></strong>
                </div>
            </div>
        </div>

        <!-- RECENT JOBS POSTINGS -->
        <div class="content-card" style="margin-bottom: 30px;">
            <div class="card-header">
                <div>
                    <h2>Recent Job Postings</h2>
                    <p>Overview of all jobs you have posted on LaboraNova.</p>
                </div>

                <a href="providerpostjob.php" class="btn-post-job">
                    + Post New Job
                </a>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Job Type</th>
                            <th>Location</th>
                            <th>Salary</th>
                            <th>Applicants</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php 
                        if ($totalJobs > 0) {
                            while ($job = mysqli_fetch_assoc($jobs_result)) {
                                $today = date("Y-m-d");
                                $dueDate = $job['due_date'];
                                
                                // Determine status
                                if ($dueDate < $today) {
                                    $badgeClass = "paused";
                                    $statusText = "Expired";
                                } else {
                                    $diffDays = (strtotime($dueDate) - strtotime($today)) / (60 * 60 * 24);
                                    if ($diffDays <= 3) {
                                        $badgeClass = "closing";
                                        $statusText = "Closing soon";
                                    } else {
                                        $badgeClass = "live";
                                        $statusText = "Live";
                                    }
                                }
                        ?>
                            <tr>
                                <td class="job-name">
                                    <strong><?php echo htmlspecialchars($job['job_title']); ?></strong>
                                    <small style="display: block; color: #8993a0; font-size: 12px; margin-top: 2px;">
                                        Openings: <?php echo htmlspecialchars($job['no_of_opening']); ?>
                                    </small>
                                </td>

                                <td>
                                    <span class="tag-type"><?php echo htmlspecialchars($job['job_type']); ?></span>
                                </td>

                                <td><?php echo htmlspecialchars($job['job_location']); ?></td>

                                <td>Rs. <?php echo number_format((float)$job['salary'], 2); ?></td>

                                <td>
                                    <a href="providerapplication.php" class="applicant-count">
                                        <?php echo htmlspecialchars($job['total_applicants']); ?> applicants
                                    </a>
                                </td>

                                <td><?php echo htmlspecialchars($job['due_date']); ?></td>

                                <td>
                                    <span class="badge <?php echo $badgeClass; ?>">
                                        <?php echo $statusText; ?>
                                    </span>
                                </td>

                                <td>
                                    <a href="providerapplication.php" class="btn-view-app">
                                        View Applications
                                    </a>
                                </td>
                            </tr>
                        <?php 
                            }
                        } else { 
                        ?>
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 35px; color: #718096;">
                                    You have not posted any jobs yet. 
                                    <a href="providerpostjob.php" style="color: #f15a3a; font-weight: 600; margin-left: 5px;">Post your first job</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- EMPLOYEES WORKING IN THIS COMPANY (HIRED CANDIDATES) -->
        <div class="content-card">
            <div class="card-header">
                <div>
                    <h2>Employees Working in Our Company (<?php echo $totalEmployees; ?>)</h2>
                    <p>All candidates approved and currently employed at <?php echo htmlspecialchars($company_name); ?>.</p>
                </div>

                <a href="providerapplication.php" class="view-link">
                    View All Applications &rarr;
                </a>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Role / Designation</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Key Skills</th>
                            <th>Hired Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php 
                        if ($totalEmployees > 0) {
                            while ($emp = mysqli_fetch_assoc($employees_result)) {
                                $empInitial = strtoupper(substr($emp['Full_name'] ?? 'E', 0, 1));
                                $empImg = $emp['profile_image'] ?? '';
                                $empImgSrc = !empty($empImg) ? (strpos($empImg, '../') === 0 ? $empImg : '../' . $empImg) : '';
                        ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <?php if (!empty($empImgSrc)) { ?>
                                            <img src="<?php echo htmlspecialchars($empImgSrc); ?>" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #f15a3a;">
                                        <?php } else { ?>
                                            <div class="user-avatar-initial">
                                                <?php echo $empInitial; ?>
                                            </div>
                                        <?php } ?>
                                        <div>
                                            <strong><?php echo htmlspecialchars($emp['Full_name']); ?></strong>
                                            <small style="display: block; color: #8993a0; font-size: 11px;">
                                                <?php echo htmlspecialchars(!empty($emp['address']) ? $emp['address'] : 'Location N/A'); ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <strong><?php echo htmlspecialchars($emp['job_title']); ?></strong>
                                    <small style="display: block; color: #8993a0; font-size: 11px;">
                                        <?php echo htmlspecialchars($emp['job_type']); ?>
                                    </small>
                                </td>

                                <td><?php echo htmlspecialchars($emp['email']); ?></td>

                                <td><?php echo htmlspecialchars($emp['phone']); ?></td>

                                <td>
                                    <span class="tag-skill">
                                        <?php echo htmlspecialchars(!empty($emp['skill_name']) ? $emp['skill_name'] : 'N/A'); ?>
                                    </span>
                                </td>

                                <td><?php echo htmlspecialchars($emp['application_date']); ?></td>

                                <td>
                                    <span class="badge live">
                                        Active Employee
                                    </span>
                                </td>

                                <td>
                                    <a href="providerhome.php?view_emp=<?php echo $emp['jobseeker_id']; ?>" class="btn-view-app">
                                        View Profile
                                    </a>
                                </td>
                            </tr>
                        <?php 
                            }
                        } else { 
                        ?>
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 40px 20px; color: #718096;">
                                    <p style="font-size: 15px; margin-bottom: 8px;">No active employees working in your company yet.</p>
                                    <p style="font-size: 13px; color: #a0aec0;">
                                        Review incoming applications in the 
                                        <a href="providerapplication.php" style="color: #f15a3a; font-weight: 600;">Applications page</a> 
                                        and approve qualified candidates to add them to your team.
                                    </p>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

</div>

<!-- EMPLOYEE DETAILS MODAL -->
<?php if ($selectedEmployee != null): ?>
<div class="modal-overlay" style="display: flex;">
    <div class="modal-box">
        <div class="modal-header">
            <h2>Employee Profile Details</h2>
            <a href="providerhome.php" class="modal-close">&times;</a>
        </div>

        <div class="profile-section">
            <?php 
            $empImg = $selectedEmployee['profile_image'] ?? '';
            $empImgSrc = !empty($empImg) ? (strpos($empImg, '../') === 0 ? $empImg : '../' . $empImg) : '';
            if (!empty($empImgSrc)) { 
            ?>
                <img src="<?php echo htmlspecialchars($empImgSrc); ?>" class="profile-image" alt="Profile Image">
            <?php } else { ?>
                <div class="profile-placeholder">
                    <?php echo strtoupper(substr($selectedEmployee['Full_name'], 0, 1)); ?>
                </div>
            <?php } ?>

            <div>
                <h3><?php echo htmlspecialchars($selectedEmployee['Full_name']); ?></h3>
                <p style="color: #f15a3a; font-weight: 600; margin-top: 2px;">
                    Role: <?php echo htmlspecialchars($selectedEmployee['job_title']); ?> (<?php echo htmlspecialchars($selectedEmployee['job_type']); ?>)
                </p>
            </div>
        </div>

        <div class="details-container">
            <div class="detail-item">
                <span>Full Name</span>
                <strong><?php echo htmlspecialchars($selectedEmployee['Full_name']); ?></strong>
            </div>

            <div class="detail-item">
                <span>Email</span>
                <strong><?php echo htmlspecialchars($selectedEmployee['email']); ?></strong>
            </div>

            <div class="detail-item">
                <span>Phone</span>
                <strong><?php echo htmlspecialchars($selectedEmployee['phone']); ?></strong>
            </div>

            <div class="detail-item">
                <span>Location</span>
                <strong><?php echo htmlspecialchars(!empty($selectedEmployee['address']) ? $selectedEmployee['address'] : 'N/A'); ?></strong>
            </div>

            <div class="detail-item">
                <span>Gender</span>
                <strong><?php echo htmlspecialchars(!empty($selectedEmployee['gender']) ? $selectedEmployee['gender'] : 'N/A'); ?></strong>
            </div>

            <div class="detail-item">
                <span>Language</span>
                <strong><?php echo htmlspecialchars(!empty($selectedEmployee['Language']) ? $selectedEmployee['Language'] : 'N/A'); ?></strong>
            </div>

            <div class="detail-item">
                <span>Education</span>
                <strong><?php echo htmlspecialchars(!empty($selectedEmployee['education']) ? $selectedEmployee['education'] : 'N/A'); ?></strong>
            </div>

            <div class="detail-item">
                <span>Experience</span>
                <strong><?php echo htmlspecialchars(!empty($selectedEmployee['experience']) ? $selectedEmployee['experience'] : 'N/A'); ?></strong>
            </div>

            <div class="detail-item" style="grid-column: 1 / -1;">
                <span>Skills</span>
                <strong><?php echo htmlspecialchars(!empty($selectedEmployee['skill_name']) ? $selectedEmployee['skill_name'] : 'N/A'); ?></strong>
            </div>

            <?php if (!empty($selectedEmployee['training_name'])): ?>
            <div class="detail-item" style="grid-column: 1 / -1;">
                <span>Training / Certification</span>
                <strong><?php echo htmlspecialchars($selectedEmployee['training_name']); ?></strong>
            </div>
            <?php endif; ?>
        </div>

        <div class="documents-section">
            <h3>Documents</h3>
            <div class="document-item">
                <span>Resume</span>
                <?php 
                $resPath = $selectedEmployee['Resume'] ?? '';
                $resHref = !empty($resPath) ? (strpos($resPath, '../') === 0 ? $resPath : '../' . $resPath) : '';
                if (!empty($resHref)) { 
                ?>
                    <a href="<?php echo htmlspecialchars($resHref); ?>" target="_blank" class="doc-btn">
                        View Resume
                    </a>
                <?php } else { ?>
                    <strong>N/A</strong>
                <?php } ?>
            </div>

            <div class="document-item">
                <span>Certificate</span>
                <?php 
                $certPath = $selectedEmployee['certificate'] ?? '';
                $certHref = !empty($certPath) ? (strpos($certPath, '../') === 0 ? $certPath : '../' . $certPath) : '';
                if (!empty($certHref)) { 
                ?>
                    <a href="<?php echo htmlspecialchars($certHref); ?>" target="_blank" class="doc-btn">
                        View Certificate
                    </a>
                <?php } else { ?>
                    <strong>N/A</strong>
                <?php } ?>
            </div>
        </div>

        <div class="modal-footer">
            <a href="providerhome.php" class="close-button">Close</a>
        </div>
    </div>
</div>
<?php endif; ?>

</body>
</html>