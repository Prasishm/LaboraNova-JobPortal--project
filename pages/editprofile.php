<?php
session_start();
include "../database/conn.php";

/* =========================
   CHECK LOGIN
========================= */

if (!isset($_SESSION['jobseeker_id'])) {
    header("Location: loginseeker.php");
    exit();
}

$jobseeker_id = $_SESSION['jobseeker_id'];


/* =========================
   UPLOAD FOLDERS
========================= */

$resumeFolder = "../uploads/resumes/";
$citizenshipFolder = "../uploads/citizenship/";
$certificateFolder = "../uploads/certificates/";
$profileFolder = "../uploads/profile/";


/* Create folders if they don't exist */

if (!is_dir($resumeFolder)) {
    mkdir($resumeFolder, 0777, true);
}

if (!is_dir($citizenshipFolder)) {
    mkdir($citizenshipFolder, 0777, true);
}

if (!is_dir($certificateFolder)) {
    mkdir($certificateFolder, 0777, true);
}

if (!is_dir($profileFolder)) {
    mkdir($profileFolder, 0777, true);
}


/* =========================
   FETCH JOBSEEKER
========================= */

$sql = "SELECT * FROM jobseeker WHERE jobseeker_id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $jobseeker_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$user = mysqli_fetch_assoc($result);


if (!$user) {
    echo "User not found.";
    exit();
}


/* =========================
   FETCH SKILL
========================= */

$skill = [
    "skill_name" => ""
];

if (!empty($user["skill_id"])) {

    $sql = "SELECT * FROM skill WHERE skill_id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $user["skill_id"]
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $skill = $row;
    }
}


/* =========================
   FETCH SOCIAL
========================= */

$social = [
    "socialmedia_name" => "",
    "platform" => ""
];

if (!empty($user["social_id"])) {

    $sql = "SELECT * FROM social WHERE social_id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $user["social_id"]
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $social = $row;
    }
}


/* =========================
   FETCH TRAINING
========================= */

$training = [
    "training_name" => "",
    "certificate" => ""
];

if (!empty($user["training_id"])) {

    $sql = "SELECT * FROM training WHERE training_id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $user["training_id"]
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $training = $row;
    }
}


/* =========================
   UPDATE PROFILE
========================= */

