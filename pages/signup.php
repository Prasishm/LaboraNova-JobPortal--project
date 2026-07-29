<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <link rel="stylesheet" href="../css/signup.css">
</head>
<body>
    <div class="main">
        <div class="left">
        <img src="../assets/luis-villasmil-mlVbMbxfWI4-unsplash.jpg" alt="">
        </div>
        <div class="right">
            <img src="../assets/Screenshot_2026-07-28_195444-removebg-preview.png" alt="">
        <form action="../actions/signup.php" method="post">
        <label class="gap" for="abc">Full Name</label><input type="text" name="username" placeholder="Username" id="name">
        <label class="gap" for="Mail">Email</label><input type="main" name="mail" placeholder="Mail" id="Mail">
        <label class="gap" for="Password">Password</label><input type="password" name="password" placeholder="Password" id="Password">
        <label class="gap"> Date&nbspof&nbspBirth </label>
        <input id="datee" type="date" name="date" placeholder="Select your date of Birth" id="">
        <label class="gap"for="phone">Phone number</label><input type="tel" name="phone" placeholder="Enter your phone number" id="phone">
        <label class="gap" for="">Gender</label>
        <div class="gender-container">
            <label class="gender-option">
                <span>Male</span>
                <input type="radio" name="gender" value="Male" >
            </label>
            <label class="gender-option">
                <span>Female</span>
                <input type="radio" name="gender" value="Female" >
            </label>
            <label class="gender-option">
                <span>Other<span>
                <input type="radio" name="gender" value="Other" >
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
<label class="gap"  for="resume">Resume</label>
<input type="file" name="resume" id="resume">
<label class="gap" for="citizenship">Citizenship</label>
<input type="file" name="citizenship" id="citizenship">
<button type="submit" name="submit">Create account</button>
<p>Already have an account? <a href="Login.php">Log in</a></p>
        </form>
        </div>
        
    </div>

</body>
</html>