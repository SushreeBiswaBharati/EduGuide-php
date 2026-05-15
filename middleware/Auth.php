<?php
session_start();

// If not logged in - redirect to login page
if(!isset($_SESSION['user_id'])){
    header("Location: /EduGuide-php/controllers/AuthController.php");
    exit;
}
// Role-based access control
function requireRole($allowRoles){
    if(is_string($allowRoles)){
        $allowRoles = [$allowRoles];
    }
    if(!in_array($_SESSION['role'], $allowRoles)){
        switch($_SESSION['role']){
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

}