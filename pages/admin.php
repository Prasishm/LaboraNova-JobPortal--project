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


/*
    Count total jobseekers
*/
$totalJobseekers = mysqli_num_rows($result);


/*
    Function to create initials
*/
function getInitials($name)
{
    $words = explode(" ", trim($name));

    if (count($words) >= 2) {
        return strtoupper(
            substr($words[0], 0, 1) .
            substr($words[1], 0, 1)
        );
    }

    return strtoupper(substr($name, 0, 2));
}

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


    <!-- ================= LEFT SIDEBAR ================= -->

    <div class="left">

        <nav>

            <!-- Logo -->

            <div class="top">

                <img
                    class="img-top"
                    src="../assets/lavoranovaaa.png"
                    alt="LABORANOVA"
                >

            </div>


            <!-- Navigation -->

            <div class="center">

                <a href="admin.php" class="active">
                    Jobseeker
                </a>

                <a href="../pages/adminprovider.php">
                    Job Provider
                </a>

            </div>


            <!-- Logout -->

            <a href="../pages/landing.php" class="logout">
                Log out
            </a>

        </nav>

    </div>



    <!-- ================= RIGHT CONTENT ================= -->

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

                            $name = htmlspecialchars($row['Full_name']);

                            $email = htmlspecialchars($row['email']);

                            $phone = htmlspecialchars($row['phone']);

                            $address = htmlspecialchars($row['address']);

                            $gender = htmlspecialchars($row['gender']);

                            $language = htmlspecialchars(
                                $row['Language'] ?? ''
                            );

                            $resume = htmlspecialchars(
                                $row['Resume'] ?? ''
                            );

                            $citizenship = htmlspecialchars(
                                $row['Citizenship'] ?? ''
                            );

                            $skill = !empty($row['skill_name'])
                                ? htmlspecialchars($row['skill_name'])
                                : "N/A";

                            $initials = getInitials($row['Full_name']);

                            /*
                                Profile image
                            */
                            $profileImage = $row['profile_image'];

                            ?>

                            <tr>

                                <!-- JOBSEEKER -->

                                <td>

                                    <div class="table-user-cell">

                                        <?php if (!empty($profileImage)) { ?>

                                            <img
                                                class="user-avatar-circle"
                                                src="../uploads/<?php echo htmlspecialchars($profileImage); ?>"
                                                alt="Profile"
                                            >

                                        <?php } else { ?>

                                            <div class="user-avatar-circle">
                                                <?php echo $initials; ?>
                                            </div>

                                        <?php } ?>


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
                                            <?php echo htmlspecialchars(json_encode($row['Full_name'])); ?>,
                                            <?php echo htmlspecialchars(json_encode($row['email'])); ?>,
                                            <?php echo htmlspecialchars(json_encode($row['phone'])); ?>,
                                            <?php echo htmlspecialchars(json_encode($row['address'])); ?>,
                                            <?php echo htmlspecialchars(json_encode($row['gender'])); ?>,
                                            <?php echo htmlspecialchars(json_encode($row['Language'] ?? 'N/A')); ?>,
                                            <?php echo htmlspecialchars(json_encode($skill)); ?>,
                                            <?php echo htmlspecialchars(json_encode($row['Resume'] ?? '')); ?>,
                                            <?php echo htmlspecialchars(json_encode($row['Citizenship'] ?? '')); ?>
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