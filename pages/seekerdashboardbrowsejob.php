<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css//seekerdashboardbrowsejob.css" />
</head>
<body>
    <div class="main">
    <div class="left">
    <nav>
    <div class="top">
        <img  class="img-top" src="../assets/lavoranovaaa.png" alt="">
    </div>
    <div class="center">
    <a href="../pages//seekerdashboardhome.php" class="grey">Home</a><br>
    <a href="../pages//seekerdashboardbrowsejob.php" class="grey">Browse Job</a><br>
    <a href="../pages//seekerbashboardcompany.php" class="grey">Browse Company</a><br>
    </div>
    <a href="../pages/login.php" class="logout">Log out</a><br>
    </nav>
    </div>
    <div class="right">
        <div class="main" id="browseJob">
        <div class="text">
            <h1>Find Your next role</h1>
            <p>9 live roles from verified companies</p>
        </div>
        <div class="search-bar">
            <div class="search"><img src="../assets/search.svg" alt="" class="img_logo"><input type="text" name="jobsearch" placeholder="Job title, Company"></div>
            <div class="search"><img src="../assets/map-pin.svg" alt="" class="img_logo"><input type="text" name="location" placeholder="Location or Remote"></div>
            <button>
                Search<img src="../assets/arrow-narrow-right.svg" class="img_logo" alt="" />
            </button>
        </div>
    </div>
    </div>



    </div>
</body>
</html>