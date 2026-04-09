<?php

require_once '../middleware/Auth.php';
requireRole('student');
require_once '../database/dbconnection.php';

// Fetch student profile
$stmt = $conn->prepare("
    SELECT u.name, u.email, u.created_at, u.profile_image,
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

$classes = $conn->query("SELECT id, name FROM classes");
$boards  = $conn->query("SELECT id, name FROM boards");
$exams   = $conn->query("SELECT id, name FROM exams");

// Edit profile
$profileSuccess = "";
$profileError   = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $class_id = $_POST['edit_class'] ?? '';
    $board_id = $_POST['edit_board'] ?? '';
    $exam_id  = $_POST['edit_exam'] ?? '';
    $school   = trim($_POST['edit_school'] ?? '');
    $phone    = trim($_POST['edit_phone'] ?? '');
    $address  = trim($_POST['edit_address'] ?? '');

    if ($class_id === "") {
        $profileError = "Class cannot be empty.";
    } elseif ($board_id === "") {
        $profileError = "Board cannot be empty.";
    } elseif ($exam_id === "") {
        $profileError = "Exam cannot be empty.";
    } elseif ($school === "") {
        $profileError = "School cannot be empty.";
    } elseif ($phone === "") {
        $profileError = "Phone number cannot be empty.";
    } elseif (!preg_match('/^[6-9][0-9]{9}$/', $phone)) {
        $profileError = "Invalid phone number.";
    } elseif ($address === "") {
        $profileError = "Address cannot be empty.";
    } else {

        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {

            $fileName = $_FILES['profile_image']['name'];
            $tmpName  = $_FILES['profile_image']['tmp_name'];

            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png'];

            if (in_array($ext, $allowed)) {
                $newFileName = "user_" . $_SESSION['user_id'] . "_" . time() . "." . $ext;
                $uploadPath  = __DIR__ . "/../assets/profile/" . $newFileName;

                if (move_uploaded_file($tmpName, $uploadPath)) {
                    $stmtImg = $conn->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
                    $stmtImg->bind_param("si", $newFileName, $_SESSION['user_id']);
                    $stmtImg->execute();
                    $stmtImg->close();
                }
            }
        }
        $stmt = $conn->prepare("
            UPDATE students 
            SET class_id = ?, board_id = ?, exam_id = ?, 
                school_name = ?, parent_phone = ?, address = ?
            WHERE user_id = ?
        ");

        $stmt->bind_param(
            "iiisssi", $class_id, $board_id, $exam_id, $school, $phone, $address, $_SESSION['user_id']
        );

        if ($stmt->execute()) {
            $profileSuccess = "Profile updated successfully!";
        } else {
            $profileError = "Update failed.";
        }

        $stmt->close();

        // Refresh data
        $stmt2 = $conn->prepare("
            SELECT u.name, u.email, u.created_at, u.profile_image,
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

// Browse tutors
$subjects = $conn->query("SELECT id, name FROM subjects");
$browseBoards = $conn->query("SELECT id, name FROM boards");

$search = $_GET['search'] ?? '';
$gender = $_GET['gender'] ?? '';
$sort   = $_GET['sort'] ?? '';

$sql = "
SELECT t.id, u.name, t.gender, t.experience,
       GROUP_CONCAT(sub.name SEPARATOR ', ') AS subject_names,
       b.name AS board_name
FROM tutors t
JOIN users u ON u.id = t.user_id
LEFT JOIN tutor_subjects ts ON ts.tutor_id = t.id
LEFT JOIN subjects sub ON sub.id = ts.subject_id
LEFT JOIN boards b ON b.id = t.board_id
WHERE t.is_verified = 1
";
$params = [];
$types  = "";

// SEARCH
if ($search !== '') {
    $sql .= " AND (u.name LIKE ? OR sub.name LIKE ? OR b.name LIKE ? OR t.address LIKE ?)";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "ssss";
}


// FILTER GENDER
if ($gender !== '') {
    $sql .= " AND t.gender = ? AND t.is_verified = 1";
    $params[] = $gender;
    $types .= "s";
}
$sql .= "GROUP BY t.id";
// SORTING
if ($sort === 'asc') {
    $sql .= " ORDER BY u.name ASC";
} elseif ($sort === 'desc') {
    $sql .= " ORDER BY u.name DESC";
} elseif ($sort === 'exp') {
    $sql .= " ORDER BY t.experience DESC";
} 

// EXECUTE
$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$tutors = $stmt->get_result();
$stmt->close();

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

$page = $_GET['page'] ?? 'dashboard';
require_once '../views/student/studentDashboard.php';
?>