if (isset($_POST["update_profile"])) {

    $fullname = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $gender = $_POST["gender"];
    $language = trim($_POST["language"]);
    $experience = trim($_POST['experience']);
    $education = trim($_POST['education']);
    $skill_name = trim($_POST["skill_name"]);

    $social_name = trim($_POST["socialmedia_name"]);
    $platform = trim($_POST["platform"]);

    $training_name = trim($_POST["training_name"]);


    /* =========================
       CHECK EMAIL
    ========================= */

    $sql = "
        SELECT jobseeker_id
        FROM jobseeker
        WHERE email = ?
        AND jobseeker_id != ?
    ";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "si",
        $email,
        $jobseeker_id
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {

        echo "<script>
            alert('This email is already used.');
            window.history.back();
        </script>";

        exit();
    }


    /* =========================
       CHECK PHONE
    ========================= */

    $sql = "
        SELECT jobseeker_id
        FROM jobseeker
        WHERE phone = ?
        AND jobseeker_id != ?
    ";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "si",
        $phone,
        $jobseeker_id
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {

        echo "<script>
            alert('This phone number is already used.');
            window.history.back();
        </script>";

        exit();
    }


    /* =========================
       FILE VARIABLES
    ========================= */

$resumePath = $user["Resume"];
$citizenshipPath = $user["Citizenship"];
$profileImagePath = $user["profile_image"];


    /* =========================
       RESUME UPLOAD
    ========================= */

    if (
        isset($_FILES["resume"]) &&
        $_FILES["resume"]["error"] == 0
    ) {

        $resumeName = $_FILES["resume"]["name"];
        $resumeTmp = $_FILES["resume"]["tmp_name"];

        $resumeExt = strtolower(
            pathinfo($resumeName, PATHINFO_EXTENSION)
        );

        $allowedResume = ["pdf", "doc", "docx"];

        if (!in_array($resumeExt, $allowedResume)) {

            echo "<script>
                alert('Only PDF, DOC and DOCX files are allowed for Resume.');
                window.history.back();
            </script>";

            exit();
        }

        $newResumeName =
            "resume_" .
            $jobseeker_id .
            "_" .
            time() .
            "." .
            $resumeExt;

        $resumePath =
            $resumeFolder .
            $newResumeName;

        move_uploaded_file(
            $resumeTmp,
            $resumePath
        );
    }


    /* =========================
       CITIZENSHIP UPLOAD
    ========================= */

    if (
        isset($_FILES["citizenship"]) &&
        $_FILES["citizenship"]["error"] == 0
    ) {

        $citizenshipName =
            $_FILES["citizenship"]["name"];

        $citizenshipTmp =
            $_FILES["citizenship"]["tmp_name"];

        $citizenshipExt =
            strtolower(
                pathinfo(
                    $citizenshipName,
                    PATHINFO_EXTENSION
                )
            );

        $allowedCitizenship = [
            "jpg",
            "jpeg",
            "png",
            "pdf"
        ];

        if (
            !in_array(
                $citizenshipExt,
                $allowedCitizenship
            )
        ) {

            echo "<script>
                alert('Citizenship must be JPG, PNG or PDF.');
                window.history.back();
            </script>";

            exit();
        }

        $newCitizenshipName =
            "citizenship_" .
            $jobseeker_id .
            "_" .
            time() .
            "." .
            $citizenshipExt;

        $citizenshipPath =
            $citizenshipFolder .
            $newCitizenshipName;

        move_uploaded_file(
            $citizenshipTmp,
            $citizenshipPath
        );
    }

/* =========================
   PROFILE IMAGE UPLOAD
========================= */

if (
    isset($_FILES["profile_image"]) &&
    $_FILES["profile_image"]["error"] == 0
) {

    $profileImageName = $_FILES["profile_image"]["name"];
    $profileImageTmp = $_FILES["profile_image"]["tmp_name"];

    $profileImageExt = strtolower(
        pathinfo($profileImageName, PATHINFO_EXTENSION)
    );

    $allowedProfileImages = [
        "jpg",
        "jpeg",
        "png",
        "webp"
    ];

    if (!in_array($profileImageExt, $allowedProfileImages)) {

        echo "<script>
            alert('Profile image must be JPG, JPEG, PNG or WEBP.');
            window.history.back();
        </script>";

        exit();
    }

    $newProfileImageName =
        "profile_" .
        $jobseeker_id .
        "_" .
        time() .
        "." .
        $profileImageExt;

    $profileImagePath =
        $profileFolder .
        $newProfileImageName;

    move_uploaded_file(
        $profileImageTmp,
        $profileImagePath
    );
}
    /* =========================
       CERTIFICATE UPLOAD
    ========================= */

    $certificatePath =
        $training["certificate"];


    if (
        isset($_FILES["certificate"]) &&
        $_FILES["certificate"]["error"] == 0
    ) {

        $certificateName =
            $_FILES["certificate"]["name"];

        $certificateTmp =
            $_FILES["certificate"]["tmp_name"];

        $certificateExt =
            strtolower(
                pathinfo(
                    $certificateName,
                    PATHINFO_EXTENSION
                )
            );

        $allowedCertificate = [
            "jpg",
            "jpeg",
            "png",
            "webp",
            "pdf"
        ];

        if (
            !in_array(
                $certificateExt,
                $allowedCertificate
            )
        ) {

            echo "<script>
                alert('Certificate must be JPG, JPEG, PNG, WEBP or PDF.');
                window.history.back();
            </script>";

            exit();
        }

        $newCertificateName =
            "certificate_" .
            $jobseeker_id .
            "_" .
            time() .
            "." .
            $certificateExt;

        $certificatePath =
            $certificateFolder .
            $newCertificateName;

        move_uploaded_file(
            $certificateTmp,
            $certificatePath
        );
    }


    /* =========================
       START TRANSACTION
    ========================= */

    mysqli_begin_transaction($conn);

    try {


        /* =========================
           SKILL
        ========================= */

        if (!empty($user["skill_id"])) {

            $sql = "
                UPDATE skill
                SET skill_name = ?
                WHERE skill_id = ?
            ";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "si",
                $skill_name,
                $user["skill_id"]
            );

            mysqli_stmt_execute($stmt);

        } elseif (!empty($skill_name)) {

            $sql = "
                INSERT INTO skill (skill_name)
                VALUES (?)
            ";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "s",
                $skill_name
            );

            mysqli_stmt_execute($stmt);

            $skill_id = mysqli_insert_id($conn);


            $sql = "
                UPDATE jobseeker
                SET skill_id = ?
                WHERE jobseeker_id = ?
            ";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "ii",
                $skill_id,
                $jobseeker_id
            );

            mysqli_stmt_execute($stmt);
        }


        /* =========================
           SOCIAL
        ========================= */

        if (!empty($user["social_id"])) {

            $sql = "
                UPDATE social
                SET
                    socialmedia_name = ?,
                    platform = ?
                WHERE social_id = ?
            ";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "ssi",
                $social_name,
                $platform,
                $user["social_id"]
            );

            mysqli_stmt_execute($stmt);

        } elseif (
            !empty($social_name) ||
            !empty($platform)
        ) {

            $sql = "
                INSERT INTO social
                (
                    socialmedia_name,
                    platform
                )
                VALUES (?, ?)
            ";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "ss",
                $social_name,
                $platform
            );

            mysqli_stmt_execute($stmt);

            $social_id = mysqli_insert_id($conn);


            $sql = "
                UPDATE jobseeker
                SET social_id = ?
                WHERE jobseeker_id = ?
            ";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "ii",
                $social_id,
                $jobseeker_id
            );

            mysqli_stmt_execute($stmt);
        }


        /* =========================
           TRAINING
        ========================= */

        if (!empty($user["training_id"])) {

            $sql = "
                UPDATE training
                SET
                    training_name = ?,
                    certificate = ?
                WHERE training_id = ?
            ";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "ssi",
                $training_name,
                $certificatePath,
                $user["training_id"]
            );

            mysqli_stmt_execute($stmt);

        } elseif (
            !empty($training_name) ||
            !empty($certificatePath)
        ) {

            $sql = "
                INSERT INTO training
                (
                    training_name,
                    certificate
                )
                VALUES (?, ?)
            ";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "ss",
                $training_name,
                $certificatePath
            );

            mysqli_stmt_execute($stmt);

            $training_id = mysqli_insert_id($conn);


            $sql = "
                UPDATE jobseeker
                SET training_id = ?
                WHERE jobseeker_id = ?
            ";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                "ii",
                $training_id,
                $jobseeker_id
            );

            mysqli_stmt_execute($stmt);
        }


        /* =========================
   UPDATE JOBSEEKER
========================= */

