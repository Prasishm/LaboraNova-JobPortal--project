<?php
include "../database/conn.php";

$selectedProvider = null;
$providerJobs = null;

// View Job Provider details
if (isset($_GET['view_id'])) {
    $providerId = (int)$_GET['view_id'];

    $detailsSql = "
        SELECT 
            jp.*,
            (SELECT COUNT(*) FROM job WHERE jobprovider_id = jp.jobprovider_id) AS total_jobs,
            (SELECT COUNT(*) FROM application a JOIN job j ON a.job_id = j.job_id WHERE j.jobprovider_id = jp.jobprovider_id) AS total_applicants,
            (SELECT COUNT(*) FROM application a JOIN job j ON a.job_id = j.job_id WHERE j.jobprovider_id = jp.jobprovider_id AND a.app_status = 'Approved') AS total_hired
        FROM jobprovider jp
        WHERE jp.jobprovider_id = ?
    ";

    $stmt = mysqli_prepare($conn, $detailsSql);
    mysqli_stmt_bind_param($stmt, "i", $providerId);
    mysqli_stmt_execute($stmt);
    $detailsResult = mysqli_stmt_get_result($stmt);

    if ($detailsResult && mysqli_num_rows($detailsResult) > 0) {
        $selectedProvider = mysqli_fetch_assoc($detailsResult);

        // Fetch jobs posted by this company
        $jq = "
            SELECT 
                j.*,
                (SELECT COUNT(*) FROM application WHERE job_id = j.job_id) AS app_count
            FROM job j 
            WHERE j.jobprovider_id = ? 
            ORDER BY j.job_id DESC
        ";
        $jstmt = mysqli_prepare($conn, $jq);
        mysqli_stmt_bind_param($jstmt, "i", $providerId);
        mysqli_stmt_execute($jstmt);
        $providerJobs = mysqli_stmt_get_result($jstmt);
    }
}

// Get all job providers
$sql = "
    SELECT
        jp.*,
        (SELECT COUNT(*) FROM job WHERE jobprovider_id = jp.jobprovider_id) AS total_jobs,
        (SELECT COUNT(*) FROM application a JOIN job j ON a.job_id = j.job_id WHERE j.jobprovider_id = jp.jobprovider_id) AS total_applicants,
        (SELECT COUNT(*) FROM application a JOIN job j ON a.job_id = j.job_id WHERE j.jobprovider_id = jp.jobprovider_id AND a.app_status = 'Approved') AS total_hired
    FROM jobprovider jp
    ORDER BY jp.jobprovider_id DESC
";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

$totalProviders = mysqli_num_rows($result);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Providers - LaboraNova Admin</title>
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/adminprovider.css">
</head>

<body>

