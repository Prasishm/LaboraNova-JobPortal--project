<?php

session_start();

include "../database/conn.php";


/* =====================================
   CHECK JOB PROVIDER LOGIN
   ===================================== */

if (!isset($_SESSION['company_name'])) {

    header("Location: logincompany.php");
    exit();
}

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $sql = "UPDATE application 
            SET app_status = 'Approved' 
            WHERE Application_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("location: providerapplication.php");
    } else {
        echo "No application updated.";
    }

    $stmt->close();
}
$company_name = $_SESSION['company_name'];


/* =====================================
   GET APPLICATIONS
   ===================================== */

$sql = "
    SELECT
        a.Application_id,
        a.application_date,
        a.app_status,

        js.Full_name,
        js.email,
        js.phone,

        j.job_title,
        jp.company_name

    FROM application a

    INNER JOIN jobseeker js
        ON a.jobseeker_id = js.jobseeker_id

    INNER JOIN job j
        ON a.job_id = j.job_id

    INNER JOIN jobprovider jp
        ON j.jobprovider_id = jp.jobprovider_id

    WHERE jp.company_name = ?

    ORDER BY a.Application_id DESC
";


$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "s",
    $company_name
);

$stmt->execute();

$result = $stmt->get_result();


/* =====================================
   TOTAL APPLICATIONS
   ===================================== */

$total_applications = $result->num_rows;

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Applications</title>

    <link rel="stylesheet"
        href="../css/providerapplication.css">

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
                        <strong><?php echo $_SESSION['company_name'] ?></strong>
                        <small>Company</small>
                    </div>

                </div>

                <div class="logout"><a href="../pages/logincompany.php">Log out</a>
                </div>

            </div>

        </aside>



        <!-- =================================
         MAIN CONTENT
         ================================= -->

        <main class="main-content">


            <!-- APPLICATION CARD -->

            <div class="application-card">


                <!-- HEADER -->

                <div class="card-header">

                    <div>

                        <h1>

                            All Applications
                            (<?php echo $total_applications; ?>)

                        </h1>


                        <p>

                            View applications submitted
                            for your job postings.

                        </p>

                    </div>

                </div>



                <!-- TABLE -->

                <div class="table-container">


                    <table>


                        <thead>

                            <tr>

                                <th>
                                    APPLICANT
                                </th>

                                <th>
                                    EMAIL
                                </th>

                                <th>
                                    PHONE
                                </th>

                                <th>
                                    JOB TITLE
                                </th>

                                <th>
                                    APPLICATION DATE
                                </th>

                                <th>
                                    ACTIONS
                                </th>

                            </tr>

                        </thead>



                        <tbody>


                            <?php if ($result->num_rows > 0): ?>


                                <?php while ($row = $result->fetch_assoc()): 
                                        if($row['app_status']=="Pending"){

                                    ?>

                                    <tr>


                                        <!-- APPLICANT -->

                                        <td>

                                            <div class="applicant">


                                                <div class="applicant-avatar">

                                                    <?php

                                                    $name =
                                                        $row['Full_name'];

                                                    $initials =
                                                        strtoupper(
                                                            substr(
                                                                $name,
                                                                0,
                                                                2
                                                            )
                                                        );

                                                    echo htmlspecialchars(
                                                        $initials
                                                    );

                                                    ?>

                                                </div>


                                                <strong>

                                                    <?php

                                                    echo htmlspecialchars(
                                                        $row['Full_name']
                                                    );

                                                    ?>

                                                </strong>


                                            </div>

                                        </td>



                                        <!-- EMAIL -->

                                        <td>

                                            <?php

                                            echo htmlspecialchars(
                                                $row['email']
                                            );

                                            ?>

                                        </td>



                                        <!-- PHONE -->

                                        <td>

                                            <?php

                                            echo htmlspecialchars(
                                                $row['phone']
                                            );

                                            ?>

                                        </td>



                                        <!-- JOB TITLE -->

                                        <td class="job-title">

                                            <?php

                                            echo htmlspecialchars(
                                                $row['job_title']
                                            );

                                            ?>

                                        </td>



                                        <!-- APPLICATION DATE -->

                                        <td>

                                            <?php

                                            echo date(
                                                "M d, Y",
                                                strtotime(
                                                    $row['application_date']
                                                )
                                            );

                                            ?>

                                        </td>



                                        <!-- ACTION -->

                                        <td>

                                            <a
                                                href="providerapplication.php?id=<?php echo $row['Application_id']; ?>"
                                                class="view-btn">

                                                Approve

                                            </a>

                                        </td>


                                    </tr>


                                <?php } endwhile; ?>


                            <?php else: ?>


                                <tr>

                                    <td
                                        colspan="6"
                                        class="no-data">

                                        No applications found.

                                    </td>

                                </tr>


                            <?php endif; ?>


                        </tbody>


                    </table>


                </div>


            </div>


        </main>


    </div>


</body>

</html>