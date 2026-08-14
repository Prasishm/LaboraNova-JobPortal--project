<?php
session_start();?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/demo.css">
</head>
<body>
    <div class="dashboard">
    <aside class="sidebar">

        <div class="logo">

            <span><img src="../assets/lavoranovaaa.png" alt=""></span>
        </div>

        <nav class="navigation">

            <a href="../pages/providerhome.php" class="nav-item active">

                <span>Home</span>
            </a>

            <a href="../pages/providerpostjob.php" class="nav-item">

                <span>Post Job</span>
            </a>

            <a href="../pages/providerapplication.php" class="nav-item">

                <span>Applications</span>
            </a>

        </nav>

        <div class="sidebar-bottom">

            <div class="provider-small">


                <div>
                    <strong><?php echo $_SESSION ['company_name']?></strong>
                    <small>Company</small>
                </div>

            </div>

            <div class="logout"><a href="../pages/logincompany.php">Log out</a>
                </div>

        </div>

    </aside>
</div>
</body>
</html>