<div class="main">

    <!-- ================= LEFT SIDEBAR ================= -->
    <div class="left">
        <nav>
            <div class="top">
                <img class="img-top" src="../assets/lavoranovaaa.png" alt="LABORANOVA">
            </div>

            <div class="center">
                <a href="admin.php">
                    Jobseeker
                </a>

                <a href="adminprovider.php" class="active">
                    Job Provider
                </a>
            </div>

            <a href="../pages/landing.php" class="logout">
                Log out
            </a>
        </nav>
    </div>

    <!-- ================= RIGHT CONTENT ================= -->
    <div class="right">
        <div class="content-card">
            <h2 class="card-title-standard">
                All Job Providers (<?php echo $totalProviders; ?>)
            </h2>

            <div class="table-responsive">
                <table class="simple-table">
                    <thead>
                        <tr>
                            <th>COMPANY</th>
                            <th>EMAIL</th>
                            <th>PHONE</th>
                            <th>JOBS POSTED</th>
                            <th>HIRED WORKERS</th>
                            <th>ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    if ($totalProviders > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $providerId = (int)$row['jobprovider_id'];
                            $companyName = $row['company_name'];
                            $email = $row['email'];
                            $phone = $row['phone'];
                            $totalJobs = (int)$row['total_jobs'];
                            $totalHired = (int)$row['total_hired'];
                            $companyInitial = strtoupper(substr($companyName, 0, 1));
                    ?>
                        <tr>
                            <!-- COMPANY -->
                            <td>
                                <div class="table-user-cell">
                                    <div class="user-avatar-circle">
                                        <?php echo $companyInitial; ?>
                                    </div>
                                    <div>
                                        <strong><?php echo htmlspecialchars($companyName); ?></strong>
                                    </div>
                                </div>
                            </td>

                            <!-- EMAIL -->
                            <td>
                                <?php echo htmlspecialchars($email); ?>
                            </td>

                            <!-- PHONE -->
                            <td>
                                <?php echo htmlspecialchars($phone); ?>
                            </td>

                            <!-- JOBS POSTED -->
                            <td>
                                <span class="badge live" style="font-size: 12px;">
                                    <?php echo $totalJobs; ?> job(s)
                                </span>
                            </td>

                            <!-- HIRED WORKERS -->
                            <td>
                                <span class="badge closing" style="font-size: 12px;">
                                    <?php echo $totalHired; ?> hired
                                </span>
                            </td>

                            <!-- ACTION -->
                            <td>
                                <a href="adminprovider.php?view_id=<?php echo $providerId; ?>" class="btn-table-view">
                                    View
                                </a>
                            </td>
                        </tr>
                    <?php
                        }
                    } else {
                    ?>
                        <tr>
                            <td colspan="6" style="text-align:center; padding: 30px; color: #64748b;">
                                No job providers found.
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

<!-- ================= JOB PROVIDER DETAILS MODAL ================= -->
<?php if ($selectedProvider != null) { 
    $companyInitial = strtoupper(substr($selectedProvider['company_name'], 0, 1));
?>
<div class="modal-overlay" style="display: flex;">
    <div class="modal-box" style="max-width: 720px;">
        <div class="modal-header">
            <h2>Job Provider Information</h2>
            <a href="adminprovider.php" class="modal-close">&times;</a>
        </div>

        <!-- PROFILE / COMPANY HEADER -->
        <div class="profile-section">
            <div class="profile-placeholder" style="background: #f15a3a; font-size: 26px;">
                <?php echo $companyInitial; ?>
            </div>

            <div>
                <h3><?php echo htmlspecialchars($selectedProvider['company_name']); ?></h3>
                <p style="color: #64748b; font-size: 13px; margin-top: 2px;">
                    Registered Employer on LaboraNova
                </p>
            </div>
        </div>

        <!-- QUICK STATS -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 22px;">
            <div style="background: #fff0e9; padding: 12px 14px; border-radius: 10px; text-align: center; border: 1px solid #fed7aa;">
                <span style="display: block; font-size: 11px; color: #c2410c; font-weight: 700; text-transform: uppercase;">Jobs Posted</span>
                <strong style="font-size: 20px; color: #9a3412;"><?php echo (int)$selectedProvider['total_jobs']; ?></strong>
            </div>

            <div style="background: #eff6ff; padding: 12px 14px; border-radius: 10px; text-align: center; border: 1px solid #bfdbfe;">
                <span style="display: block; font-size: 11px; color: #1d4ed8; font-weight: 700; text-transform: uppercase;">Applications</span>
                <strong style="font-size: 20px; color: #1e40af;"><?php echo (int)$selectedProvider['total_applicants']; ?></strong>
            </div>

            <div style="background: #ecfdf5; padding: 12px 14px; border-radius: 10px; text-align: center; border: 1px solid #a7f3d0;">
                <span style="display: block; font-size: 11px; color: #047857; font-weight: 700; text-transform: uppercase;">Hired Workers</span>
                <strong style="font-size: 20px; color: #065f46;"><?php echo (int)$selectedProvider['total_hired']; ?></strong>
            </div>
        </div>

        <!-- DETAILS -->
        <div class="details-container">
            <div class="detail-item">
                <span>Company Name</span>
                <strong><?php echo htmlspecialchars($selectedProvider['company_name']); ?></strong>
            </div>

            <div class="detail-item">
                <span>Email Address</span>
                <strong><?php echo htmlspecialchars($selectedProvider['email']); ?></strong>
            </div>

            <div class="detail-item">
                <span>Phone Number</span>
                <strong><?php echo htmlspecialchars($selectedProvider['phone']); ?></strong>
            </div>

            <div class="detail-item">
                <span>Company Registration No.</span>
                <strong><?php echo htmlspecialchars(!empty($selectedProvider['company_registration']) ? $selectedProvider['company_registration'] : 'N/A'); ?></strong>
            </div>

            <div class="detail-item" style="grid-column: 1 / -1;">
                <span>Company Description</span>
                <p style="font-size: 14px; color: #334155; line-height: 1.5; margin-top: 4px;">
                    <?php echo nl2br(htmlspecialchars(!empty($selectedProvider['company_description']) ? $selectedProvider['company_description'] : 'No company description provided yet.')); ?>
                </p>
            </div>
        </div>

        <!-- POSTED JOBS SECTION -->
        <div class="documents-section">
            <h3>Recent Jobs Posted by this Company (<?php echo (int)$selectedProvider['total_jobs']; ?>)</h3>
            
            <?php if ($providerJobs && mysqli_num_rows($providerJobs) > 0) { ?>
                <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 10px;">
                    <?php while ($pj = mysqli_fetch_assoc($providerJobs)) { ?>
                        <div style="display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            <div>
                                <strong style="font-size: 14px; color: #1e293b;"><?php echo htmlspecialchars($pj['job_title']); ?></strong>
                                <small style="display: block; color: #64748b; font-size: 12px; margin-top: 2px;">
                                    <?php echo htmlspecialchars($pj['job_location']); ?> | <?php echo htmlspecialchars($pj['job_type']); ?> | Rs. <?php echo number_format((float)$pj['salary'], 2); ?>
                                </small>
                            </div>
                            <div style="text-align: right;">
                                <span style="display: block; font-size: 11px; color: #64748b;">Due: <?php echo htmlspecialchars($pj['due_date']); ?></span>
                                <span style="font-size: 12px; font-weight: 600; color: #2563eb;">
                                    <?php echo (int)($pj['app_count'] ?? 0); ?> applicants
                                </span>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <p style="color: #64748b; font-size: 13px; margin-top: 6px;">This provider has not posted any jobs yet.</p>
            <?php } ?>
        </div>

        <div class="modal-footer">
            <a href="adminprovider.php" class="close-button">
                Close
            </a>
        </div>
    </div>
</div>
<?php } ?>

</body>
</html>