$sql = "
    UPDATE jobseeker
    SET
        Full_name = ?,
        email = ?,
        phone = ?,
        address = ?,
        gender = ?,
        Language = ?,
        experience = ?,
        education = ?,
        Resume = ?,
        Citizenship = ?,
        profile_image = ?
    WHERE jobseeker_id = ?
";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "sssssssssssi",
    $fullname,
    $email,
    $phone,
    $address,
    $gender,
    $language,
    $experience,
    $education,
    $resumePath,
    $citizenshipPath,
    $profileImagePath,
    $jobseeker_id
);

mysqli_stmt_execute($stmt);


        /* =========================
           COMMIT
        ========================= */

        mysqli_commit($conn);


        $_SESSION["jobseeker_name"] = $fullname;
        $_SESSION["Full_name"] = $fullname;


        echo "<script>
            alert('Profile updated successfully!');
            window.location.href = 'editprofile.php';
        </script>";

        exit();


    } catch (Exception $e) {

        mysqli_rollback($conn);

        echo "<script>
            alert('Failed to update profile.');
            window.history.back();
        </script>";

        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Edit Profile</title>

    <link rel="stylesheet"
          href="../css/edit.css">

</head>


<body>

<div class="dashboard">


    <!-- =========================
         SIDEBAR
    ========================== -->

    <aside class="sidebar">

        <div>

            <div class="logo">

                <img src="../assets/lavoranovaaa.png"
                     alt="LaboraNova">

            </div>


            <nav class="navigation">

                <a href="../pages/seekerdashboardhome.php"
                   class="nav-item">

                    <span>Home</span>

                </a>


                <a href="../pages/seekerdashboardbrowsejob.php"
                   class="nav-item">

                    <span>Browse Job</span>

                </a>


                <a href="../pages/seekernotificaion.php"
                   class="nav-item">

                    <span>Notification</span>

                </a>

            </nav>

        </div>


        <div class="sidebar-bottom">

            <div class="provider-small">

                <div>

                    <strong>
                        <?php
                        echo htmlspecialchars(
                            $_SESSION['Full_name']
                            ?? $user['Full_name']
                        );
                        ?>
                    </strong>

                    <small>Job Seeker</small>

                </div>

            </div>


            <div class="logout">

                <a href="../pages/loginseeker.php">
                    Log out
                </a>

            </div>

        </div>

    </aside>



    <!-- =========================
         MAIN CONTENT
    ========================== -->

    <main class="main-content">


        <div class="top-header">

            <h1>Edit Profile</h1>

            <p>
                Update your personal information and profile details.
            </p>

        </div>



        <div class="form-card">


            <div class="card-header">

                <h2>Personal Information</h2>

                <p>
                    Update your information below.
                </p>

            </div>



            <form method="POST" enctype="multipart/form-data">


                <div class="form-grid">


                    <!-- =========================
                         JOBSEEKER INFORMATION
                    ========================== -->

                    <div class="section-title">
                        Personal Information
                    </div>

                    <!-- =========================
     PROFILE IMAGE
========================== -->



<div class="form-group full">

    <label for="profile_image">
        Profile Image
    </label>

    <?php if (!empty($user['profile_image'])): ?>

        <div class="profile-preview">
            <img
                src="<?php echo htmlspecialchars($user['profile_image']); ?>"
                alt="Profile Image"
            >
        </div>

    <?php endif; ?>

    <input
        type="file"
        id="profile_image"
        name="profile_image"
        accept=".jpg,.jpeg,.png,.webp"
    >

    <?php if (!empty($user['profile_image'])): ?>

        <small class="current-file">
            Current profile image is shown above.
        </small>

    <?php endif; ?>

</div>
                    <div class="form-group">

                        <label>Full Name</label>

                        <input
                            type="text"
                            name="fullname"
                            value="<?php
                            echo htmlspecialchars(
                                $user['Full_name']
                            );
                            ?>"
                            required>

                    </div>


                    <div class="form-group">

                        <label>Email</label>

                        <input
                            type="email"
                            name="email"
                            value="<?php
                            echo htmlspecialchars(
                                $user['email']
                            );
                            ?>"
                            required>

                    </div>


                    <div class="form-group">

                        <label>Phone Number</label>

                        <input
                            type="text"
                            name="phone"
                            value="<?php
                            echo htmlspecialchars(
                                $user['phone']
                            );
                            ?>"
                            required>

                    </div>


                    <div class="form-group">

                        <label>Address</label>

                        <input
                            type="text"
                            name="address"
                            value="<?php
                            echo htmlspecialchars(
                                $user['address']
                            );
                            ?>"
                            required>

                    </div>


                    <div class="form-group">

                        <label>Gender</label>

                        <select name="gender" required>

                            <option value="">
                                Select Gender
                            </option>

                            <option value="Male"
                                <?php
                                if ($user['gender'] == 'Male')
                                    echo 'selected';
                                ?>>
                                Male
                            </option>

                            <option value="Female"
                                <?php
                                if ($user['gender'] == 'Female')
                                    echo 'selected';
                                ?>>
                                Female
                            </option>

                            <option value="Other"
                                <?php
                                if ($user['gender'] == 'Other')
                                    echo 'selected';
                                ?>>
                                Other
                            </option>

                        </select>

                    </div>


                    <div class="form-group">

                        <label>Language</label>

                        <input
                            type="text"
                            name="language"
                            placeholder="e.g. Nepali, English"
                            value="<?php
                            echo htmlspecialchars(
                                $user['Language'] ?? ''
                            );
                            ?>">

                    </div>



                    <!-- =========================
                         SKILL
                    ========================== -->

                    <div class="section-title">
                        Skills
                    </div>


                    <div class="form-group full">

                        <label>Skill</label>

                        <input
                            type="text"
                            name="skill_name"
                            placeholder="e.g. PHP, JavaScript, MySQL"
                            value="<?php
                            echo htmlspecialchars(
                                $skill['skill_name']
                            );
                            ?>">

                    </div>

<div class="form-group">
    <label for="education">Education</label>
    <input
        type="text"
        id="education"
        name="education"
        value="<?php echo htmlspecialchars($user['education'] ?? ''); ?>"
        placeholder="e.g. BCA, Bachelor in Computer Application"
    >
</div>

<div class="form-group">
    <label for="experience">Experience</label>
    <input
        type="text"
        id="experience"
        name="experience"
        value="<?php echo htmlspecialchars($user['experience'] ?? ''); ?>"
        placeholder="e.g. 2 years"
    >
</div>

                    <!-- =========================
                         SOCIAL
                    ========================== -->

                    <div class="section-title">
                        Social Media
                    </div>


                    <div class="form-group">

                        <label>Social Media Name</label>

                        <input
                            type="text"
                            name="socialmedia_name"
                            placeholder="e.g. your username"
                            value="<?php
                            echo htmlspecialchars(
                                $social['socialmedia_name']
                            );
                            ?>">

                    </div>


                    <div class="form-group">

                        <label>Platform</label>

                        <select name="platform">

                            <option value="">
                                Select Platform
                            </option>

                            <option value="Facebook"
                                <?php
                                if ($social['platform'] == 'Facebook')
                                    echo 'selected';
                                ?>>
                                Facebook
                            </option>

                            <option value="Instagram"
                                <?php
                                if ($social['platform'] == 'Instagram')
                                    echo 'selected';
                                ?>>
                                Instagram
                            </option>

                            <option value="LinkedIn"
                                <?php
                                if ($social['platform'] == 'LinkedIn')
                                    echo 'selected';
                                ?>>
                                LinkedIn
                            </option>

                            <option value="GitHub"
                                <?php
                                if ($social['platform'] == 'GitHub')
                                    echo 'selected';
                                ?>>
                                GitHub
                            </option>

                            <option value="Other"
                                <?php
                                if ($social['platform'] == 'Other')
                                    echo 'selected';
                                ?>>
                                Other
                            </option>

                        </select>

                    </div>



                    <!-- TRAINING -->

<div class="section-title">
    Training & Certificate
</div>


<div class="form-group">

    <label for="training_name">
        Training Name
    </label>

    <input
        type="text"
        id="training_name"
        name="training_name"
        placeholder="e.g. Web Development"
        value="<?php
        echo htmlspecialchars(
            $training['training_name']
        );
        ?>">

</div>


<div class="form-group">

    <label for="certificate">
        Certificate Photo
    </label>

    <input
        type="file"
        id="certificate"
        name="certificate"
        accept=".jpg,.jpeg,.png,.webp,.pdf">


    <?php if (!empty($training['certificate'])): ?>

        <small class="current-file">

            Current certificate:

            <a
                href="<?php
                echo htmlspecialchars(
                    $training['certificate']
                );
                ?>"
                target="_blank">

                View Certificate

            </a>

        </small>

    <?php endif; ?>

</div>



  
                <!-- RESUME -->

<div class="form-group">

    <label for="resume">
        Resume
    </label>

    <input
        type="file"
        id="resume"
        name="resume"
        accept=".pdf,.doc,.docx">

    <?php if (!empty($user['Resume'])): ?>

        <small class="current-file">
            Current resume:
            <a href="<?php echo htmlspecialchars($user['Resume']); ?>"
               target="_blank">
                View Resume
            </a>
        </small>

    <?php endif; ?>

</div>


<!-- CITIZENSHIP -->

<div class="form-group">

    <label for="citizenship">
        Citizenship
    </label>

    <input
        type="file"
        id="citizenship"
        name="citizenship"
        accept=".jpg,.jpeg,.png,.pdf">

    <?php if (!empty($user['Citizenship'])): ?>

        <small class="current-file">
            Current citizenship:
            <a href="<?php echo htmlspecialchars($user['Citizenship']); ?>"
               target="_blank">
                View Citizenship
            </a>
        </small>

    <?php endif; ?>

</div>
 <!-- =========================
                         BUTTON
                    ========================== -->

                    <div class="form-actions">

                        <button
                            type="submit"
                            name="update_profile">

                            Update Profile

                        </button>

                    </div>
                </div>

            </form>

        </div>

    </main>

</div>

</body>

</html>