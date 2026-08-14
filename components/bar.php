<?php 
function bar($active){  ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
  <aside class="sidebar">

        <div class="logo">
            <div class="logo-icon">L</div>
            <span>LABORANOVA</span>
        </div>

        <nav class="navigation">

            <a href="../pages/providerhome.php" class="nav-item active">
                <span class="nav-icon">⌂</span>
                <span>Home</span>
            </a>

            <a href="../pages/providerpostjob.php" class="nav-item">
                <span class="nav-icon">＋</span>
                <span>Post Job</span>
            </a>

            <a href="candidates.html" class="nav-item">
                <span class="nav-icon">♙</span>
                <span>Candidates</span>
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

<?php }?>

</body>
</html>
?>