<?php
define('BASE_URL', '/EduGuide-php/');
$HOST     = "localhost";
$USERNAME = "root";
$PASSWORD = "";
$DB_NAME  = "eduGuide";

$conn = new mysqli($HOST, $USERNAME, $PASSWORD, $DB_NAME);

if ($conn->connect_error) {
    die("Error: Database Couldn't be Connected!!");
}
?>