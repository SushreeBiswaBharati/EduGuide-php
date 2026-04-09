<?php
require_once '../middleware/auth.php';
requireRole('admin');
require_once '../database/dbconnection.php';

<<<<<<< HEAD
$page = $_GET['page'] ?? 'dashboard';

/* ==========================
   HANDLE ACTIONS (AJAX)
========================== */
if (isset($_POST['action']) && isset($_POST['id'])) {

=======
if (isset($_POST['action']) && isset($_POST['id'])) {
>>>>>>> 1e6023ad6511607255b4ac3a2d01971df3bc67ad
    $id = intval($_POST['id']);

    if ($_POST['action'] === 'verify') {
        $conn->query("UPDATE tutors SET is_verified = 1 WHERE id = $id");
        echo "success";
        exit;
    }
<<<<<<< HEAD

=======
>>>>>>> 1e6023ad6511607255b4ac3a2d01971df3bc67ad
    if ($_POST['action'] === 'reject') {
        $conn->query("UPDATE tutors SET is_verified = 0 WHERE id = $id");
        echo "success";
        exit;
    }
<<<<<<< HEAD

    if ($_POST['action'] === 'delete_student') {

        $stmt = $conn->prepare("SELECT user_id FROM students WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = $res->fetch_assoc();

        if ($data) {
            $user_id = $data['user_id'];

            $conn->query("DELETE FROM students WHERE id = $id");
            $conn->query("DELETE FROM users WHERE id = $user_id");

            echo "success";
            exit;
        }

        echo "not_found";
        exit;
    }

    if ($_POST['action'] === 'delete_subject') {

        $check = $conn->prepare("SELECT COUNT(*) as total FROM tutor_subjects WHERE subject_id = ?");
        $check->bind_param("i", $id);
        $check->execute();
        $result = $check->get_result()->fetch_assoc();

        if ($result['total'] > 0) {
            echo "in_use";
            exit;
        }

        $del = $conn->prepare("DELETE FROM subjects WHERE id = ?");
        $del->bind_param("i", $id);
        $del->execute();

        echo "success";
        exit;
    }

=======
    if ($_POST['action'] === 'resolve') {
        $conn->query("UPDATE complaints SET status = 'Resolved' WHERE id = $id");
        echo "success";
        exit;
    }
>>>>>>> 1e6023ad6511607255b4ac3a2d01971df3bc67ad
    if ($_POST['action'] === 'delete') {
        $conn->query("DELETE FROM complaints WHERE id = $id");
        echo "success";
        exit;
    }
<<<<<<< HEAD

    if ($_POST['action'] === 'resolve') {
        $conn->query("UPDATE complaints SET status = 1 WHERE id = $id");
        echo "success";
        exit;
    }

    if ($_POST['action'] === 'delete_dropdown') {

        $stmt = $conn->prepare("SELECT type, value FROM dropdowns WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $data = $stmt->get_result()->fetch_assoc();

        if ($data) {

            $type = $data['type'];
            $value = $data['value'];

            $del = $conn->prepare("DELETE FROM dropdowns WHERE id = ?");
            $del->bind_param("i", $id);
            $del->execute();

            if ($type === 'subject') {
                $conn->query("DELETE FROM subjects WHERE name = '$value'");
            }

            if ($type === 'class') {
                $conn->query("DELETE FROM classes WHERE name = '$value'");
            }

            echo "success";
            exit;
        }

        echo "not_found";
        exit;
    }
}

/* ==========================
   ADD DROPDOWN
========================== */
if ($page === 'dropdown' && isset($_POST['type'], $_POST['value'])) {

    $type = $_POST['type'];
    $value = trim($_POST['value']);

    if (!empty($value)) {

        $stmt = $conn->prepare("INSERT INTO dropdowns (type, value) VALUES (?, ?)");
        $stmt->bind_param("ss", $type, $value);
        $stmt->execute();

        if ($type === 'subject') {
            $conn->query("INSERT IGNORE INTO subjects (name) VALUES ('$value')");
        }

        if ($type === 'class') {
            $conn->query("INSERT IGNORE INTO classes (name) VALUES ('$value')");
        }
    }

    header("Location: AdminDashboardController.php?page=dropdown");
    exit;
}

/* ==========================
   DROPDOWNS
========================== */
if ($page === 'dropdown') {
    $dropdowns = $conn->query("SELECT * FROM dropdowns ORDER BY type ASC");
}

/* ==========================
   SUBJECTS
========================== */
$subjects = $conn->query("
    SELECT s.id, s.name,
           COUNT(ts.tutor_id) AS tutor_count
    FROM subjects s
    LEFT JOIN tutor_subjects ts ON ts.subject_id = s.id
    GROUP BY s.id
    ORDER BY s.name ASC
");

/* ==========================
   COUNTS
========================== */
$totalTutors = $conn->query("SELECT COUNT(*) AS total FROM tutors")->fetch_assoc()['total'] ?? 0;
$totalStudents = $conn->query("SELECT COUNT(*) AS total FROM students")->fetch_assoc()['total'] ?? 0;
$totalBookings = $conn->query("SELECT COUNT(*) AS total FROM bookings")->fetch_assoc()['total'] ?? 0;
$totalComplaints = $conn->query("SELECT COUNT(*) AS total FROM complaints")->fetch_assoc()['total'] ?? 0;

/* ==========================
   SAFE DEFAULTS (IMPORTANT FIX)
========================== */
$todayBookings = 0;
$yesterdayBookings = 0;
$bookingGrowth = 0;

$today = 0;
$yesterday = 0;
$growth = 0;

$topTutors = $conn->query("
    SELECT u.name, COUNT(b.id) AS total
    FROM tutors t
    JOIN users u ON u.id = t.user_id
    LEFT JOIN bookings b ON b.tutor_id = t.id
    GROUP BY t.id
    ORDER BY total DESC
    LIMIT 5
");

/* ==========================
   BOOKING STATS
========================== */
$todayBookings = $conn->query("
    SELECT COUNT(*) AS total
    FROM bookings
    WHERE DATE(created_at) = CURDATE()
")->fetch_assoc()['total'] ?? 0;

$yesterdayBookings = $conn->query("
    SELECT COUNT(*) AS total
    FROM bookings
    WHERE DATE(created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)
")->fetch_assoc()['total'] ?? 0;

$bookingGrowth = ($yesterdayBookings > 0)
    ? (($todayBookings - $yesterdayBookings) / $yesterdayBookings) * 100
    : ($todayBookings > 0 ? 100 : 0);

/* ==========================
   TUTOR REGISTRATION STATS
========================== */
$today = $conn->query("
    SELECT COUNT(*) AS total
    FROM tutors
    WHERE DATE(created_at) = CURDATE()
")->fetch_assoc()['total'] ?? 0;

$yesterday = $conn->query("
    SELECT COUNT(*) AS total
    FROM tutors
    WHERE DATE(created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)
")->fetch_assoc()['total'] ?? 0;

$growth = ($yesterday > 0)
    ? (($today - $yesterday) / $yesterday) * 100
    : ($today > 0 ? 100 : 0);

/* ==========================
   OTHER PAGES
========================== */
if ($page === 'complaint') {
    $complaints = $conn->query("SELECT * FROM complaints ORDER BY created_at DESC");
}

if ($page === 'manage_student') {
    $students = $conn->query("
        SELECT s.id, u.name, u.email
        FROM students s
        JOIN users u ON u.id = s.user_id
    ");
}

if ($page === 'booking') {
    $bookings = $conn->query("
        SELECT b.*, u.name AS student_name
        FROM bookings b
        JOIN users u ON u.id = b.student_id
    ");
}

/* ==========================
   TUTORS LIST
========================== */
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';
$sort   = $_GET['sort'] ?? '';

$sql = "
SELECT 
    t.id, u.name, u.email, t.phone, t.address,
    t.experience, t.rating, t.is_verified,
    GROUP_CONCAT(s.name SEPARATOR ', ') AS subjects
=======
}

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
    t.id, u.name, u.email, t.phone,
    t.address, t.experience, t.rating, t.is_verified,
    u.profile_image, GROUP_CONCAT(s.name SEPARATOR ', ') AS subjects
>>>>>>> 1e6023ad6511607255b4ac3a2d01971df3bc67ad
FROM tutors t
JOIN users u ON u.id = t.user_id
LEFT JOIN tutor_subjects ts ON ts.tutor_id = t.id
LEFT JOIN subjects s ON s.id = ts.subject_id
WHERE 1
";
<<<<<<< HEAD

$params = [];
$types = "";

if ($search !== '') {
    $sql .= " AND (u.name LIKE ? OR s.name LIKE ?)";
=======
$params = [];
$types  = "";


// Search
if ($search !== ''){
    $sql .= " AND (u.name LIKE ? OR  s.name LIKE ?)";
>>>>>>> 1e6023ad6511607255b4ac3a2d01971df3bc67ad
    $term = "%$search%";
    $params[] = $term;
    $params[] = $term;
    $types .= "ss";
}

<<<<<<< HEAD
if ($status !== '') {
=======
// status
if($status !== ''){
>>>>>>> 1e6023ad6511607255b4ac3a2d01971df3bc67ad
    $sql .= " AND t.is_verified = ?";
    $params[] = (int)$status;
    $types .= "i";
}

$sql .= " GROUP BY t.id";
<<<<<<< HEAD

if ($sort === 'rating') {
    $sql .= " ORDER BY t.rating DESC";
} elseif ($sort === 'exp') {
    $sql .= " ORDER BY t.experience DESC";
=======
// sort
if($sort === 'rating'){
    $sql .=" ORDER BY t.rating DESC";
}elseif($sort === 'exp'){
    $sql .="ORDER BY t.experience DESC ";
>>>>>>> 1e6023ad6511607255b4ac3a2d01971df3bc67ad
}

$stmt = $conn->prepare($sql);

<<<<<<< HEAD
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

=======
if(!empty($params)){
    $stmt->bind_param($types, ...$params);
}


>>>>>>> 1e6023ad6511607255b4ac3a2d01971df3bc67ad
$stmt->execute();
$tutors = $stmt->get_result();
$stmt->close();

<<<<<<< HEAD
/* ==========================
   LOAD VIEW
========================== */
=======

$page = $_GET['page'] ?? 'dashboard';
>>>>>>> 1e6023ad6511607255b4ac3a2d01971df3bc67ad
require_once '../views/admin/adminDashboard.php';
?>