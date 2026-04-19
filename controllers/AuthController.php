<?php
session_start();

// If logged in, redirect to their dashboard by the role of the user 
if(isset($_SESSION['user_id'])){
    redirectByRole($_SESSION['role']);
    exit;
}

require_once('../database/dbconnection.php');

$emailError = "";
$passwordError = "";
$loginError = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if($email === ""){
        $emailError = "Email is required";
    }
    elseif (!preg_match('/^[a-z0-9_\.]{3,}@[a-z0-9\.]{3,15}\.[a-z]{2,5}$/i', $email)) {
        $emailError = "Please enter a valid email";
    }

    if($password === ""){
        $passwordError = "Password is required";
    }

    if($emailError === "" && $passwordError === ""){

        if ($email === 'admin@gmail.com' && $password === 'Admin@123') {
            $_SESSION['user_id'] = 0;  
            $_SESSION['name']    = 'Admin';
            $_SESSION['role']    = 'admin';
            redirectByRole('admin');
            exit;
        }
        $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows === 1){
            $user = $result->fetch_assoc();

            if(password_verify($password, $user['password'])){
                // Check if tutor is verified by admin
                if($user['role'] === 'tutor'){
                    $checkVerified = $conn->prepare("SELECT is_verified FROM tutors WHERE user_id = ?");
                    $checkVerified->bind_param("i", $user['id']);
                    $checkVerified->execute();
                    $verifiedResult = $checkVerified->get_result();
                    $tutorRow = $verifiedResult->fetch_assoc();
                    $checkVerified->close();

                    if(!$tutorRow || $tutorRow['is_verified'] == 0){
                        $loginError = "Your account is pending admin verification. Please wait.";
                        $stmt->close();
                        include("../views/auth/login.php");
                        exit;
                    }
                }
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];

                $stmt->close();

                redirectByRole($user['role']);
                exit;
            }else{
                $loginError = "Incorrect Password. Please try again";
            }
            
        }else{
            $loginError = "No account found with this email.";
        }
        $stmt->close();
    }
}

function redirectByRole($role) {
    switch ($role){
        case 'student':
            header("Location: /EduGuide-php/controllers/StudentDashboardController.php");
            break;
        case 'tutor':
            header("Location: /EduGuide-php/controllers/TutorDashboardController.php");
            break;
        case 'admin':
            header("Location: /EduGuide-php/controllers/AdminDashboardController.php");
            break;
        default:
            header("Location: /EduGuide-php/index.php");
    }
    exit;
}

include("../views/auth/login.php");