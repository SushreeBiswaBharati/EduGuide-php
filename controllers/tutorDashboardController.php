<?php
session_start();
require_once '../middleware/auth.php';
requireRole('tutor');
require_once '../database/dbconnection.php';

// Fetch tutor profile
$stmt = $conn->prepare("
    SELECT u.name, u.email, u.created_at,
           t.gender, t.qualification, t.experience,
           t.phone, t.address, t.availability, t.is_verified,
           s.name AS subject_name,
           b.name AS board_name
    FROM users u
    JOIN tutors t ON t.user_id = u.id
    LEFT JOIN subjects s ON s.id = t.subject_id
    LEFT JOIN boards   b ON b.id = t.board_id
    WHERE u.id = ?
");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$tutor = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Get tutor id
$tid_stmt = $conn->prepare("SELECT id FROM tutors WHERE user_id = ?");
$tid_stmt->bind_param("i", $_SESSION['user_id']);
$tid_stmt->execute();
$tid_row  = $tid_stmt->get_result()->fetch_assoc();
$tid_stmt->close();
$tutor_id = $tid_row['id'] ?? 0;

// Booking counts
$totalRequests    = 0;
$confirmedCount   = 0;
$pendingCount     = 0;
$rejectedCount    = 0;
$completedCount   = 0;

$count_stmt = $conn->prepare("
    SELECT status, COUNT(*) AS cnt
    FROM bookings
    WHERE tutor_id = ?
    GROUP BY status
");
$count_stmt->bind_param("i", $tutor_id);
$count_stmt->execute();
$count_result = $count_stmt->get_result();
while ($row = $count_result->fetch_assoc()) {
    $totalRequests += $row['cnt'];
    if (strtolower($row['status']) === 'confirmed')  $confirmedCount = $row['cnt'];
    if (strtolower($row['status']) === 'pending')    $pendingCount   = $row['cnt'];
    if (strtolower($row['status']) === 'cancelled')  $rejectedCount  = $row['cnt'];
    if (strtolower($row['status']) === 'completed')  $completedCount = $row['cnt'];
}
$count_stmt->close();

// All booking requests
$requests_stmt = $conn->prepare("
    SELECT bk.id, bk.status, bk.message, bk.mode, bk.created_at,
           u.name   AS student_name,
           sub.name AS subject_name
    FROM bookings bk
    JOIN students  st  ON st.id  = bk.student_id
    JOIN users     u   ON u.id   = st.user_id
    LEFT JOIN subjects sub ON sub.id = bk.subject_id
    WHERE bk.tutor_id = ?
    ORDER BY bk.created_at DESC
");
$requests_stmt->bind_param("i", $tutor_id);
$requests_stmt->execute();
$requests = $requests_stmt->get_result();
$requests_stmt->close();

// My schedult
$schedule_stmt = $conn->prepare("
    SELECT bk.id, bk.mode, bk.created_at,
           u.name   AS student_name,
           sub.name AS subject_name
    FROM bookings bk
    JOIN students  st  ON st.id  = bk.student_id
    JOIN users     u   ON u.id   = st.user_id
    LEFT JOIN subjects sub ON sub.id = bk.subject_id
    WHERE bk.tutor_id = ?
    AND   bk.status   = 'Confirmed'
    ORDER BY bk.created_at ASC
");
$schedule_stmt->bind_param("i", $tutor_id);
$schedule_stmt->execute();
$schedule = $schedule_stmt->get_result();
$schedule_stmt->close();

// My reviews
$reviews_stmt = $conn->prepare("
    SELECT r.rating, r.comment, r.created_at,
           u.name AS student_name
    FROM reviews r
    JOIN students st ON st.id = r.student_id
    JOIN users    u  ON u.id  = st.user_id
    WHERE r.tutor_id = ?
    ORDER BY r.created_at DESC
");
$reviews_stmt->bind_param("i", $tutor_id);
$reviews_stmt->execute();
$reviews = $reviews_stmt->get_result();
$reviews_stmt->close();

// Ratings
$avg_stmt = $conn->prepare("SELECT ROUND(AVG(rating), 1) AS avg_rating, COUNT(*) AS total_reviews FROM reviews WHERE tutor_id = ?");
$avg_stmt->bind_param("i", $tutor_id);
$avg_stmt->execute();
$avg_row      = $avg_stmt->get_result()->fetch_assoc();
$avg_stmt->close();
$avgRating    = $avg_row['avg_rating'] ?? 0;
$totalReviews = $avg_row['total_reviews'] ?? 0;

// Booking status (reject/ accept)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_action'])) {
    $booking_id = intval($_POST['booking_id']);
    $action     = $_POST['booking_action'];

    if (in_array($action, ['Confirmed', 'Cancelled'])) {
        $upd = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ? AND tutor_id = ?");
        $upd->bind_param("sii", $action, $booking_id, $tutor_id);
        $upd->execute();
        $upd->close();
    }
    header("Location: /EduGuide-php/controllers/tutorDashboardController.php");
    exit;
}

// Complaint submit
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

// Edit profile details
$profileSuccess = "";
$profileError   = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_name'])) {
    $newName     = trim($_POST['edit_name']);
    $newPhone    = trim($_POST['edit_phone']);
    $newAddress  = trim($_POST['edit_address']);
    $newQual     = trim($_POST['edit_qualification']);
    $newExp      = intval($_POST['edit_experience']);
    $newAvail    = $_POST['edit_availability'] ?? 'Yes';

    if ($newName === '') {
        $profileError = "Name cannot be empty.";
    } elseif ($newPhone !== '' && !preg_match('/^[6-9][0-9]{9}$/', $newPhone)) {
        $profileError = "Enter a valid 10-digit phone number.";
    } else {
        $u = $conn->prepare("UPDATE users SET name = ? WHERE id = ?");
        $u->bind_param("si", $newName, $_SESSION['user_id']);
        $u->execute();
        $u->close();

        $t = $conn->prepare("UPDATE tutors SET phone = ?, address = ?, qualification = ?, experience = ?, availability = ? WHERE user_id = ?");
        $t->bind_param("sssisi", $newPhone, $newAddress, $newQual, $newExp, $newAvail, $_SESSION['user_id']);
        $t->execute();
        $t->close();

        $_SESSION['name'] = $newName;
        $profileSuccess   = "Profile updated successfully!";

        // Refresh tutor data
        $stmt2 = $conn->prepare("
            SELECT u.name, u.email, u.created_at,
                   t.gender, t.qualification, t.experience,
                   t.phone, t.address, t.availability, t.is_verified,
                   s.name AS subject_name,
                   b.name AS board_name
            FROM users u
            JOIN tutors t ON t.user_id = u.id
            LEFT JOIN subjects s ON s.id = t.subject_id
            LEFT JOIN boards   b ON b.id = t.board_id
            WHERE u.id = ?
        ");
        $stmt2->bind_param("i", $_SESSION['user_id']);
        $stmt2->execute();
        $tutor = $stmt2->get_result()->fetch_assoc();
        $stmt2->close();
    }
}

$registeredDate = isset($tutor['created_at'])
    ? date('F j, Y', strtotime($tutor['created_at']))
    : 'N/A';
$today = date('F j, Y');

$statusBadge = [
    'Pending'   => 'bg-warning text-dark',
    'Confirmed' => 'bg-success text-white',
    'Cancelled' => 'bg-danger text-white',
    'Completed' => 'bg-primary text-white',
];

require_once '../views/tutor/tutorDashboard.php';
?>