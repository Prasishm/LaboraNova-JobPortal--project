<?php
session_start();
include "../database/conn.php";

if (isset($_POST['submit'])) {

    $email = trim($_POST['mail']);
    $password = $_POST['password'];

    // ==========================================
    // 1. CHECK ADMIN LOGIN
    // ==========================================

    $admin_sql = "SELECT * FROM admin WHERE email = ?";
    $admin_stmt = mysqli_prepare($conn, $admin_sql);

    mysqli_stmt_bind_param($admin_stmt, "s", $email);
    mysqli_stmt_execute($admin_stmt);

    $admin_result = mysqli_stmt_get_result($admin_stmt);

    if ($admin_result && mysqli_num_rows($admin_result) > 0) {

        $admin = mysqli_fetch_assoc($admin_result);

        // If admin password is stored as a hashed password
        if (password_verify($password, $admin['password'])) {

            $_SESSION['admin_id'] = $admin['Admin_id'];
            $_SESSION['Admin_name'] = $admin['Admin_name'];
            $_SESSION['admin_email'] = $admin['email'];

            header("Location: ../pages/admin.php");
            exit();

        } else {

            echo "<script>
                    alert('Invalid email or password!');
                    window.location.href = 'loginseeker.php';
                  </script>";
            exit();
        }
    }

    // ==========================================
    // 2. CHECK JOB SEEKER LOGIN
    // ==========================================

    $sql = "SELECT * FROM jobseeker WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {

            $_SESSION['jobseeker_id'] = $row['jobseeker_id'];
            $_SESSION['Full_name'] = $row['Full_name'];
            $_SESSION['jobseeker_email'] = $row['email'];

            header("Location: ../pages/seekerdashboardhome.php");
            exit();

        } else {

            echo "<script>
                    alert('Invalid email or password!');
                    window.location.href = 'loginseeker.php';
                  </script>";
            exit();
        }
    }

    // ==========================================
    // 3. EMAIL NOT FOUND
    // ==========================================

    echo "<script>
            alert('Invalid email or password!');
            window.location.href = 'loginseeker.php';
          </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>seeker login</title>
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <div class="main">
        <div class="right">
            <div class="text-container">
            <img class="right-img" src="../assets/lavoranovaaalogo.png" alt="">
            <h2>Login </h2>
            <p class="grey">Sign in to your Job Seeker account</p>
            </div>
            <form action="" method="post">

                <label class="gap" for="Mail">Email</label>
                <input type="email" name="mail" id="Mail" required>

                <label class="gap" for="Password">Password</label>
                <input type="password" name="password" id="Password" required>

                <button class="log font" type="submit" name="submit">Log in</button>
                <div class="sign-in"><p>Sign in as <a href="../pages/logincompany.php">Job Provider</a></p></div>
                <div class="divider">
                    <span></span>
                    <p>or</p>
                    <span></span>
                </div>

                <div class="signup">
                    <a href="signupworker.php" class="log signup">Sign up as a Job Seeker</a>
                    <a href="signupcompany.php" class="log signup">Sign up as a Job Provider</a>
                </div>
            </form>
        </div>

        <div class="left">
            <img src="../assets/samantha-borges-ax3lbQfdXP0-unsplash.jpg" alt="">
        </div>
    </div>

</body>
</html>