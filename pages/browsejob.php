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
        <img  class="img-top" src="../assets/lavoranovaaa.png" alt="">
    </div>
    <div class="center">
    <a href="../pages/landing.php" class="grey">Home</a>
    <a href="../pages/browsejob.php" class="grey">Browse Job</a>
    <a href="../pages/companies.php" class="grey">Browse Company</a>
    <a href="" class="grey">About</a>
    </div>
    <div class="b">
        <a href="" class="sign signcommon">Sign in</a>
        <a href="../pages/signupworker.php" class="signup signcommon">Sign up</a>
    </div>
    </nav>
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
    <div class="mid">
        <div class="filter-container">
            <div class="filter-header">
                <h3>Filter</h3>
                <a href="" class="hidden clearBtn">Clear all</a>
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
    <div class="mid-right">
    <div class="right-content">
        <div class="right-topic"><p>Linear </p></div>
        <h4>Senior Product Designer</h4>
        <div class="right-text">
        <p class="grey"><img src="../assets/map-pin.svg" alt="" class="img_logo"> Remote . Worldwide </p>&nbsp
        <p class="grey">Full time</p>&nbsp&nbsp
        <p>14000Rs-17000Rs</p>
        </div>
        <ul class="rolebox">
            <li class="role-roundshape">Design</li>
            <li class="role-roundshape">Figma</li>
            <li class="role-roundshape">React</li>
        </ul>
    </div>
        <div class="right-content">
        <div class="right-topic"><p>Stripe</p></div>
        <h4>Staff Front End Engineer</h4>
        <div class="right-text">
        <p class="grey"><img src="../assets/map-pin.svg" alt="" class="img_logo"> San Franciso . Hybrid </p>&nbsp
        <p class="grey">Full time</p>&nbsp&nbsp
        <p>24000Rs-27000Rs</p>
        </div>
        <ul class="rolebox">
            <li class="role-roundshape">React</li>
            <li class="role-roundshape">TypeScript</li>
            <li class="role-roundshape">Payments</li>
        </ul>
    </div>

    </div>
    </div>
    <script src="../js/browsejob.js"></script>
</body>

</html>