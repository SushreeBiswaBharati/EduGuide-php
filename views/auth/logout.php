<?php
session_start();
$_SESSION = [];
session_destroy();
header("Location: /EduGuide-php/controllers/AuthController.php");
exit();
?>