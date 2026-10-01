<?php
session_start();
include "../database/conn.php";

/* =========================
   CHECK JOB PROVIDER LOGIN
========================= */

if (!isset($_SESSION['company_name']) && !isset($_SESSION['jobprovider_id'])) {
    header("Location: logincompany.php");
    exit();
}

$jobprovider_id = $_SESSION['jobprovider_id'] ?? null;
$session_company_name = $_SESSION['company_name'] ?? '';

// If jobprovider_id not set in session, retrieve from DB
if (!$jobprovider_id && !empty($session_company_name)) {
    $stmt = mysqli_prepare($conn, "SELECT jobprovider_id FROM jobprovider WHERE company_name = ?");
    mysqli_stmt_bind_param($stmt, "s", $session_company_name);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($res)) {
        $jobprovider_id = (int)$row['jobprovider_id'];
        $_SESSION['jobprovider_id'] = $jobprovider_id;
    }
}

/* =========================
   FETCH JOB PROVIDER
========================= */

$sql = "SELECT * FROM jobprovider WHERE jobprovider_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $jobprovider_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$provider = mysqli_fetch_assoc($result);

if (!$provider) {
    echo "Job Provider account not found.";
    exit();
}

/* =========================
   UPDATE PROVIDER PROFILE
========================= */

