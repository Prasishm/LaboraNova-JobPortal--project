<?php
session_start();
// Database connection
include '../database/conn.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $job_title       = $_POST['job_title'];
    $job_description = $_POST['job_description'];
    $no_of_opening   = $_POST['no_of_opening'];
    $jobprovider_id  = $_SESSION['jobprovider_id'];
    $skill_id        = $_POST['skill_id'];
    $language        = $_POST['language'];
    $job_location    = $_POST['job_location'];
    $position        = $_POST['position'];
    $salary          = $_POST['salary'];
    $job_type        = $_POST['job_type'];
    $qualification   = $_POST['qualification'];
    $office_time     = $_POST['office_time'];
    $due_date        = $_POST['due_date'];
    $experience      = $_POST['experience'];
    
    $sql = "INSERT INTO job (
                job_title,
                job_description,
                no_of_opening,
                jobprovider_id,
                skill_id,
                language,
                job_location,
                position,
                salary,
                job_type,
                qualification,
                office_time,
                due_date,
                experience

            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssiiisssssssss",
        $job_title,
        $job_description,
        $no_of_opening,
        $jobprovider_id,
        $skill_id,
        $language,
        $job_location,
        $position,
        $salary,
        $job_type,
        $qualification,
        $office_time,
        $due_date,
        $experience
    );

    if ($stmt->execute()) {
        ?>
        <script>alert("Job posted successfully!")</script>
        <?php
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Post New Job</title>

    <link rel="stylesheet" href="../css/providerpostjob.css">

</head>


<body>

    <div class="dashboard">


        <!-- SIDEBAR -->

        <aside class="sidebar">

            <div class="logo">

                <div class="logo-icon">
                    L
                </div>

                <span>LABORANOVA</span>

            </div>


            <nav class="navigation">

                <a href="../pages/providerhome.php" class="nav-item">

                    <span class="nav-icon">
                        ⌂
                    </span>

                    <span>
                        Home
                    </span>

                </a>


                <a href="post-job.html"
                    class="nav-item active">

                    <span class="nav-icon">
                        ＋
                    </span>

                    <span>
                        Post Job
                    </span>

                </a>


                <a href="candidates.html"
                    class="nav-item">

                    <span class="nav-icon">
                        ♙
                    </span>

                    <span>
                        Candidates
                    </span>

                </a>

            </nav>


            <div class="sidebar-bottom">

                <div class="provider-small">

                    <div class="provider-avatar">
                        JP
                    </div>

                    <div>

                        <strong>
                            Job Provider
                        </strong>

                        <small>
                            Employer
                        </small>

                    </div>

                </div>


                <div class="logout">
                    ↪ &nbsp; Log out
                </div>

            </div>

        </aside>


        <!-- MAIN -->

        <main class="main-content">


            <div class="top-header">

                <div>

                    <h1>
                        Post a New Job
                    </h1>

                    <p>
                        Create a new job vacancy and find suitable candidates.
                    </p>

                </div>


                <div class="profile-mini">

                    <div class="profile-avatar">
                        JP
                    </div>

                    <div>

                        <strong>
                            Job Provider
                        </strong>

                        <span>
                            Employer
                        </span>

                    </div>

                </div>

            </div>


            <!-- FORM -->

            <div class="form-card">


                <div class="card-header">

                    <h2>
                        Job Information
                    </h2>

                    <p>
                        Fill in the details of your job vacancy.
                    </p>

                </div>


                <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">

                    <div class="form-grid">

                        <!-- JOB TITLE -->
                        <div class="form-group">
                            <label for="jobTitle">Job Title</label>
                            <input
                                type="text"
                                id="jobTitle"
                                name="job_title"
                                placeholder="e.g. Frontend Developer"
                                required>
                        </div>

                        <!-- NUMBER OF OPENINGS -->
                        <div class="form-group">
                            <label for="openings">Number of Openings</label>
                            <input
                                type="number"
                                id="openings"
                                name="no_of_opening"
                                placeholder="e.g. 2"
                                min="1"
                                required>
                        </div>

                        <!-- SKILL -->
                        <div class="form-group">
                            <label for="skill">Required Skill</label>
                            <input
                                type="text"
                                id="skill"
                                name="skill_id"
                                placeholder="e.g. HTML, CSS, JavaScript"
                                required>
                        </div>

                        <!-- LANGUAGE -->
                        <div class="form-group">
                            <label for="language">Language</label>
                            <input
                                type="text"
                                id="language"
                                name="language"
                                placeholder="e.g. English, Nepali"
                                required>
                        </div>

                        <!-- JOB LOCATION -->
                        <div class="form-group">
                            <label for="location">Job Location</label>
                            <input
                                type="text"
                                id="location"
                                name="job_location"
                                placeholder="e.g. Kathmandu, Nepal"
                                required>
                        </div>

                        <!-- POSITION -->
                        <div class="form-group">
                            <label for="position">Position</label>
                            <input
                                type="text"
                                id="position"
                                name="position"
                                placeholder="e.g. Junior Developer"
                                required>
                        </div>

                        <!-- SALARY -->
                        <div class="form-group">
                            <label for="salary">Salary</label>
                            <input
                                type="text"
                                id="salary"
                                name="salary"
                                placeholder="e.g. Rs. 30,000 - Rs. 50,000"
                                required>
                        </div>

                        <!-- JOB TYPE -->
                        <div class="form-group">
                            <label for="jobType">Job Type</label>
                            <select id="jobType" name="job_type" required>
                                <option value="">Select Job Type</option>
                                <option value="Full-time">Full-time</option>
                                <option value="Part-time">Part-time</option>
                                <option value="Contract">Contract</option>
                                <option value="Internship">Internship</option>
                            </select>
                        </div>

                        <!-- QUALIFICATION -->
                        <div class="form-group">
                            <label for="qualification">Qualification</label>
                            <input
                                type="text"
                                id="qualification"
                                name="qualification"
                                placeholder="e.g. Bachelor's in Computer Science"
                                required>
                        </div>

                        <!-- OFFICE TIME -->
                        <div class="form-group">
                            <label for="officeTime">Office Time</label>
                            <input
                                type="text"
                                id="officeTime"
                                name="office_time"
                                placeholder="e.g. 10:00 AM - 5:00 PM"
                                required>
                        </div>

                        <!-- DEADLINE -->
                        <div class="form-group">
                            <label for="dueDate">Application Deadline</label>
                            <input
                                type="date"
                                id="dueDate"
                                name="due_date"
                                required>
                        </div>

                        <!-- EXPERIENCE -->
                        <div class="form-group">
                            <label for="experience">Experience</label>
                            <input
                                type="text"
                                id="experience"
                                name="experience"
                                placeholder="e.g. 1-2 years"
                                required>
                        </div>

                        <!-- JOB DESCRIPTION -->
                        <div class="form-group full">
                            <label for="description">Job Description</label>
                            <textarea
                                id="description"
                                name="job_description"
                                placeholder="Describe the job responsibilities, requirements and other details..."
                                required></textarea>
                        </div>

                    </div>

                    <!-- HIDDEN SYSTEM FIELDS -->
                 

                    <div class="form-actions">

                        <button
                            type="reset"
                            class="cancel-btn">
                            Clear
                        </button>

                        <button
                            type="submit"
                            class="primary-btn">
                            Post Job
                        </button>

                    </div>

                </form>

            </div>

        </main>

    </div>

</body>

</html>