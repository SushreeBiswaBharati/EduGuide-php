<?php
require_once '../middleware/auth.php';
requireRole('admin');
require_once '../database/dbconnection.php';

$page = $_GET['page'] ?? 'dashboard';
require_once '../views/admin/adminDashboard.php';

?>