if (isset($_POST["update_provider"])) {
    $company_name = trim($_POST["company_name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $company_registration = trim($_POST["company_registration"]);
    $company_description = trim($_POST["company_description"]);

    $current_password = $_POST["current_password"] ?? '';
    $new_password = $_POST["new_password"] ?? '';
    $confirm_password = $_POST["confirm_password"] ?? '';

    // Check duplicate Email for other providers
    $chk_email_sql = "SELECT jobprovider_id FROM jobprovider WHERE email = ? AND jobprovider_id != ?";
    $chk_stmt = mysqli_prepare($conn, $chk_email_sql);
    mysqli_stmt_bind_param($chk_stmt, "si", $email, $jobprovider_id);
    mysqli_stmt_execute($chk_stmt);
    $chk_result = mysqli_stmt_get_result($chk_stmt);

    if (mysqli_num_rows($chk_result) > 0) {
        echo "<script>
            alert('This email is already registered by another company.');
            window.history.back();
        </script>";
        exit();
    }

    // Check duplicate Phone for other providers
    $chk_phone_sql = "SELECT jobprovider_id FROM jobprovider WHERE phone = ? AND jobprovider_id != ?";
    $chk_p_stmt = mysqli_prepare($conn, $chk_phone_sql);
    mysqli_stmt_bind_param($chk_p_stmt, "si", $phone, $jobprovider_id);
    mysqli_stmt_execute($chk_p_stmt);
    $chk_p_result = mysqli_stmt_get_result($chk_p_stmt);

    if (mysqli_num_rows($chk_p_result) > 0) {
        echo "<script>
            alert('This phone number is already registered by another company.');
            window.history.back();
        </script>";
        exit();
    }

    // Handle Password Change Validation
    $password_to_update = null;

    if (!empty($new_password) || !empty($current_password) || !empty($confirm_password)) {
        if (empty($current_password)) {
            echo "<script>
                alert('Please enter your current (old) password to set a new password.');
                window.history.back();
            </script>";
            exit();
        }

        // Verify current password against database hash or plaintext
        $is_old_password_correct = password_verify($current_password, $provider['password']) || ($current_password === $provider['password']);

        if (!$is_old_password_correct) {
            echo "<script>
                alert('Current password does not match. Please enter your correct old password.');
                window.history.back();
            </script>";
            exit();
        }

        if (empty($new_password)) {
            echo "<script>
                alert('Please enter a new password.');
                window.history.back();
            </script>";
            exit();
        }

        if (strlen($new_password) < 6) {
            echo "<script>
                alert('New password must be at least 6 characters long.');
                window.history.back();
            </script>";
            exit();
        }

        if ($new_password !== $confirm_password) {
            echo "<script>
                alert('New password and Confirm password do not match.');
                window.history.back();
            </script>";
            exit();
        }

        // Hash new password
        $password_to_update = password_hash($new_password, PASSWORD_DEFAULT);
    }

    mysqli_begin_transaction($conn);

    try {
        if ($password_to_update !== null) {
            $update_sql = "
                UPDATE jobprovider
                SET
                    company_name = ?,
                    email = ?,
                    phone = ?,
                    company_description = ?,
                    company_registration = ?,
                    password = ?
                WHERE jobprovider_id = ?
            ";
            $up_stmt = mysqli_prepare($conn, $update_sql);
            mysqli_stmt_bind_param(
                $up_stmt,
                "ssssssi",
                $company_name,
                $email,
                $phone,
                $company_description,
                $company_registration,
                $password_to_update,
                $jobprovider_id
            );
        } else {
            $update_sql = "
                UPDATE jobprovider
                SET
                    company_name = ?,
                    email = ?,
                    phone = ?,
                    company_description = ?,
                    company_registration = ?
                WHERE jobprovider_id = ?
            ";
            $up_stmt = mysqli_prepare($conn, $update_sql);
            mysqli_stmt_bind_param(
                $up_stmt,
                "sssssi",
                $company_name,
                $email,
                $phone,
                $company_description,
                $company_registration,
                $jobprovider_id
            );
        }

        mysqli_stmt_execute($up_stmt);

        // Keep company_name synchronized in application table
        $sync_app_sql = "
            UPDATE application a
            INNER JOIN job j ON a.job_id = j.job_id
            SET a.company_name = ?
            WHERE j.jobprovider_id = ?
        ";
        $sync_stmt = mysqli_prepare($conn, $sync_app_sql);
        mysqli_stmt_bind_param($sync_stmt, "si", $company_name, $jobprovider_id);
        mysqli_stmt_execute($sync_stmt);

        mysqli_commit($conn);

        $_SESSION['company_name'] = $company_name;
        $_SESSION['jobprovider_email'] = $email;

        echo "<script>
            alert('Company profile updated successfully!');
            window.location.href = 'providereditprofile.php';
        </script>";
        exit();

    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "<script>
            alert('Failed to update profile. Please try again.');
            window.history.back();
        </script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Company Profile - LaboraNova</title>
    <link rel="stylesheet" href="../css/edit.css">
</head>

<body>

<div class="dashboard">

    <!-- =========================
         SIDEBAR
    ========================== -->
    <aside class="sidebar">
        <div>
            <div class="logo">
                <img src="../assets/lavoranovaaa.png" alt="LaboraNova">
            </div>

            <nav class="navigation">
                <a href="providerhome.php" class="nav-item">
                    <span>Home</span>
                </a>

                <a href="providerpostjob.php" class="nav-item">
                    <span>Post Job</span>
                </a>

                <a href="providerapplication.php" class="nav-item">
                    <span>Applications</span>
                </a>

                <a href="providereditprofile.php" class="nav-item" style="background: #fff0e9; color: #f15a3a; font-weight: 700;">
                    <span>Edit Profile</span>
                </a>
            </nav>
        </div>

        <div class="sidebar-bottom">
            <div class="provider-small">
                <div>
                    <strong><?php echo htmlspecialchars($_SESSION['company_name'] ?? $provider['company_name']); ?></strong>
                    <small>Job Provider</small>
                </div>
            </div>

            <div class="logout">
                <a href="logincompany.php">Log out</a>
            </div>
        </div>
    </aside>

    <!-- =========================
         MAIN CONTENT
    ========================== -->
    <main class="main-content">

        <div class="top-header">
            <h1>Edit Company Profile</h1>
            <p>Update your company details, contact information, and employer branding.</p>
        </div>

        <div class="form-card">
            <div class="card-header">
                <h2>Company Information</h2>
                <p>Modify the information below to keep your company profile up to date.</p>
            </div>

            <form method="POST">
                <div class="form-grid">

                    <!-- =========================
                         SECTION: COMPANY DETAILS
                    ========================== -->
                    <div class="section-title">
                        Company Details
                    </div>

                    <div class="form-group">
                        <label for="company_name">Company Name</label>
                        <input
                            type="text"
                            id="company_name"
                            name="company_name"
                            value="<?php echo htmlspecialchars($provider['company_name'] ?? ''); ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="<?php echo htmlspecialchars($provider['email'] ?? ''); ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            value="<?php echo htmlspecialchars($provider['phone'] ?? ''); ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="company_registration">Company Registration / PAN No.</label>
                        <input
                            type="text"
                            id="company_registration"
                            name="company_registration"
                            placeholder="e.g. 102938475-NP"
                            value="<?php echo htmlspecialchars($provider['company_registration'] ?? ''); ?>"
                        >
                    </div>

                    <div class="form-group full">
                        <label for="company_description">About the Company / Overview</label>
                        <textarea
                            id="company_description"
                            name="company_description"
                            maxlength="500"
                            placeholder="Briefly describe what your company does, your work culture, and industry focus (max 500 characters)..."
                        ><?php echo htmlspecialchars($provider['company_description'] ?? ''); ?></textarea>
                    </div>

                    <!-- =========================
                         SECTION: SECURITY & PASSWORD
                    ========================== -->
                    <div class="section-title">
                        Change Password (Optional)
                    </div>

                    <div class="form-group full">
                        <label for="current_password">Current (Old) Password</label>
                        <input
                            type="password"
                            id="current_password"
                            name="current_password"
                            placeholder="Enter your current password to authorize change"
                        >
                    </div>

                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input
                            type="password"
                            id="new_password"
                            name="new_password"
                            placeholder="Enter new password (min 6 characters)"
                        >
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input
                            type="password"
                            id="confirm_password"
                            name="confirm_password"
                            placeholder="Re-enter new password"
                        >
                    </div>

                    <!-- =========================
                         BUTTON
                    ========================== -->
                    <div class="form-actions">
                        <button type="submit" name="update_provider">
                            Save Changes
                        </button>
                    </div>

                </div>
            </form>
        </div>

    </main>

</div>

</body>
</html>
