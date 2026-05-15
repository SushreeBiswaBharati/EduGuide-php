<!-- studentController only insert the data in to the database during registration. -->

<?php
// Handles student registration
require_once('../database/dbconnection.php');

// Fetch dropdowns from database
$classes_result = $conn->query("SELECT id, name FROM classes WHERE is_active = 1 ORDER BY id ASC");
$boards_result  = $conn->query("SELECT id, name FROM boards  WHERE is_active = 1 ORDER BY name ASC");
$exams_result   = $conn->query("SELECT id, name FROM exams   WHERE is_active = 1 ORDER BY name ASC");

// Initialize all error and success variables
$nameError        = "";
$emailError       = "";
$passwordError    = "";
$cpasswordError   = "";
$genderError      = "";
$classError       = "";
$boardError       = "";
$parentPhoneError = "";
$formSuccess      = "";

// Only run when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name         = trim($_POST['name']);
    $email        = trim($_POST['email']);
    $password     = $_POST['password'];
    $cpassword    = $_POST['cpassword'];
    $gender       = $_POST['gender'];
    $class_id     = $_POST['class_name'];
    $board_id     = $_POST['board'];
    $exam_id      = !empty($_POST['target_exam']) ? $_POST['target_exam'] : null;
    $school_name  = trim($_POST['school_name']);
    $parent_name  = trim($_POST['parent_name']);
    $parent_phone = trim($_POST['parent_phone']);
    $address      = trim($_POST['address']);

    // Name validation
    if ($name === "") {
        $nameError = "Name is required";
    } elseif (strlen($name) < 3) {
        $nameError = "Name must be at least 3 characters";
    } elseif (!preg_match('/^[A-Za-z\s]+$/', $name)) {
        $nameError = "Name must contain letters only";
    }

    // Email validation
    if ($email === "") {
        $emailError = "Email is required";
    } elseif (!preg_match('/^[a-z0-9_\.]{3,}@[a-z0-9\.]{3,15}\.[a-z]{2,5}$/i', $email)) {
        $emailError = "Please enter a valid email";
    } else {
        // Check if email already exists
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

    // Gender validation
    if (empty($gender)) {
        $genderError = "Please select your gender";
    }

    // Class validation
    if (empty($class_id)) {
        $classError = "Please select your class";
    }

    // Board validation
    if (empty($board_id)) {
        $boardError = "Please select your education board";
    }

    // Parent phone validation
    if ($parent_phone !== "" && !preg_match('/^[6-9][0-9]{9}$/', $parent_phone)) {
        $parentPhoneError = "Phone number must be exactly 10 digits";
    }

    // Save to database
    if ($nameError === "" && $emailError === "" && $passwordError === "" &&
        $cpasswordError === "" && $genderError === "" && $classError === "" &&
        $boardError === "" && $parentPhoneError === "") {

        // Hash password before saving
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into users table
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'student')");
        $stmt->bind_param("sss", $name, $email, $hashed_password);
        $stmt->execute();
        $user_id = $conn->insert_id;
        $stmt->close();

        // Insert into students table
        $stmt2 = $conn->prepare("INSERT INTO students (user_id, class_id, board_id, exam_id, gender, school_name, parent_name, parent_phone, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt2->bind_param("iiiisssss", $user_id, $class_id, $board_id, $exam_id, $gender, $school_name, $parent_name, $parent_phone, $address);
        $stmt2->execute();
        $stmt2->close();

        $formSuccess = "Registration successful! Please login.";

        // Clear all POST data so form fields go empty after success
        $_POST = [];
    }
}

// Load the view
include_once("../views/auth/student-register.php");