<?php
require_once '../middleware/auth.php';
requireRole('admin');
require_once '../database/dbconnection.php';

$page = $_GET['page'] ?? 'dashboard';

/* ==========================
   COMPLAINTS
========================== */
$complaints = $conn->query("SELECT * FROM complaints ORDER BY id DESC");

/* ==========================
   HANDLE ACTIONS (AJAX)
========================== */
if (isset($_POST['action']) && isset($_POST['id'])) {

    $id = intval($_POST['id']);

    /* ---------------- VERIFY TUTOR ---------------- */
    if ($_POST['action'] === 'verify') {
        $conn->query("UPDATE tutors SET is_verified = 1 WHERE id = $id");
        echo "success";
        exit;
    }

    /* ---------------- REJECT TUTOR ---------------- */
    if ($_POST['action'] === 'reject') {
        $conn->query("UPDATE tutors SET is_verified = 0 WHERE id = $id");
        echo "success";
        exit;
    }

    /* ---------------- DELETE STUDENT ---------------- */
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

    /* ---------------- DELETE SUBJECT ---------------- */
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

    /* ---------------- DELETE COMPLAINT ---------------- */
    if ($_POST['action'] === 'delete') {
        $conn->query("DELETE FROM complaints WHERE id = $id");
        echo "success";
        exit;
    }

    /* ---------------- RESOLVE COMPLAINT ---------------- */
    if ($_POST['action'] === 'resolve') {
        $conn->query("UPDATE complaints SET status = 1 WHERE id = $id");
        echo "success";
        exit;
    }

    /* ---------------- ADD / DELETE CLASS & DROPDOWN ---------------- */
    if (in_array($_POST['action'], ['add_class', 'delete_class', 'delete_dropdown'])) {
        $value = trim($_POST['value'] ?? '');

        if ($_POST['action'] === 'add_class' && $value !== '') {
            $stmt = $conn->prepare("INSERT INTO dropdowns (type, value) VALUES ('class', ?)");
            $stmt->bind_param("s", $value);
            $stmt->execute();
            $conn->query("INSERT IGNORE INTO classes (name) VALUES ('$value')");
            echo "success";
            exit;
        }

        if ($_POST['action'] === 'delete_class' || $_POST['action'] === 'delete_dropdown') {
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

                if ($type === 'subject') $conn->query("DELETE FROM subjects WHERE name = '$value'");
                if ($type === 'class') $conn->query("DELETE FROM classes WHERE name = '$value'");

                echo "success";
                exit;
            }

            echo "not_found";
            exit;
        }
    }
}

/* ==========================
   DROPDOWNS LIST
========================== */
if ($page === 'dropdown') {
    $dropdowns = $conn->query("SELECT * FROM dropdowns ORDER BY type ASC");
}

/* ==========================
   MANAGE CLASS
========================== */
if ($page === 'manage_class') {
    $classes = $conn->query("SELECT id, value AS name FROM dropdowns WHERE type = 'class' ORDER BY value ASC");
}

/* ==========================
   SUBJECTS
========================== */
$subjects = $conn->query("
    SELECT s.id, s.name, COUNT(ts.tutor_id) AS tutor_count
    FROM subjects s
    LEFT JOIN tutor_subjects ts ON ts.subject_id = s.id
    GROUP BY s.id
    ORDER BY s.name ASC
");

/* ==========================
   COUNTS
========================== */
$totalTutors    = $conn->query("SELECT COUNT(*) AS total FROM tutors")->fetch_assoc()['total'] ?? 0;
$totalStudents  = $conn->query("SELECT COUNT(*) AS total FROM students")->fetch_assoc()['total'] ?? 0;
$totalBookings  = $conn->query("SELECT COUNT(*) AS total FROM bookings")->fetch_assoc()['total'] ?? 0;
$totalComplaints= $conn->query("SELECT COUNT(*) AS total FROM complaints")->fetch_assoc()['total'] ?? 0;

/* ==========================
   BOOKING & TUTOR GROWTH
========================== */
// Booking Growth
$todayBookings = $conn->query("SELECT COUNT(*) AS total FROM bookings WHERE DATE(created_at) = CURDATE()")->fetch_assoc()['total'] ?? 0;
$yesterdayBookings = $conn->query("SELECT COUNT(*) AS total FROM bookings WHERE DATE(created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)")->fetch_assoc()['total'] ?? 0;
$bookingGrowth = ($yesterdayBookings == 0) ? 100 : (($todayBookings - $yesterdayBookings) / $yesterdayBookings) * 100;

// Tutor Registration Growth
$todayRegs = $conn->query("SELECT COUNT(*) AS total FROM tutors WHERE DATE(created_at) = CURDATE()")->fetch_assoc()['total'] ?? 0;
$yesterdayRegs = $conn->query("SELECT COUNT(*) AS total FROM tutors WHERE DATE(created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)")->fetch_assoc()['total'] ?? 0;
$growth = ($yesterdayRegs == 0) ? 100 : (($todayRegs - $yesterdayRegs) / $yesterdayRegs) * 100;

/* ==========================
   TOP TUTORS
========================== */
$topTutors = $conn->query("
    SELECT t.id, u.name, IFNULL(COUNT(b.id), 0) AS total
    FROM tutors t
    JOIN users u ON t.user_id = u.id
    LEFT JOIN bookings b ON b.tutor_id = t.id
    GROUP BY t.id
    ORDER BY total DESC
    LIMIT 5
");

/* ==========================
   TUTORS WITH FILTER
========================== */
$status = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';
$sort   = $_GET['sort'] ?? '';

$query = "
    SELECT 
        t.id,
        u.name,
        u.email,
        u.profile_image,
        t.phone,
        t.address,
        t.experience,
        t.gender,
        t.is_verified,
        t.created_at,
        GROUP_CONCAT(s.name SEPARATOR ', ') AS subjects
    FROM tutors t
    JOIN users u ON t.user_id = u.id
    LEFT JOIN tutor_subjects ts ON ts.tutor_id = t.id
    LEFT JOIN subjects s ON s.id = ts.subject_id
    WHERE 1
";

if ($status !== '') $query .= " AND t.is_verified = " . intval($status);
if (!empty($search)) {
    $safe = $conn->real_escape_string($search);
    $query .= " AND (u.name LIKE '%$safe%' OR s.name LIKE '%$safe%')";
}

$query .= " GROUP BY t.id ";

if ($sort === 'rating') $query .= " ORDER BY t.rating DESC";
elseif ($sort === 'exp') $query .= " ORDER BY t.experience DESC";
else $query .= " ORDER BY t.id DESC";

$tutors = $conn->query($query);

/* ==========================
   LOAD VIEW
========================== */
require_once '../views/admin/adminDashboard.php';
?>