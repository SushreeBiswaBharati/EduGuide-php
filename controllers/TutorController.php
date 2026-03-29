<?php
require_once("../database/dbconnection.php");

// Fetch dropdown from database
$boards_result = $conn->query("SELECT id, name FROM boards WHERE is_active = 1 ORDER BY name ASC");
$subjects_result = $conn->query("SELECT id, name FROM subjects WHERE is_active = 1 ORDER BY name ASC");

// Initialize all error and success variables
$nameError = "";
$genderError = "";
$emailError = "";
$passwordError = "";
$cpasswordError ="";
$qualificationError = "";
$experienceError = "";
$subjectError = "";
$boardError = "";
$phoneError = "";
$addressError = "";
$availabilityError = "";
$formSuccess = "";

// Run when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = trim($_POST['name']);
    $gender = $_POST['gender'];
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $cpassword = $_POST['confirm_password'];
    $qualification = trim($_POST['qualification']);
    $experience = trim($_POST['experience']);
    $subject_id = $_POST['subject_id'];
    $board_id = $_POST['board_id'];
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $availability = $_POST['availability'];

    // Name validation
    if ($name === "") {
        $nameError = "Name is required";
    } elseif (strlen($name) < 2) {
        $nameError = "Name must be at least 2 characters";
    } elseif (!preg_match('/^[A-Za-z\s]+$/', $name)) {
        $nameError = "Name must contain letters only";
    }
 
    // Gender validation
    if (empty($gender)) {
        $genderError = "Please select your gender";
    }
 
    // Email validation
    if ($email === "") {
        $emailError = "Email is required";
    } elseif (!preg_match('/^[a-z0-9_\.]{3,}@[a-z0-9\.]{3,15}\.[a-z]{2,5}$/i', $email)) {
        $emailError = "Please enter a valid email";
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();
        if ($check->num_rows > 0) {
            $emailError = "This email is already registered. Please login.";
        }
        $check->close();
    }
 
    // Password validation
    if ($password === "") {
        $passwordError = "Password is required";
    } elseif (strlen($password) < 8 || strlen($password) > 15) {
        $passwordError = "Password must be between 8 and 15 characters";
    } elseif (!preg_match('/[a-z]/', $password)) {
        $passwordError = "Password must have at least 1 lowercase letter";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $passwordError = "Password must have at least 1 uppercase letter";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $passwordError = "Password must have at least 1 number";
    } elseif (!preg_match('/[@#$%^&]/', $password)) {
        $passwordError = "Password must have at least 1 special character (@#$%^&)";
    }
 
    // Confirm password validation
    if ($cpassword === "") {
        $cpasswordError = "Please confirm your password";
    } elseif ($password !== $cpassword) {
        $cpasswordError = "Passwords do not match";
    }
 
    // Qualification validation
    if ($qualification === "") {
        $qualificationError = "Qualification is required";
    }
 
    // Experience validation
    if ($experience === "") {
        $experienceError = "Experience is required";
    } elseif (!is_numeric($experience) || $experience < 0 || $experience > 50) {
        $experienceError = "Please enter a valid experience (0-50 years)";
    }
 
    // Subject validation
    if (empty($subject_id)) {
        $subjectError = "Please select a subject";
    }
 
    // Board validation
    if (empty($board_id)) {
        $boardError = "Please select an education board";
    }
 
    // Phone validation
    if ($phone === "") {
        $phoneError = "Phone number is required";
    } elseif (!preg_match('/^[6-9][0-9]{9}$/', $phone)) {
        $phoneError = "Phone number must be exactly 10 digits";
    }
 
    // Address validation
    if ($address === "") {
        $addressError = "Address is required";
    }
 
    // Availability validation
    if (empty($availability)) {
        $availabilityError = "Please select your availability";
    }
 
    // Save to database
    if ($nameError === "" && $genderError === "" && $emailError === "" &&
        $passwordError === "" && $cpasswordError === "" && $qualificationError === "" && $experienceError === "" && $subjectError === "" && $boardError === "" &&
        $phoneError === "" && $addressError === "" && $availabilityError === "") {
 
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
 
        // Insert into users table
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'tutor')");
        $stmt->bind_param("sss", $name, $email, $hashed_password);
        $stmt->execute();
        $user_id = $conn->insert_id;
        $stmt->close();
 
        // Insert into tutors table (is_verified defaults to 0)
        $stmt2 = $conn->prepare("INSERT INTO tutors (user_id, gender, qualification, experience, subject_id, board_id, phone, address, availability) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt2->bind_param("issiiisss", $user_id, $gender, $qualification, $experience, $subject_id, $board_id, $phone, $address, $availability);
        $stmt2->execute();
        $stmt2->close();
 
        $formSuccess = "Registration successful! Please wait for admin verification before logging in.";
 
        // Clear form fields
        $_POST = [];
 
        // Re-fetch dropdowns after clearing POST
        $boards_result   = $conn->query("SELECT id, name FROM boards   WHERE is_active = 1 ORDER BY name ASC");
        $subjects_result = $conn->query("SELECT id, name FROM subjects WHERE is_active = 1 ORDER BY name ASC");
    }
}
 
// Load the view
include_once("../views/auth/tutor-register.php");
