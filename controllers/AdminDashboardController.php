<?php
require_once '../middleware/auth.php';
requireRole('admin');
require_once '../database/dbconnection.php';

// ✅ define page FIRST
$page = $_GET['page'] ?? 'dashboard';


// =====================================================
// 🔥 HANDLE ALL ACTIONS (AJAX)
// =====================================================
if (isset($_POST['action']) && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Tutor verify
    if ($_POST['action'] === 'verify') {
        $conn->query("UPDATE tutors SET is_verified = 1 WHERE id = $id");
        echo "success";
        exit;
    }

    // Tutor reject
    if ($_POST['action'] === 'reject') {
        $conn->query("UPDATE tutors SET is_verified = 0 WHERE id = $id");
        echo "success";
        exit;
    }

    // Resolve complaint
    if ($_POST['action'] === 'resolve') {
        $conn->query("UPDATE complaints SET status = 1 WHERE id = $id");
        echo "success";
        exit;
    }

    // Delete complaint
    if ($_POST['action'] === 'delete') {
        $conn->query("DELETE FROM complaints WHERE id = $id");
        echo "success";
        exit;
    }

    // ✅ DELETE STUDENT (NEW)
    if ($_POST['action'] === 'delete_student') {
        $conn->query("DELETE FROM students WHERE id = $id");
        echo "success";
        exit;
    }

    // ❌ IMPORTANT: STOP dropdown delete conflict
    if ($_POST['action'] === 'delete_dropdown') {
        // handled below separately
    }
}


// =====================================================
// 🔥 DROPDOWN HANDLING (ADD / DELETE / FETCH)
// =====================================================

// ADD dropdown
if ($page === 'dropdown' && isset($_POST['type'], $_POST['value'])) {
    $type  = $_POST['type'];
    $value = trim($_POST['value']);

    if (!empty($value)) {
        $stmt = $conn->prepare("INSERT INTO dropdowns (type, value) VALUES (?, ?)");
        $stmt->bind_param("ss", $type, $value);
        $stmt->execute();
    }

    header("Location: AdminDashboardController.php?page=dropdown");
    exit;
}

// DELETE dropdown
if ($page === 'dropdown' && isset($_POST['action']) && $_POST['action'] === 'delete_dropdown') {
    $id = intval($_POST['id']);
    $conn->query("DELETE FROM dropdowns WHERE id = $id");
    echo "success";
    exit;
}

// FETCH dropdowns
if ($page === 'dropdown') {
    $dropdowns = $conn->query("SELECT * FROM dropdowns ORDER BY type ASC");
}


// =====================================================
// 📊 DASHBOARD COUNTS
// =====================================================
$totalTutors = $conn->query("SELECT COUNT(*) AS total FROM tutors")->fetch_assoc()['total'] ?? 0;
$totalStudents = $conn->query("SELECT COUNT(*) AS total FROM students")->fetch_assoc()['total'] ?? 0;
$totalBookings = $conn->query("SELECT COUNT(*) AS total FROM bookings")->fetch_assoc()['total'] ?? 0;
$totalComplaints = $conn->query("SELECT COUNT(*) AS total FROM complaints")->fetch_assoc()['total'] ?? 0;


// =====================================================
// 📢 COMPLAINTS SECTION
// =====================================================
if ($page === 'complaint') {
    $complaints = $conn->query("
        SELECT * FROM complaints 
        ORDER BY created_at DESC
    ");
}


// =====================================================
// 👨‍🎓 MANAGE STUDENTS (NEW)
// =====================================================
if ($page === 'manage_student') {
    $students = $conn->query("
        SELECT s.id, u.name, u.email
        FROM students s
        JOIN users u ON u.id = s.user_id 
        ORDER BY id DESC
    ");
}


// =====================================================
// 📅 BOOKINGS SECTION (NEW)
// =====================================================
$bookings = null; 

if ($page === 'booking') {
    $bookings = $conn->query("
        SELECT b.*, u.name AS student_name
        FROM bookings b
        JOIN users u ON u.id = b.student_id
        ORDER BY b.created_at DESC
    ");
}


// =====================================================
// 📈 BOOKINGS PROGRESS
// =====================================================
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


// =====================================================
// 📈 TUTOR GROWTH
// =====================================================
$today = $conn->query("
    SELECT COUNT(*) AS t FROM tutors 
    WHERE DATE(created_at) = CURDATE()
")->fetch_assoc()['t'];

$yesterday = $conn->query("
    SELECT COUNT(*) AS t FROM tutors 
    WHERE DATE(created_at) = CURDATE() - INTERVAL 1 DAY
")->fetch_assoc()['t'];

$growth = 0;
if ($yesterday > 0) {
    $growth = round((($today - $yesterday) / $yesterday) * 100);
}


// =====================================================
// 🔍 TUTOR FILTER + SEARCH + SORT
// =====================================================
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';
$sort   = $_GET['sort'] ?? '';

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
if ($search !== '') {
    $sql .= " AND (u.name LIKE ? OR s.name LIKE ?)";
    $term = "%$search%";
    $params[] = $term;
    $params[] = $term;
    $types .= "ss";
}

// Status filter
if ($status !== '') {
    $sql .= " AND t.is_verified = ?";
    $params[] = (int)$status;
    $types .= "i";
}

$sql .= " GROUP BY t.id";

// Sorting
if ($sort === 'rating') {
    $sql .= " ORDER BY t.rating DESC";
} elseif ($sort === 'exp') {
    $sql .= " ORDER BY t.experience DESC";
}

// Execute
$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$tutors = $stmt->get_result();
$stmt->close();


// =====================================================
// 📄 LOAD VIEW
// =====================================================
require_once '../views/admin/adminDashboard.php';
?>