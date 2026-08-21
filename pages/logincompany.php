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

        // ADMIN PASSWORD IS NOT HASHED
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
                    window.location.href = 'logincompany.php';
                  </script>";
            exit();
        }
    }


    // ==========================================
    // 2. CHECK COMPANY / JOB PROVIDER LOGIN
    // ==========================================

    $company_sql = "SELECT * FROM jobprovider WHERE email = ?";
    $company_stmt = mysqli_prepare($conn, $company_sql);

    mysqli_stmt_bind_param($company_stmt, "s", $email);
    mysqli_stmt_execute($company_stmt);

    $company_result = mysqli_stmt_get_result($company_stmt);

    if ($company_result && mysqli_num_rows($company_result) > 0) {

        $company = mysqli_fetch_assoc($company_result);

        // COMPANY PASSWORD IS HASHED
        if (password_verify($password, $company['password'])) {

            $_SESSION['jobprovider_id'] = $company['jobprovider_id'];
            $_SESSION['company_name'] = $company['company_name'];
            $_SESSION['jobprovider_email'] = $company['email'];

            // Go to Company Dashboard
            header("Location: ../pages/providerhome.php");
            exit();

        } else {

            echo "<script>
                    alert('Invalid email or password!');
                    window.location.href = 'logincompany.php';
                  </script>";
            exit();
        }
    }


    // ==========================================
    // 3. EMAIL NOT FOUND
    // ==========================================

    echo "<script>
            alert('Invalid email or password!');
            window.location.href = 'logincompany.php';
          </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company login</title>
    <link rel="stylesheet" href="../css/login.css">
</head>

<body>
    <div class="main">
        <div class="right">
            <div class="text-container">
            <img class="right-img" src="../assets/lavoranovaaalogo.png" alt="">
            <h2>Login </h2>
            <p class="grey">Sign in to your Job Provider account</p>
            </div>
            <form action="" method="post">

                <label class="gap" for="Mail">Email</label>
                <input type="email" name="mail" id="Mail" required>

                <label class="gap" for="Password">Password</label>
                <input type="password" name="password" id="Password" required>

                <button class="log font" type="submit" name="submit">Log in</button>
                <div class="sign-in"><p>Sign in as <a href="../pages/loginseeker.php">Job Seeker</a></p></div>
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