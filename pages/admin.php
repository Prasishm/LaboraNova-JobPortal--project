<?php
include "../database/conn.php";
$selectedJobseeker = null;

if (isset($_GET['view_id'])) {
    $jobseekerId = (int) $_GET['view_id'];

    $detailsSql = "
        SELECT
            j.*,
            s.skill_name,
            t.training_name,
            t.certificate
        FROM jobseeker j
        LEFT JOIN skill s ON j.skill_id = s.skill_id
        LEFT JOIN training t ON j.training_id = t.training_id
        WHERE j.jobseeker_id = ?
    ";

    $stmt = mysqli_prepare($conn, $detailsSql);
    mysqli_stmt_bind_param($stmt, "i", $jobseekerId);
    mysqli_stmt_execute($stmt);
    $detailsResult = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($detailsResult) > 0) {
        $selectedJobseeker = mysqli_fetch_assoc($detailsResult);
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
        j.experience,
        j.education,
        j.Resume,
        j.Citizenship,
        j.profile_image,
        s.skill_name
    FROM jobseeker j
    LEFT JOIN skill s ON j.skill_id = s.skill_id
    ORDER BY j.jobseeker_id DESC
";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}

$totalJobseekers = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobseekers - LaboraNova Admin</title>
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>

<div class="main">

    <!-- sidebar -->
    <div class="left">
        <nav>
            <div class="top">
                <img class="img-top" src="../assets/lavoranovaaa.png" alt="LABORANOVA">
            </div>

            <div class="center">
                <a href="admin.php" class="active">
                    Jobseeker
                </a>

                <a href="adminprovider.php">
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
                            <th>EXPERIENCE</th>
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
                            $address = !empty($row['address']) ? $row['address'] : 'N/A';
                            $skill = !empty($row['skill_name']) ? $row['skill_name'] : "N/A";
                            $exp = !empty($row['experience']) ? $row['experience'] : "N/A";
                            $initial = strtoupper(substr($name, 0, 1));
                    ?>
                            <tr>
                                <!-- JOBSEEKER -->
                                <td>
                                    <div class="table-user-cell">
                                        <div class="user-avatar-circle">
                                            <?php echo $initial; ?>
                                        </div>
                                        <div>
                                            <strong><?php echo htmlspecialchars($name); ?></strong>
                                            <small style="display: block; color: #64748b; font-size: 12px; margin-top: 2px;">
                                                <?php echo htmlspecialchars($email); ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <!-- SKILL -->
                                <td>
                                    <?php echo htmlspecialchars($skill); ?>
                                </td>

                                <!-- EXPERIENCE -->
                                <td>
                                    <?php echo htmlspecialchars($exp); ?>
                                </td>

                                <!-- LOCATION -->
                                <td>
                                    <?php echo htmlspecialchars($address); ?>
                                </td>

                                <!-- ACTION -->
                                <td>
                                    <a href="admin.php?view_id=<?php echo $row['jobseeker_id']; ?>" class="btn-table-view">
                                        View
                                    </a>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                    ?>
                        <tr>
                            <td colspan="5" style="text-align:center; padding: 30px; color: #64748b;">
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

<?php if ($selectedJobseeker != null) { 
    $pImg = $selectedJobseeker['profile_image'] ?? '';
    $pImgSrc = !empty($pImg) ? (strpos($pImg, '../') === 0 ? $pImg : '../' . $pImg) : '';
    $initial = strtoupper(substr($selectedJobseeker['Full_name'] ?? 'J', 0, 1));
?>

<div class="modal-overlay" style="display: flex;">
    <div class="modal-box">
        <div class="modal-header">
            <h2>Jobseeker Information</h2>
            <a href="admin.php" class="modal-close">&times;</a>
        </div>

        <!-- PROFILE IMAGE -->
        <div class="profile-section">
            <?php if (!empty($pImgSrc)) { ?>
                <img src="<?php echo htmlspecialchars($pImgSrc); ?>" class="profile-image" alt="Profile Image">
            <?php } else { ?>
                <div class="profile-placeholder">
                    <?php echo $initial; ?>
                </div>
            <?php } ?>

            <div>
                <h3><?php echo htmlspecialchars($selectedJobseeker['Full_name']); ?></h3>
                <p>Registered Job Seeker</p>
            </div>
        </div>

        <!-- DETAILS -->
        <div class="details-container">
            <div class="detail-item">
                <span>Full Name</span>
                <strong><?php echo htmlspecialchars($selectedJobseeker['Full_name']); ?></strong>
            </div>

            <div class="detail-item">
                <span>Email</span>
                <strong><?php echo htmlspecialchars($selectedJobseeker['email']); ?></strong>
            </div>

            <div class="detail-item">
                <span>Phone</span>
                <strong><?php echo htmlspecialchars($selectedJobseeker['phone']); ?></strong>
            </div>

            <div class="detail-item">
                <span>Address</span>
                <strong><?php echo htmlspecialchars(!empty($selectedJobseeker['address']) ? $selectedJobseeker['address'] : 'N/A'); ?></strong>
            </div>

            <div class="detail-item">
                <span>Gender</span>
                <strong><?php echo htmlspecialchars(!empty($selectedJobseeker['gender']) ? $selectedJobseeker['gender'] : 'N/A'); ?></strong>
            </div>

            <div class="detail-item">
                <span>Languages</span>
                <strong><?php echo htmlspecialchars(!empty($selectedJobseeker['Language']) ? $selectedJobseeker['Language'] : 'N/A'); ?></strong>
            </div>

            <div class="detail-item">
                <span>Education</span>
                <strong><?php echo htmlspecialchars(!empty($selectedJobseeker['education']) ? $selectedJobseeker['education'] : 'N/A'); ?></strong>
            </div>

            <div class="detail-item">
                <span>Experience</span>
                <strong><?php echo htmlspecialchars(!empty($selectedJobseeker['experience']) ? $selectedJobseeker['experience'] : 'N/A'); ?></strong>
            </div>

            <div class="detail-item" style="grid-column: 1 / -1;">
                <span>Skills</span>
                <strong><?php echo htmlspecialchars(!empty($selectedJobseeker['skill_name']) ? $selectedJobseeker['skill_name'] : 'N/A'); ?></strong>
            </div>

            <?php if (!empty($selectedJobseeker['training_name'])) { ?>
            <div class="detail-item" style="grid-column: 1 / -1;">
                <span>Training</span>
                <strong><?php echo htmlspecialchars($selectedJobseeker['training_name']); ?></strong>
            </div>
            <?php } ?>
        </div>

        <!-- <- DOCUMENTS > -->
        <div class="documents-section">
            <h3>Documents</h3>

            <!-- RESUME -->
            <div class="document-item">
                <span>Resume</span>
                <?php 
                $resPath = $selectedJobseeker['Resume'] ?? '';
                $resHref = !empty($resPath) ? (strpos($resPath, '../') === 0 ? $resPath : '../' . $resPath) : '';
                if (!empty($resHref)) { 
                ?>
                    <a href="<?php echo htmlspecialchars($resHref); ?>" target="_blank" class="doc-btn" style="color: #f15a3a; font-weight: 600;">
                        View Resume
                    </a>
                <?php } else { ?>
                    <strong>N/A</strong>
                <?php } ?>
            </div>
            <!-- CITIZENSHIP -->
            <div class="document-item">
                <span>Citizenship</span>
                <?php 
                $citPath = $selectedJobseeker['Citizenship'] ?? '';
                $citHref = !empty($citPath) ? (strpos($citPath, '../') === 0 ? $citPath : '../' . $citPath) : '';
                if (!empty($citHref)) { 
                ?>
                    <a href="<?php echo htmlspecialchars($citHref); ?>" target="_blank" class="doc-btn" style="color: #f15a3a; font-weight: 600;">
                        View Citizenship
                    </a>
                <?php } else { ?>
                    <strong>N/A</strong>
                <?php } ?>
            </div>
            <!-- CERTIFICATE -->
            <?php if (!empty($selectedJobseeker['certificate'])) { 
                $certPath = $selectedJobseeker['certificate'];
                $certHref = (strpos($certPath, '../') === 0 ? $certPath : '../' . $certPath);
            ?>
            <div class="document-item">
                <span>Training Certificate</span>
                <a href="<?php echo htmlspecialchars($certHref); ?>" target="_blank" class="doc-btn" style="color: #f15a3a; font-weight: 600;">
                    View Certificate
                </a>
            </div>
            <?php } ?>
        </div>

        <div class="modal-footer">
            <a href="admin.php" class="close-button">
                Close
            </a>
        </div>
    </div>
</div>

<?php } ?>

</body>
</html>