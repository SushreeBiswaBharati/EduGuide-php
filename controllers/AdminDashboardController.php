<?php
require_once '../middleware/auth.php';
requireRole('admin');
require_once '../database/dbconnection.php';

/* ======================
   DASHBOARD STATS
====================== */

// Total Tutors
$res = $conn->query("SELECT COUNT(*) AS total FROM tutors");
$totalTutors = $res->fetch_assoc()['total'] ?? 0;

// Total Students
$res = $conn->query("SELECT COUNT(*) AS total FROM students");
$totalStudents = $res->fetch_assoc()['total'] ?? 0;

// Total Bookings
$res = $conn->query("SELECT COUNT(*) AS total FROM bookings");
$totalBookings = $res->fetch_assoc()['total'] ?? 0;

// Total Complaints
$res = $conn->query("SELECT COUNT(*) AS total FROM complaints");
$totalComplaints = $res->fetch_assoc()['total'] ?? 0;


/* ======================
   MANAGE TUTORS
====================== */

$search = $_GET['search'] ?? '';

$sql = "
SELECT t.id, u.name, u.email, t.gender, t.experience,
       t.availability,
       GROUP_CONCAT(s.name SEPARATOR ', ') AS subjects,
       b.name AS board_name
FROM tutors t
JOIN users u ON u.id = t.user_id
LEFT JOIN tutor_subjects ts ON ts.tutor_id = t.id
LEFT JOIN subjects s ON s.id = ts.subject_id
LEFT JOIN boards b ON b.id = t.board_id
WHERE 1
";

$params = [];
$types  = "";

// SEARCH
if ($search !== '') {
    $sql .= " AND (u.name LIKE ? OR u.email LIKE ? OR s.name LIKE ?)";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "sss";
}

$sql .= " GROUP BY t.id ORDER BY t.id DESC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$tutors = $stmt->get_result();
$stmt->close();

$page = $_GET['page'] ?? 'dashboard';
require_once '../views/admin/adminDashboard.php';
?>