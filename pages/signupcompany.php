<?php

include "../database/conn.php";

if (isset($_POST['submit'])) {

    // Company Name
    $company_name = $_POST['company_name'];

    // Email
    $email = $_POST['mail'];

    // Password
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Phone Number
    $phone = $_POST['phone'];

    // Insert into database
    $sql = "INSERT INTO jobprovider
    (
        company_name,
        email,
        phone,
        password
    )
    VALUES
    (
        '$company_name',
        '$email',
        '$phone',
        '$password'
    )";

    if (mysqli_query($conn, $sql)) {

        echo "<script>
                alert('Company account created successfully!');
                window.location.href = '../pages/logincompany.php';
            </script>";

    } else {

        echo mysqli_error($conn);

    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <link rel="stylesheet" href="../css/signupcompany.css">
</head>
<body>
    <div class="main">
        <div class="left">
        <img src="../assets//samantha-borges-ax3lbQfdXP0-unsplash.jpg" alt="">
        </div>
        <div class="right">
            <img src="../assets/lavoranovaaa.png" alt="">
            <div class="right-input">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <label class="gap" for="abc">Comapny Name</label><input type="text" name="company_name" placeholder="company_name" id="name">
                <div class="same-line">
                    <div class="mail-phone"><label class="gap" for="Mail">Email</label><label class="gap"for="phone">Phone number</label></div>
                    <input type="email" name="mail" placeholder="Mail" id="Mail">
                    
                    <input type="tel" name="phone" placeholder="Enter your phone number" id="phone">
                </div>
                
                <label class="gap" for="Password">Password</label><input type="password" name="password" placeholder="Password" id="Password">
                <button type="submit" name="submit">Create account</button>
                <p>Already have an account? <a href="../pages/logincompany.php">Log in</a></p>
                </div>
        </form>
        </div>
        </div>
        
    </div>

</body>
</html>