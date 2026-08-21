<?php
session_start();
include "../database/conn.php";


// Check if job seeker is logged in
if (!isset($_SESSION['jobseeker_id'])) {
    header("Location: loginseeker.php");
    exit();
}

$jobseeker_id = $_SESSION['jobseeker_id'];


// Fetch current profile data
$sql = "SELECT * FROM jobseeker WHERE jobseeker_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $jobseeker_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "User not found.";
    exit();
}


// Update profile
if (isset($_POST['update_profile'])) {

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $gender = $_POST['gender'];
    $language = trim($_POST['language']);
    $resume = trim($_POST['resume']);
    $citizenship = trim($_POST['citizenship']);


    // Check if email already belongs to another user
    $checkEmail = "SELECT jobseeker_id FROM jobseeker
                   WHERE email = ? AND jobseeker_id != ?";

    $stmt = mysqli_prepare($conn, $checkEmail);
    mysqli_stmt_bind_param($stmt, "si", $email, $jobseeker_id);
    mysqli_stmt_execute($stmt);

    $emailResult = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($emailResult) > 0) {

        echo "<script>
                alert('This email is already used by another account.');
                window.history.back();
              </script>";
        exit();
    }


    // Check if phone already belongs to another user
    $checkPhone = "SELECT jobseeker_id FROM jobseeker
                   WHERE phone = ? AND jobseeker_id != ?";

    $stmt = mysqli_prepare($conn, $checkPhone);
    mysqli_stmt_bind_param($stmt, "si", $phone, $jobseeker_id);
    mysqli_stmt_execute($stmt);

    $phoneResult = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($phoneResult) > 0) {

        echo "<script>
                alert('This phone number is already used by another account.');
                window.history.back();
              </script>";
        exit();
    }


    // Update database
    $updateSql = "UPDATE jobseeker SET
                    Full_name = ?,
                    email = ?,
                    phone = ?,
                    address = ?,
                    gender = ?,
                    Language = ?,
                    Resume = ?,
                    Citizenship = ?
                  WHERE jobseeker_id = ?";

    $stmt = mysqli_prepare($conn, $updateSql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssssssi",
        $fullname,
        $email,
        $phone,
        $address,
        $gender,
        $language,
        $resume,
        $citizenship,
        $jobseeker_id
    );


    if (mysqli_stmt_execute($stmt)) {

        // Update session information
        $_SESSION['jobseeker_name'] = $fullname;

        echo "<script>
                alert('Profile updated successfully!');
                window.location.href = 'editprofile.php';
              </script>";
        exit();

    } else {

        echo "<script>
                alert('Failed to update profile.');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/edit.css">
</head>
<body>
    <div class="dashboard">
    <aside class="sidebar">

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

           

        </nav>

        <div class="sidebar-bottom">

            <div class="provider-small">


                <div>
                    <strong><?php echo $_SESSION ['Full_name']?></strong>
                    <small>Employee</small>
                </div>

            </div>

            <div class="logout"><a href="../pages/loginseeker.php">Log out</a>
                </div>

        </div>
        
    </aside>
    <main class="main-content">

    <div class="top-header">

        <div>

            <h1>Edit Profile</h1>

            <p>
                Update your personal information and profile details.
            </p>

        </div>

    </div>


    <div class="form-card">

        <div class="card-header">

            <h2>Personal Information</h2>

            <p>
                Update your information below.
            </p>

        </div>


        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">

            <div class="form-grid">


                <!-- FULL NAME -->
                <div class="form-group">

                    <label for="fullname">
                        Full Name
                    </label>

                    <input
                        type="text"
                        id="fullname"
                        name="fullname"
                        value="<?php echo htmlspecialchars($user['Full_name']); ?>"
                        required>

                </div>


                <!-- EMAIL -->
                <div class="form-group">

                    <label for="email">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?php echo htmlspecialchars($user['email']); ?>"
                        required>

                </div>


                <!-- PHONE -->
                <div class="form-group">

                    <label for="phone">
                        Phone Number
                    </label>

                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="<?php echo htmlspecialchars($user['phone']); ?>"
                        required>

                </div>


                <!-- ADDRESS -->
                <div class="form-group">

                    <label for="address">
                        Address
                    </label>

                    <input
                        type="text"
                        id="address"
                        name="address"
                        value="<?php echo htmlspecialchars($user['address']); ?>"
                        required>

                </div>


                <!-- GENDER -->
                <div class="form-group">

                    <label for="gender">
                        Gender
                    </label>

                    <select
                        id="gender"
                        name="gender"
                        required>

                        <option value="">
                            Select Gender
                        </option>

                        <option value="Male"
                            <?php if ($user['gender'] == 'Male') echo 'selected'; ?>>
                            Male
                        </option>

                        <option value="Female"
                            <?php if ($user['gender'] == 'Female') echo 'selected'; ?>>
                            Female
                        </option>

                        <option value="Other"
                            <?php if ($user['gender'] == 'Other') echo 'selected'; ?>>
                            Other
                        </option>

                    </select>

                </div>


                <!-- LANGUAGE -->
                <div class="form-group">

                    <label for="language">
                        Language
                    </label>

                    <input
                        type="text"
                        id="language"
                        name="language"
                        placeholder="e.g. Nepali, English"
                        value="<?php echo htmlspecialchars($user['Language'] ?? ''); ?>">

                </div>


                <!-- RESUME -->
                <div class="form-group">

                    <label for="resume">
                        Resume
                    </label>

                    <input
                        type="file"
                        id="resume"
                        name="resume"
                        placeholder="Resume file "
                        value="<?php echo htmlspecialchars($user['Resume'] ?? ''); ?>">

                </div>


                <!-- CITIZENSHIP -->
                <div class="form-group">

                    <label for="citizenship">
                        Citizenship
                    </label>

                    <input
                        type="file"
                        id="citizenship"
                        name="citizenship"
                        placeholder="e.g. Citizenship "
                        value="<?php echo htmlspecialchars($user['Citizenship'] ?? ''); ?>">

                </div>


                <!-- BUTTON -->
                <div class="form-actions">

                    <button
                        type="submit"
                        name="update_profile">

                        Update Profile

                    </button>

                </div>


            </div>

        </form>

    </div>

</main>
</div>
</body>
</html>