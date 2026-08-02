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
        <img src="../assets/luis-villasmil-mlVbMbxfWI4-unsplash.jpg" alt="">
        </div>
        <div class="right">
            <img src="../assets/lavoranovaaa.png" alt="">
            <div class="right-input">
            <form action="../actions/signup.php" method="post">
                <label class="gap" for="abc">Comapny Name</label><input type="text" name="username" placeholder="Username" id="name">
                <div class="same-line">
                    <div class="mail-phone"><label class="gap" for="Mail">Email</label><label class="gap"for="phone">Phone number</label></div>
                    <input type="email" name="mail" placeholder="Mail" id="Mail">
                    
                    <input type="tel" name="phone" placeholder="Enter your phone number" id="phone">
                </div>
                
                <label class="gap" for="Password">Password</label><input type="password" name="password" placeholder="Password" id="Password">
                <button type="submit" name="submit">Create account</button>
                <p>Already have an account? <a href="Login.php">Log in</a></p>
                </div>
        </form>
        </div>
        </div>
        
    </div>

</body>
</html>