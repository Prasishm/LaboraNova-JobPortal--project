<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/browsejob.css">
</head>

<body>
    <nav>
        <div class="top">
            <a href="../pages/landing.php">
                <h3>LaboraNova</h3>
            </a>
        </div>
        <div class="b">
            <a href="../pages/browsejob.php">Browse Job</a>
            <a href="">About</a>
            <a href="">Log in</a>
            <a href="../pages/signupworker.php" class="signup">Sign up</a>
        </div>
    </nav>
    <div class="main">
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
    <div class="mid">
        <div class="filter-container">
            <div class="filter-header">
                <h3>Filter</h3>
                <a href="" class="clear">Clear all</a>
            </div>
            <div class="filter-section">
                <h4>Employment Type</h4>
            <label>
            <input type="checkbox">
            Full-time
        </label>

        <label>
            <input type="checkbox" >
            Part-time
        </label>

        <label>
            <input type="checkbox" >
            Contract
        </label>

        <label>
            <input type="checkbox" >
            Internship
        </label>
            </div>
            <div class="filter-section">
                <h4>Location</h4>
                <label>
            <input type="checkbox">
            Remote
        </label>

        <label>
            <input type="checkbox" >
            On-site
        </label>

        <label>
            <input type="checkbox" >
            Hybrid
        </label>
            </div>
            <div class="filter-section">
                <h4>Experience</h4>
                <label>
            <input type="checkbox" >
            Entry
        </label>

        <label>
            <input type="checkbox" >
            Mid
        </label>

        <label>
            <input type="checkbox" >
            Senior
        </label>
        <label>
            <input type="checkbox" >
            Lead
        </label>
            </div>
        </div>
    </div>
</body>

</html>