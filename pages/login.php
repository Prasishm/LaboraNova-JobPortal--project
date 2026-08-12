<?php

include "../database/conn.php";

if (isset($_POST['submit'])) {
    $email = $_POST['mail'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM jobseeker WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['jobseeker_id'] = $row['jobseeker_id'];
            $_SESSION['Full_name'] = $row['Full_name'];
            header("Location: ../pages/seekerdashboardhome.php");
            exit();
        } else {
            echo "<script>alert('Invalid email or password!'); window.location.href = 'login.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid email or password!'); window.location.href = 'login.php';</script>";
    }
}   

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <link rel="stylesheet" href="../css//login.css">
</head>

<body>
    <div class="main">
        <div class="right">
            <img class="right-img" src="../assets/lavoranovaaa.png" alt="">
            <form action="" method="post">

                <label class="gap" for="Mail">Email</label><input type="main" name="mail" id="Mail">
                <label class="gap" for="Password">Password</label><input type="password" name="password" id="Password">

                <button class="log font" type="submit" name="submit">Log in</button>
                <div class="divider">
    <span></span>
    <p>or</p>
    <span></span>
</div>
                <div class="signup">
                    <a href="signupworker.php" class="log signup">Be a Job Seeker</a>
                    <a href="signupcompany.php" class="log signup">Be a Job Provider</a>
                </div>
            </form>
        </div>

        <div class="left">
            <img src="../assets//samantha-borges-ax3lbQfdXP0-unsplash.jpg" alt="">
        </div>
    </div>

</body>

</html>