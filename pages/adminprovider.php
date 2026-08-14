<?php

include "../database/conn.php";


/*
    Get all job providers from database
*/

$sql = "
    SELECT
        jobprovider_id,
        company_name,
        email,
        phone,
        company_description,
        company_registration
    FROM jobprovider
    ORDER BY jobprovider_id DESC
";


$result = mysqli_query($conn, $sql);


if (!$result) {

    die("Database query failed: " . mysqli_error($conn));

}


/*
    Count providers
*/

$totalProviders = mysqli_num_rows($result);


/*
    Create company initials
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

    <title>Job Providers - LaboraNova</title>

    <link
        rel="stylesheet"
        href="../css/adminprovider.css"
    >

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

                <a href="admin.php">

                    Jobseeker

                </a>


                <a
                    href="adminprovider.php"
                    class="active"
                >

                    Job Provider

                </a>

            </div>


            <!-- Logout -->

            <a
                href="../pages/landing.php"
                class="logout"
            >

                Log out

            </a>

        </nav>

    </div>



    <!-- ================= RIGHT CONTENT ================= -->

    <div class="right">

        <div class="content-card">


            <h2 class="card-title-standard">

                All Job Providers
                (<?php echo $totalProviders; ?>)

            </h2>



            <div class="table-responsive">


                <table class="simple-table">


                    <thead>

                        <tr>

                            <th>COMPANY</th>

                            <th>EMAIL</th>

                            <th>PHONE</th>

                            <th>DESCRIPTION</th>

                            <th>ACTIONS</th>

                        </tr>

                    </thead>



                    <tbody>


                    <?php


                    if ($totalProviders > 0) {


                        while ($row = mysqli_fetch_assoc($result)) {


                            /*
                                Correct primary key
                            */

                            $providerId =
                                (int)$row['jobprovider_id'];


                            $companyName =
                                htmlspecialchars(
                                    $row['company_name']
                                );


                            $email =
                                htmlspecialchars(
                                    $row['email']
                                );


                            $phone =
                                htmlspecialchars(
                                    $row['phone']
                                );


                            $description =
                                !empty($row['company_description'])
                                ? htmlspecialchars(
                                    $row['company_description']
                                )
                                : "N/A";


                            $initials =
                                getInitials(
                                    $row['company_name']
                                );


                    ?>


                        <tr>


                            <!-- COMPANY -->

                            <td>

                                <div class="table-user-cell">


                                    <div class="user-avatar-circle">

                                        <?php
                                        echo $initials;
                                        ?>

                                    </div>


                                    <strong>

                                        <?php
                                        echo $companyName;
                                        ?>

                                    </strong>


                                </div>

                            </td>



                            <!-- EMAIL -->

                            <td>

                                <?php
                                echo $email;
                                ?>

                            </td>



                            <!-- PHONE -->

                            <td>

                                <?php
                                echo $phone;
                                ?>

                            </td>



                            <!-- DESCRIPTION -->

                            <td>

                                <?php
                                echo $description;
                                ?>

                            </td>



                            <!-- ACTION -->

                            <td>
    <button type="button" class="btn-table-view">
        View
    </button>
</td>


                        </tr>


                    <?php


                        }


                    } else {


                    ?>


                        <tr>

                            <td
                                colspan="5"
                                style="text-align:center;"
                            >

                                No job providers found.

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