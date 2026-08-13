<?php

// Create database first
createDB();

// Connect to the Laboranova database
$conn = mysqli_connect("localhost", "root", "", "Laboranova");

if (!$conn) {
    die("Connection error: " . mysqli_connect_error());
}

// Create tables in the correct order
Admin($conn);
subscription($conn);
training($conn);
social($conn);
skill($conn);

Jobprovider($conn);
Jobseeker($conn);
Job($conn);
Application($conn);

mysqli_close($conn);


// =====================================================
// CREATE DATABASE
// =====================================================

function createDB()
{
    $conn = mysqli_connect("localhost", "root", "");

    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    $sql = "CREATE DATABASE IF NOT EXISTS Laboranova";

    if (mysqli_query($conn, $sql)) {
        echo "<br>Database Created Successfully!!!";
    } else {
        echo "<br>Error Creating Database: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}


// =====================================================
// ADMIN TABLE
// =====================================================

function Admin($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS Admin (
        Admin_id INT AUTO_INCREMENT PRIMARY KEY,
        Admin_name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    )";

    $res = mysqli_query($conn, $sql);

    if ($res) {
        echo "<br>Admin Table Created Successfully!!!";
    } else {
        echo "<br>Error Creating Admin Table: " . mysqli_error($conn);
    }
}


// =====================================================
// SUBSCRIPTION TABLE
// =====================================================

function subscription($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS subscription (
        subscription_id INT AUTO_INCREMENT PRIMARY KEY,
        subscription_name VARCHAR(255) NOT NULL,
        subscription_amount DECIMAL(10,2) NOT NULL
    )";

    $res = mysqli_query($conn, $sql);

    if ($res) {
        echo "<br>Subscription Table Created Successfully!!!";
    } else {
        echo "<br>Error Creating Subscription Table: " . mysqli_error($conn);
    }
}


// =====================================================
// TRAINING TABLE
// =====================================================

function training($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS training (
        training_id INT AUTO_INCREMENT PRIMARY KEY,
        training_name VARCHAR(100) NOT NULL,
        certificate VARCHAR(100) NOT NULL
    )";

    $res = mysqli_query($conn, $sql);

    if ($res) {
        echo "<br>Training Table Created Successfully!!!";
    } else {
        echo "<br>Error Creating Training Table: " . mysqli_error($conn);
    }
}


// =====================================================
// SOCIAL TABLE
// =====================================================

function social($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS social (
        social_id INT AUTO_INCREMENT PRIMARY KEY,
        socialmedia_name VARCHAR(255) NOT NULL,
        platform VARCHAR(255) NOT NULL
    )";

    $res = mysqli_query($conn, $sql);

    if ($res) {
        echo "<br>Social Table Created Successfully!!!";
    } else {
        echo "<br>Error Creating Social Table: " . mysqli_error($conn);
    }
}


// =====================================================
// SKILL TABLE
// =====================================================

function skill($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS skill (
        skill_id INT AUTO_INCREMENT PRIMARY KEY,
        skill_name VARCHAR(255) NOT NULL
    )";

    $res = mysqli_query($conn, $sql);

    if ($res) {
        echo "<br>Skill Table Created Successfully!!!";
    } else {
        echo "<br>Error Creating Skill Table: " . mysqli_error($conn);
    }
}


// =====================================================
// JOB PROVIDER TABLE
// =====================================================

function Jobprovider($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS Jobprovider (
        jobprovider_id INT AUTO_INCREMENT PRIMARY KEY,
        company_name VARCHAR(255) NOT NULL,
        phone VARCHAR(20) NOT NULL UNIQUE,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        company_description VARCHAR(500) DEFAULT NULL,
        company_registration VARCHAR(255) DEFAULT NULL,
        subscription_id INT DEFAULT NULL,


        FOREIGN KEY (subscription_id)
            REFERENCES subscription(subscription_id)


    )";

    $res = mysqli_query($conn, $sql);

    if ($res) {
        echo "<br>Jobprovider Table Created Successfully!!!";
    } else {
        echo "<br>Error Creating Jobprovider Table: " . mysqli_error($conn);
    }
}


// =====================================================
// JOB SEEKER TABLE
// =====================================================

function Jobseeker($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS Jobseeker (
        jobseeker_id INT AUTO_INCREMENT PRIMARY KEY,
        Full_name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        phone VARCHAR(20) NOT NULL UNIQUE,
        address VARCHAR(255) NOT NULL,
        gender VARCHAR(255) NOT NULL,
        Language VARCHAR(255) DEFAULT NULL,
        Resume VARCHAR(255) DEFAULT NULL,
        Citizenship VARCHAR(255) DEFAULT NULL,
        profile_image VARCHAR(255) DEFAULT NULL,

        skill_id INT DEFAULT NULL,
        social_id INT DEFAULT NULL,
        training_id INT DEFAULT NULL,

        FOREIGN KEY (skill_id)
            REFERENCES skill(skill_id),

        FOREIGN KEY (social_id)
            REFERENCES social(social_id),

        FOREIGN KEY (training_id)
            REFERENCES training(training_id)


    )";

    $res = mysqli_query($conn, $sql);

    if ($res) {
        echo "<br>Jobseeker Table Created Successfully!!!";
    } else {
        echo "<br>Error Creating Jobseeker Table: " . mysqli_error($conn);
    }
}


// =====================================================
// JOB TABLE
// =====================================================

function Job($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS Job (
        job_id INT AUTO_INCREMENT PRIMARY KEY,
        job_title VARCHAR(255) NOT NULL,
        job_description TEXT NOT NULL,
        no_of_opening INT NOT NULL,
        jobprovider_id INT NOT NULL,
        skill_id INT NOT NULL,
        language VARCHAR(100) NOT NULL,
        job_location VARCHAR(255) NOT NULL,
        position VARCHAR(100) NOT NULL,
        salary DECIMAL(10,2) NOT NULL,
        job_type VARCHAR(50) NOT NULL,
        qualification VARCHAR(255) NOT NULL,
        office_time VARCHAR(100) NOT NULL,
        due_date DATE NOT NULL,
        experience VARCHAR(100) NOT NULL,
        

        FOREIGN KEY (jobprovider_id)
            REFERENCES Jobprovider(jobprovider_id),

        FOREIGN KEY (skill_id)
            REFERENCES skill(skill_id)


    )";

    $res = mysqli_query($conn, $sql);

    if ($res) {
        echo "<br>Job Table Created Successfully!!!";
    } else {
        echo "<br>Error Creating Job Table: " . mysqli_error($conn);
    }
}


// =====================================================
// APPLICATION TABLE
// =====================================================

function Application($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS Application (
        Application_id INT AUTO_INCREMENT PRIMARY KEY,
        company_name VARCHAR(255) NOT NULL,
        jobseeker_id INT NOT NULL,
        job_id INT NOT NULL,
        application_date DATE NOT NULL,

        FOREIGN KEY (jobseeker_id)
            REFERENCES Jobseeker(jobseeker_id),

        FOREIGN KEY (job_id)
            REFERENCES Job(job_id)
    )";

    $res = mysqli_query($conn, $sql);

    if ($res) {
        echo "<br>Application Table Created Successfully!!!";
    } else {
        echo "<br>Error Creating Application Table: " . mysqli_error($conn);
    }
}

?>