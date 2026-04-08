<?php
require_once '../middleware/auth.php';
requireRole('admin');
require_once '../database/dbconnection.php';

$res = $conn->query("SELECT COUNT(*) AS total FROM tutors");
$totalTutors = $res->fetch_assoc()['total'] ?? 0;

$res = $conn->query("SELECT COUNT(*) AS total FROM students");
$totalStudents = $res->fetch_assoc()['total'] ?? 0;

$res = $conn->query("SELECT COUNT(*) AS total FROM bookings");
$totalBookings = $res->fetch_assoc()['total'] ?? 0;

$res = $conn->query("SELECT COUNT(*) AS total FROM complaints");
$totalComplaints = $res->fetch_assoc()['total'] ?? 0;

if ($page === 'complaint') {
    $complaintsRes = $conn->query("
        SELECT c.id, c.student_id, c.tutor_id, c.message, c.status, c.created_at,
               s.name AS student_name,
               t.name AS tutor_name
        FROM complaints c
        LEFT JOIN students s ON c.student_id = s.id
        LEFT JOIN tutors t ON c.tutor_id = t.id
        ORDER BY c.created_at DESC
    ");
    $complaints = $complaintsRes;
}

// progress bar section

$todayBookings = $conn->query("
    SELECT COUNT(*) AS t FROM bookings 
    WHERE DATE(created_at) = CURDATE()
")->fetch_assoc()['t'];

$yesterdayBookings = $conn->query("
    SELECT COUNT(*) AS t FROM bookings 
    WHERE DATE(created_at) = CURDATE() - INTERVAL 1 DAY
")->fetch_assoc()['t'];

$bookingGrowth = 0;
if ($yesterdayBookings > 0) {
    $bookingGrowth = round((($todayBookings - $yesterdayBookings) / $yesterdayBookings) * 100);
}


$today = $conn->query("
    SELECT COUNT(*) AS t FROM tutors 
    WHERE DATE(created_at) = CURDATE()
")->fetch_assoc()['t'];

$yesterday = $conn->query("
    SELECT COUNT(*) AS t FROM tutors 
    WHERE DATE(created_at) = CURDATE() - INTERVAL 1 DAY
")->fetch_assoc()['t'];

// Growth %
$growth = 0;
if ($yesterday > 0) {
    $growth = round((($today - $yesterday) / $yesterday) * 100);
}

$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';
$sort = $_GET['sort'] ?? '';

$sql = "
SELECT 
    t.id, 
    u.name, 
    u.email, 
    t.phone,
    t.address,
    t.experience,
    t.rating,
    t.is_verified,
    u.profile_image,
    GROUP_CONCAT(s.name SEPARATOR ', ') AS subjects
FROM tutors t
JOIN users u ON u.id = t.user_id
LEFT JOIN tutor_subjects ts ON ts.tutor_id = t.id
LEFT JOIN subjects s ON s.id = ts.subject_id
WHERE 1
";
$params = [];
$types  = "";

// Search
if ($search !== ''){
    $sql .= " AND (u.name LIKE ? OR  s.name LIKE ?)";
    $term = "%$search%";
    $params[] = $term;
    $params[] = $term;
    $types .= "ss";
}

// status
if($status !== ''){
    $sql .= " AND t.is_verified = ?";
    $params[] = (int)$status;
    $types .= "i";
}

$sql .= " GROUP BY t.id";
// sort
if($sort === 'rating'){
    $sql .=" ORDER BY t.rating DESC";
}elseif($sort === 'exp'){
    $sql .="ORDER BY t.experience DESC ";
}

$stmt = $conn->prepare($sql);

if(!empty($params)){
    $stmt->bind_param($types, ...$params);
}


$stmt->execute();
$tutors = $stmt->get_result();
$stmt->close();


$page = $_GET['page'] ?? 'dashboard';
require_once '../views/admin/adminDashboard.php';
?>