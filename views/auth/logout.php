<?php
session_start();
$_SESSION = [];
session_destroy();
header("location: /EduGuide-php/views/auth/login.php");
exit();
?>