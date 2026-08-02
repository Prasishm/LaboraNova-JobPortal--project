<?php

createDB();

include 'conn.php';

if (!$conn) {
    die("Connection error: " . mysqli_connect_error());
} else {
    subscription($conn);
    training($conn);
    social($conn);
    skill($conn);

    Admin($conn);

    Jobprovider($conn);

    Jobseeker($conn);

    Job($conn);

    Application($conn);
}

function createDB()
{
    $conn = mysqli_connect("localhost", "root", "");

    $sql = "CREATE DATABASE IF NOT EXISTS Laboranova";

    $res = mysqli_query($conn, $sql);

    if ($res) {
        echo "<br> Database Created Successfully!!!";
    }
}

function Jobseeker($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS Jobseeker (

        jobseeker_id INT PRIMARY KEY AUTO_INCREMENT,
        Full_name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        phone BIGINT UNIQUE NOT NULL,
        address VARCHAR(255) NOT NULL,
        Language VARCHAR(255) default NULL,
        Resume VARCHAR(255) default NULL,
        Citizenship VARCHAR(255) default NULL,
        profile_image VARCHAR(255) default NULL,
        skill_id INT default NULL,
        social_id INT default NULL,
        training_id INT default NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

        FOREIGN KEY (skill_id) REFERENCES skill(skill_id),
        FOREIGN KEY (social_id) REFERENCES social(social_id),
        FOREIGN KEY (training_id) REFERENCES training(training_id)

    );";

    $res = mysqli_query($conn, $sql);

    if ($res) {
        echo "<br> Jobseeker Table Created Successfully!!!";
    } else {
        echo "<br>Error Creating Jobseeker Table: " . mysqli_error($conn);
    }
}

function Admin($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS Admin (

        Admin_id INT AUTO_INCREMENT PRIMARY KEY,
        Admin_name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL

    )";

    $res = mysqli_query($conn, $sql);
}

function Jobprovider($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS Jobprovider (

        jobprovider_id INT AUTO_INCREMENT PRIMARY KEY,
        company_name VARCHAR(255) NOT NULL,
        phone BIGINT UNIQUE NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        company_description VARCHAR(500) NOT NULL,
        company_registration VARCHAR(255) NOT NULL,
        subscription_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

        FOREIGN KEY (subscription_id) REFERENCES subscription(subscription_id)

    )";

    $res = mysqli_query($conn, $sql);

    if ($res) {
        echo "<br>Jobprovider Table Created Successfully!!!";
    } else {
        echo "<br>Error Creating Jobprovider Table: " . mysqli_error($conn);
    }
}

function subscription($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS subscription (

        subscription_id INT AUTO_INCREMENT PRIMARY KEY,
        subscription_name VARCHAR(255) NOT NULL,
        subscription_amount BIGINT NOT NULL,

        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

    )";

    $res = mysqli_query($conn, $sql);

    if ($res) {
        echo "<br>subscription Table Created Successfully!!!";
    } else {
        echo "<br>Error Creating subscription Table: " . mysqli_error($conn);
    }
}

function training($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS training (

        training_id INT AUTO_INCREMENT PRIMARY KEY,
        training_name VARCHAR(100) NOT NULL,
        certificate VARCHAR(100) NOT NULL

    )";

    $res = mysqli_query($conn, $sql);

    if ($res) {
        echo "<br>training Table Created Successfully!!!";
    } else {
        echo "<br>Error Creating training Table: " . mysqli_error($conn);
    }
}

function social($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS social (

        social_id INT AUTO_INCREMENT PRIMARY KEY,
        socialmedia_name VARCHAR(255) NOT NULL,
        platform VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

    )";

    $res = mysqli_query($conn, $sql);

    if ($res) {
        echo "<br>social Table Created Successfully!!!";
    } else {
        echo "<br>Error Creating social Table: " . mysqli_error($conn);
    }
}

function Application($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS Application (

        Application_id INT AUTO_INCREMENT PRIMARY KEY,
        company_name VARCHAR(255) NOT NULL,
        jobseeker_id INT NOT NULL,
        job_id INT NOT NULL,
        application_date DATE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

        FOREIGN KEY (jobseeker_id) REFERENCES Jobseeker(jobseeker_id),
        FOREIGN KEY (job_id) REFERENCES Job(job_id)

    )";

    $res = mysqli_query($conn, $sql);

    if ($res) {
        echo "<br>Driver Documents Table Created Successfully!!!";
    } else {
        echo "<br>Error Creating Driver Documents Table: " . mysqli_error($conn);
    }
}

function skill($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS skill (

        skill_id INT AUTO_INCREMENT PRIMARY KEY,
        skill_name VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

    )";

    $res = mysqli_query($conn, $sql);

    if ($res) {
        echo "<br>Skills Table Created Successfully!!!";
    } else {
        echo "<br>Error Creating skill Table: " . mysqli_error($conn);
    }
}

function Job($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS Job (

        job_id INT PRIMARY KEY AUTO_INCREMENT,
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

        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

        FOREIGN KEY (jobprovider_id) REFERENCES JobProvider(jobprovider_id),
        FOREIGN KEY (skill_id) REFERENCES skill(skill_id)

    );";

    $res = mysqli_query($conn, $sql);

    if ($res) {
        echo "<br>Job Table Created Successfully!!!";
    } else {
        echo "<br>Error Creating Job Table: " . mysqli_error($conn);
    }
}

?>