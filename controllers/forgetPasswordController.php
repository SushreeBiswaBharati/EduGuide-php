<?php
session_start();
require_once "../database/dbconnection.php";

$step = 1;
$emailError = "";
$resetError = "";
$resetSuccess = "";
$email = "";

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['step']) && $_POST['step'] == 1){
    $email = trim($_POST['email']);
    if($email === ''){
        $emailError = "Please enter your email address";
        $step = 1;
    }else{
        $stmt = $conn->prepare("SELECT id, name FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows === 0){
            $emailError = "No account found with this email address";
            $step = 1;
        }else{
            $user = $result->fetch_assoc();
            $_SESSION['reset_user_id'] = $user['id'];
            $_SESSION['reset_email'] = $email;
            $step = 2;
        }
        $stmt->close();
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['step']) && $_POST['step'] == '2'){
    $newPass = $_POST['new_password'];
    $confirmPass = $_POST['confirm_password'];
    $step = 2;

     if ($newPass === '' || $confirmPass === '') {
        $resetError = "Please fill in all fields";
 
    } elseif (strlen($newPass) < 8 || strlen($newPass) > 15) {
        $resetError = "Password must be between 8 and 15 characters";
 
    } elseif (!preg_match('/[a-z]/', $newPass)) {      
        $resetError = "Password must have at least 1 lowercase letter";
 
    } elseif (!preg_match('/[A-Z]/', $newPass)) {     
        $resetError = "Password must have at least 1 uppercase letter";
 
    } elseif (!preg_match('/[0-9]/', $newPass)) {    
        $resetError = "Password must have at least 1 number";
 
    } elseif (!preg_match('/[@#$%^&]/', $newPass)) {  
        $resetError = "Password must have at least 1 special character (@#$%^&)";
 
    } elseif ($newPass !== $confirmPass) {
        $resetError = "Passwords do not match";
 
    } elseif (!isset($_SESSION['reset_user_id'])) {
        $resetError = "Session expired. Please start again";
        $step = 1;
 
    } else {
        $hashed = password_hash($newPass, PASSWORD_DEFAULT);
        $uid    = $_SESSION['reset_user_id'];
 
        $upd = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $upd->bind_param("si", $hashed, $uid);
        $upd->execute();
        $upd->close();
 
        unset($_SESSION['reset_user_id']);
        unset($_SESSION['reset_email']);
 
        header("Location: /EduGuide-php/controllers/AuthController.php?reset=success");
        exit;
    }
}
 
if($_SERVER['REQUEST_METHOD'] === 'GET'){
    unset($_SESSION['reset_user_id']);
    unset($_SESSION['reset_email']);
    $step = 1;
}

require_once '../views/auth/forgetPassword.php';
?>