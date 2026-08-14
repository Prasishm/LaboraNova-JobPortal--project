<?php

include "../database/conn.php";

if (isset($_POST['submit'])) {
    // Full Name
    $fullname = $_POST['username'];

    // Email
    $email = $_POST['mail'];

    // Password
    $password = $_POST['password'];

if (strlen($password) < 6) {

    echo "<script>
            alert('Password must be at least 6 characters.');
            window.history.back();
        </script>";
    exit();

}
$password = password_hash($password, PASSWORD_DEFAULT);
    // Date of Birth
    $date = $_POST['date'];

    // Phone Number
    $phone = $_POST['phone'];

    // Gender
    $gender = $_POST['gender'] ?? "";

    // Address
    $address = $_POST['address'];

    // Resume Upload
    $resume = "";


    // Citizenship Upload
    $citizenship = "";

    if (empty($fullname)) {

        echo "<script>
                alert('Please enter your full name.');
                window.history.back();
            </script>";
        exit();

    } 
   elseif (empty($email)) {

    echo "<script>
            alert('Please enter your email.');
            window.history.back();
          </script>";
    exit();

} elseif (!str_ends_with(strtolower($email), '@gmail.com')) {

    echo "<script>
            alert('Please enter a valid Gmail address (example@gmail.com).');
            window.history.back();
          </script>";
    exit();
}

     elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        echo "<script>
                alert('Please enter a valid email address.');
                window.history.back();
            </script>";
        exit();

    } elseif (empty($password)) {

        echo "<script>
                alert('Please enter your password.');
                window.history.back();
            </script>";
        exit();

    } elseif (strlen($password) < 6) {

        echo "<script>
                alert('Password must be at least 6 characters.');
                window.history.back();
            </script>";
        exit();

    } elseif (empty($date)) {

        echo "<script>
                alert('Please select your date of birth.');
                window.history.back();
            </script>";
        exit();

    } elseif (empty($phone)) {

        echo "<script>
                alert('Please enter your phone number.');
                window.history.back();
            </script>";
        exit();

    } elseif (!preg_match("/^[0-9]{10}$/", $phone)) {

        echo "<script>
                alert('Phone number must contain exactly 10 digits.');
                window.history.back();
            </script>";
        exit();

    } elseif (empty($gender)) {

        echo "<script>
                alert('Please select your gender.');
                window.history.back();
            </script>";
        exit();

    } elseif (empty($address)) {

        echo "<script>
                alert('Please select your address.');
                window.history.back();
            </script>";
        exit();
    }

    // Insert into database
$sql = "INSERT INTO jobseeker
(
    Full_name,
    email,
    password,
    phone,
    address,
    gender,
    Resume,
    Citizenship
)
VALUES
(
    '$fullname',
    '$email',
    '$password',
    '$phone',
    '$address',
    '$gender',
    '$resume',
    '$citizenship'
)";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Account created successfully!'); window.location.href = '../pages/loginseeker.php';</script>";
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
    <link rel="stylesheet" href="../css/signupwoker.css">
</head>

<body>
    <div class="main">
        <div class="left">
            <img src="../assets//samantha-borges-ax3lbQfdXP0-unsplash.jpg" alt="">
        </div>
        <div class="right">
            <img src="../assets/lavoranovaaa.png" alt="">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <label class="gap" for="abc">Full Name</label><input type="text" name="username" placeholder="Username" id="name">
                <label class="gap" for="Mail">Email</label><input type="email" name="mail" placeholder="Mail" id="Mail">
                <label class="gap" for="Password">Password</label><input type="password" name="password" placeholder="Password" id="Password">
                <div class="same-line">
                    <div class="date-phone"><label class="gap"> Date&nbspof&nbspBirth </label> <label class="gap" for="phone">Phone number</label><br></div>
                    <input id="datee" type="date" name="date" placeholder="Select your date of Birth" id="">
                    <input type="tel" name="phone" placeholder="Enter your phone number" id="phone">
                </div>
                <label class="gap" for="">Gender</label>
                <div class="gender-container">
                    <label class="gender-option">
                        <span>Male</span>
                        <input type="radio" name="gender" value="Male">
                    </label>
                    <label class="gender-option">
                        <span>Female</span>
                        <input type="radio" name="gender" value="Female">
                    </label>
                    <label class="gender-option">
                        <span>Other<span>
                                <input type="radio" name="gender" value="Other">
                    </label>

                </div>
                <label class="gap" for="">Address</label>
                <select name="address" id="address">
                    <option value="">Select &nbsp Address</option>
                    <option value="Kathmandu">Kathmandu</option>
                    <option value="Lalitpur">Lalitpur</option>
                    <option value="Bhaktapur">Bhaktapur</option>
                    <option value="Kavrepalanchok">Kavrepalanchok</option>
                    <option value="Sindhupalchok">Sindhupalchok</option>
                    <option value="Makwanpur">Makwanpur</option>
                    <option value="Chitwan">Chitwan</option>
                    <option value="Pokhara">Pokhara</option>
                    <option value="Kaski">Kaski</option>
                    <option value="Lamjung">Lamjung</option>
                    <option value="Gorkha">Gorkha</option>
                    <option value="Tanahun">Tanahun</option>
                    <option value="Syangja">Syangja</option>
                    <option value="Butwal">Butwal</option>
                    <option value="Rupandehi">Rupandehi</option>
                    <option value="Dang">Dang</option>
                    <option value="Banke">Banke</option>
                    <option value="Bardiya">Bardiya</option>
                    <option value="Biratnagar">Biratnagar</option>
                    <option value="Morang">Morang</option>
                    <option value="Sunsari">Sunsari</option>
                    <option value="Janakpur">Janakpur</option>
                    <option value="Dhanusha">Dhanusha</option>
                    <option value="Birgunj">Birgunj</option>
                    <option value="Parsa">Parsa</option>
                </select>

                <button type="submit" name="submit">Create account</button>
                <p>Already have an account? <a href="../pages/loginseeker.php">Log in</a></p>
            </form>
        </div>

    </div>

</body>

</html>