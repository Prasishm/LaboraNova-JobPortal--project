<?php

include "../database/conn.php";

$sql = "
    SELECT 
        j.jobseeker_id,
        j.Full_name,
        j.email,
        j.phone,
        j.address,
        j.gender,
        j.Language,
        j.Resume,
        j.Citizenship,
        j.profile_image,
        s.skill_name
    FROM jobseeker j
    LEFT JOIN skill s 
        ON j.skill_id = s.skill_id
    ORDER BY j.jobseeker_id DESC
";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}



    // jobseeker total count

$totalJobseekers = mysqli_num_rows($result);




?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta 
        name="viewport" 
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin Dashboard - LaboraNova</title>

    <link rel="stylesheet" href="../css/admin.css">

</head>

<body>

<div class="main">


<!-- sidebar -->

    <div class="left">

        <nav>


            <div class="top">

                <img
                    class="img-top"
                    src="../assets/lavoranovaaa.png"
                    alt="LABORANOVA"
                >

            </div>




            <div class="center">

                <a href="admin.php" class="active">
                    Jobseeker
                </a>

                <a href="../pages/adminprovider.php">
                    Job Provider
                </a>

            </div>



            <a href="../pages/landing.php" class="logout">
                Log out
            </a>

        </nav>

    </div>




    <div class="right">

        <div class="content-card">

            <h2 class="card-title-standard">
                All Jobseekers (<?php echo $totalJobseekers; ?>)
            </h2>


            <div class="table-responsive">

                <table class="simple-table">

                    <thead>

                        <tr>

                            <th>JOBSEEKER</th>

                            <th>SKILL</th>

                            <th>LOCATION</th>

                            <th>ACTIONS</th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php

                    if ($totalJobseekers > 0) {

                        while ($row = mysqli_fetch_assoc($result)) {

                            $name = $row['Full_name'];

                            $email = $row['email'];

                            $phone = $row['phone'];

                            $address = $row['address'];

                            $gender = $row['gender'];

                            $language = 
                                $row['Language'] ?? ''
                            ;

                            $resume = 
                                $row['Resume'] ?? ''
                            ;

                            $citizenship = 
                                $row['Citizenship'] ?? ''
                            ;

                            $skill = !empty($row['skill_name'])
                                ? ($row['skill_name'])
                                : "N/A";

                            

    

                            ?>

                            <tr>

                                <!-- JOBSEEKER -->

                                <td>

                                    <div class="table-user-cell">

                                        


                                        <strong>
                                            <?php echo $name; ?>
                                        </strong>

                                    </div>

                                </td>


                                <!-- SKILL -->

                                <td>
                                    <?php echo $skill; ?>
                                </td>


                                <!-- LOCATION -->

                                <td>
                                    <?php echo $address; ?>
                                </td>


                                <!-- ACTION -->

                                <td>

                                    <button
                                        class="btn-table-view"
                                        onclick="openDetailsModal(
                                            <?php echo (json_encode($row['Full_name'])); ?>,
                                            <?php echo (json_encode($row['email'])); ?>,
                                            <?php echo (json_encode($row['phone'])); ?>,
                                            <?php echo (json_encode($row['address'])); ?>,
                                            <?php echo (json_encode($row['gender'])); ?>,
                                            <?php echo (json_encode($row['Language'] ?? 'N/A')); ?>,
                                            <?php echo (json_encode($skill)); ?>,
                                            <?php echo (json_encode($row['Resume'] ?? '')); ?>,
                                            <?php echo (json_encode($row['Citizenship'] ?? '')); ?>
                                        )"
                                    >

                                        View

                                    </button>

                                </td>

                            </tr>

                            <?php

                        }

                    } else {

                    ?>

                        <tr>

                            <td colspan="4" style="text-align:center;">
                                No jobseekers found.
                            </td>

                        </tr>

                    <?php

                    }

                    ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>





</body>

</html>