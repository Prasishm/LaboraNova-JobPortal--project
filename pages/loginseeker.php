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

        // ADMIN PASSWORD IS PLAIN TEXT
        if ($password === $admin['password']) {

            $_SESSION['admin_id'] = $admin['Admin_id'];
            $_SESSION['Admin_name'] = $admin['Admin_name'];
            $_SESSION['admin_email'] = $admin['email'];

            // Go to Admin Dashboard
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

    $seeker_sql = "SELECT * FROM jobseeker WHERE email = ?";
    $seeker_stmt = mysqli_prepare($conn, $seeker_sql);

    mysqli_stmt_bind_param($seeker_stmt, "s", $email);
    mysqli_stmt_execute($seeker_stmt);

    $seeker_result = mysqli_stmt_get_result($seeker_stmt);

    if ($seeker_result && mysqli_num_rows($seeker_result) > 0) {

        $seeker = mysqli_fetch_assoc($seeker_result);

        // JOB SEEKER PASSWORD IS HASHED
        if (password_verify($password, $seeker['password'])) {

            $_SESSION['jobseeker_id'] = $seeker['jobseeker_id'];
            $_SESSION['Full_name'] = $seeker['Full_name'];
            $_SESSION['jobseeker_email'] = $seeker['email'];

            // Go to Job Seeker Dashboard
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