<?php

require_once '../middleware/Auth.php';
requireRole('student');
require_once '../database/dbconnection.php';

// Fetch student profile
$stmt = $conn->prepare("
    SELECT u.name, u.email, u.created_at,
           s.gender, s.school_name, s.parent_name, s.parent_phone, s.address,
           c.name AS class_name, b.name AS board_name, e.name AS exam_name
    FROM users u
    JOIN students s ON s.user_id = u.id
    LEFT JOIN classes c ON c.id = s.class_id
    LEFT JOIN boards  b ON b.id = s.board_id
    LEFT JOIN exams   e ON e.id = s.exam_id
    WHERE u.id = ?
");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Get student_id
$sid_stmt = $conn->prepare("SELECT id FROM students WHERE user_id = ?");
$sid_stmt->bind_param("i", $_SESSION['user_id']);
$sid_stmt->execute();
$sid_row    = $sid_stmt->get_result()->fetch_assoc();
$sid_stmt->close();
$student_id = $sid_row['id'] ?? 0;

// Booking counts
$totalBookings     = 0;
$confirmedBookings = 0;
$pendingBookings   = 0;
$completedBookings = 0;

$count_stmt = $conn->prepare("SELECT status, COUNT(*) AS cnt FROM bookings WHERE student_id = ? GROUP BY status");
$count_stmt->bind_param("i", $student_id);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
while ($row = $count_result->fetch_assoc()) {
    $totalBookings += $row['cnt'];
    if (strtolower($row['status']) === 'confirmed')  $confirmedBookings  = $row['cnt'];
    if (strtolower($row['status']) === 'pending')    $pendingBookings    = $row['cnt'];
    if (strtolower($row['status']) === 'completed')  $completedBookings  = $row['cnt'];
}
$count_stmt->close();

// Booking history
$bookings_stmt = $conn->prepare("
    SELECT bk.id, bk.status, bk.created_at,
           u.name   AS tutor_name,
           sub.name AS subject_name
    FROM bookings bk
    JOIN tutors   t   ON t.id  = bk.tutor_id
    JOIN users    u   ON u.id  = t.user_id
    LEFT JOIN subjects sub ON sub.id = bk.subject_id
    WHERE bk.student_id = ?
    ORDER BY bk.created_at DESC
");
$bookings_stmt->bind_param("i", $student_id);
$bookings_stmt->execute();
$bookings = $bookings_stmt->get_result();
$bookings_stmt->close();

// complaint submission
$complaintSuccess = "";
$complaintError   = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complaint_subject'])) {
    $subject = trim($_POST['complaint_subject']);
    $message = trim($_POST['complaint_message']);
    if ($subject !== '' && $message !== '') {
        $cstmt = $conn->prepare("INSERT INTO complaints (user_id, subject, message) VALUES (?, ?, ?)");
        $cstmt->bind_param("iss", $_SESSION['user_id'], $subject, $message);
        $cstmt->execute();
        $cstmt->close();
        $complaintSuccess = "Your complaint has been submitted successfully!";
    } else {
        $complaintError = "Please fill in all fields.";
    }
}

// Edit profile section
$profileSuccess = "";
$profileError   = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_name'])) {
    $newName    = trim($_POST['edit_name']);
    $newPhone   = trim($_POST['edit_phone']);
    $newAddress = trim($_POST['edit_address']);
    $newSchool  = trim($_POST['edit_school']);

    if ($newName === '') {
        $profileError = "Name cannot be empty.";
    } elseif ($newPhone !== '' && !preg_match('/^[6-9][0-9]{9}$/', $newPhone)) {
        $profileError = "Enter a valid 10-digit phone number.";
    } else {
        $u = $conn->prepare("UPDATE users SET name = ? WHERE id = ?");
        $u->bind_param("si", $newName, $_SESSION['user_id']);
        $u->execute();
        $u->close();

        $s = $conn->prepare("UPDATE students SET parent_phone = ?, address = ?, school_name = ? WHERE user_id = ?");
        $s->bind_param("sssi", $newPhone, $newAddress, $newSchool, $_SESSION['user_id']);
        $s->execute();
        $s->close();

        $_SESSION['name'] = $newName;
        $profileSuccess   = "Profile updated successfully!";

        // Refresh student data after update
        $stmt2 = $conn->prepare("
            SELECT u.name, u.email, u.created_at,
                   s.gender, s.school_name, s.parent_name, s.parent_phone, s.address,
                   c.name AS class_name, b.name AS board_name, e.name AS exam_name
            FROM users u
            JOIN students s ON s.user_id = u.id
            LEFT JOIN classes c ON c.id = s.class_id
            LEFT JOIN boards  b ON b.id = s.board_id
            LEFT JOIN exams   e ON e.id = s.exam_id
            WHERE u.id = ?
        ");
        $stmt2->bind_param("i", $_SESSION['user_id']);
        $stmt2->execute();
        $student = $stmt2->get_result()->fetch_assoc();
        $stmt2->close();
    }
}

// created_at
$registeredDate = isset($student['created_at'])
    ? date('F j, Y', strtotime($student['created_at']))
    : 'N/A';
$today = date('F j, Y');

$statusBadge = [
    'Pending'   => 'bg-warning text-dark',
    'Confirmed' => 'bg-success text-white',
    'Cancelled' => 'bg-danger text-white',
    'Completed' => 'bg-primary text-white',
];

require_once '../views/student/studentDashboard.php';
?>