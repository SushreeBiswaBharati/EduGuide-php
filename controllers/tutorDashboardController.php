<?php
require_once '../middleware/auth.php';
requireRole('tutor');
require_once '../database/dbconnection.php';

// Fetch Profile Details
$stmt = $conn->prepare("
    SELECT u.name, u.email, u.created_at, u.profile_image,
           t.gender, t.qualification, t.experience,
           t.phone, t.address, t.availability,
           GROUP_CONCAT(s.name SEPARATOR ', ') AS subject_names,
           b.name AS board_name
    FROM users u
    JOIN tutors t ON t.user_id = u.id
    LEFT JOIN tutor_subjects ts ON ts.tutor_id = t.id
    LEFT JOIN subjects s ON s.id = ts.subject_id
    LEFT JOIN boards b ON b.id = t.board_id
    WHERE u.id = ?
    GROUP BY u.id
");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$tutor = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Get Tutor id
$tid_stmt = $conn->prepare("SELECT id FROM tutors WHERE user_id = ?");
$tid_stmt->bind_param("i", $_SESSION['user_id']);
$tid_stmt->execute();
$tid_row  = $tid_stmt->get_result()->fetch_assoc();
$tid_stmt->close();
$tutor_id = $tid_row['id'] ?? 0;

// Booking count
$totalRequests  = 0;
$confirmedCount = 0;
$pendingCount   = 0;
$completedCount = 0;

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
    if ($row['status'] === 'Confirmed') $confirmedCount = $row['cnt'];
    if ($row['status'] === 'Pending')   $pendingCount   = $row['cnt'];
    if ($row['status'] === 'Completed') $completedCount = $row['cnt'];
}
$count_stmt->close();

// All booking requests
$req_stmt = $conn->prepare("
    SELECT bk.id, bk.status, bk.created_at,
           bk.requirement, bk.duration_months,
           u.name   AS student_name,
           sub.name AS subject_name
    FROM bookings bk
    JOIN students  st  ON st.id  = bk.student_id
    JOIN users     u   ON u.id   = st.user_id
    LEFT JOIN subjects sub ON sub.id = bk.subject_id
    WHERE bk.tutor_id = ?
    ORDER BY bk.created_at DESC
");
$req_stmt->bind_param("i", $tutor_id);
$req_stmt->execute();
$requests = $req_stmt->get_result();
$req_stmt->close();

// My schedules
$sch_stmt = $conn->prepare("
    SELECT bk.id, bk.created_at,
           u.name   AS student_name,
           sub.name AS subject_name
    FROM bookings bk
    JOIN students  st  ON st.id  = bk.student_id
    JOIN users     u   ON u.id   = st.user_id
    LEFT JOIN subjects sub ON sub.id = bk.subject_id
    WHERE bk.tutor_id = ?
      AND bk.status   = 'Confirmed'
    ORDER BY bk.created_at DESC
");
$sch_stmt->bind_param("i", $tutor_id);
$sch_stmt->execute();
$schedule = $sch_stmt->get_result();
$sch_stmt->close();

// Reviews
$rev_stmt = $conn->prepare("
    SELECT r.rating, r.comment, r.created_at,
           u.name AS student_name
    FROM reviews r
    JOIN students st ON st.id = r.student_id
    JOIN users    u  ON u.id  = st.user_id
    WHERE r.tutor_id = ?
    ORDER BY r.created_at DESC
");
$rev_stmt->bind_param("i", $tutor_id);
$rev_stmt->execute();
$reviews = $rev_stmt->get_result();
$rev_stmt->close();

// Average rating
$rat_stmt = $conn->prepare("
    SELECT ROUND(AVG(rating), 1) AS avg_rating, COUNT(*) AS total
    FROM reviews
    WHERE tutor_id = ?
");
$rat_stmt->bind_param("i", $tutor_id);
$rat_stmt->execute();
$rat_row      = $rat_stmt->get_result()->fetch_assoc();
$rat_stmt->close();
$avgRating    = $rat_row['avg_rating'] ?? '0.0';
$totalReviews = $rat_row['total'] ?? 0;

// Accept / Reject bookings
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_action'], $_POST['booking_id'])) {
    $action     = $_POST['booking_action'];   // 'Confirmed' or 'Cancelled'
    $booking_id = intval($_POST['booking_id']);

    // Only allow valid statuses
    if (in_array($action, ['Confirmed', 'Cancelled', 'Completed'])) {
        $upd = $conn->prepare("
            UPDATE bookings SET status = ?
            WHERE id = ? AND tutor_id = ?
        ");
        $upd->bind_param("sii", $action, $booking_id, $tutor_id);
        $upd->execute();
        $upd->close();
    }
    header("Location: /EduGuide-php/controllers/tutorDashboardController.php?page=requests");
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

// Edit profile
$profileSuccess = "";
$profileError   = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {

        $fileName = $_FILES['profile_image']['name'];
        $tmpName  = $_FILES['profile_image']['tmp_name'];

        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];

        if (!in_array($ext, $allowed)) {
            $profileError = "Only JPG, JPEG, PNG allowed.";
        } else {
            $newFileName = "user_" . $_SESSION['user_id'] . "_" . time() . "." . $ext;
            $uploadPath = __DIR__ . "/../assets/profile/" . $newFileName;
            if (move_uploaded_file($tmpName, $uploadPath)) {
                $stmtImg = $conn->prepare("UPDATE users SET profile_image = ? WHERE id = ?");
                $stmtImg->bind_param("si", $newFileName, $_SESSION['user_id']);
                $stmtImg->execute();
                $stmtImg->close();
            } else {
                $profileError = "Image upload failed.";
            }
        }
    }

    $phone        = trim($_POST['edit_phone'] ?? '');
    $qualification= trim($_POST['edit_qualification'] ?? '');
    $experience   = intval($_POST['edit_experience'] ?? 0);
    $address      = trim($_POST['edit_address'] ?? '');
    $availability = ($_POST['edit_availability'] ?? 'No') === 'Yes' ? 'Yes' : 'No';

    if ($profileError === "") {

        $stmt2 = $conn->prepare("
            UPDATE tutors 
            SET phone = ?, qualification = ?, experience = ?, address = ?, availability = ?
            WHERE user_id = ?
        ");

        $stmt2->bind_param(
            "ssissi", $phone, $qualification, $experience, $address, $availability, $_SESSION['user_id']
        );

        if ($stmt2->execute()) {
            $profileSuccess = "Profile updated successfully!";
        } else {
            $profileError = "Update failed.";
        }

        $stmt2->close();

        // Refresh data
       $stmt3 = $conn->prepare("
            SELECT u.name, u.email, u.created_at, u.profile_image,
                t.gender, t.qualification, t.experience,
                t.phone, t.address, t.availability,
                GROUP_CONCAT(s.name SEPARATOR ', ') AS subject_names,
                b.name AS board_name
            FROM users u
            JOIN tutors t ON t.user_id = u.id
            LEFT JOIN tutor_subjects ts ON ts.tutor_id = t.id
            LEFT JOIN subjects s ON s.id = ts.subject_id
            LEFT JOIN boards b ON b.id = t.board_id
            WHERE u.id = ?
            GROUP BY u.id
        ");

        $stmt3->bind_param("i", $_SESSION['user_id']);
        $stmt3->execute();
        $tutor = $stmt3->get_result()->fetch_assoc();
        $stmt3->close();
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

$page = $_GET['page'] ?? 'dashboard';
require_once '../views/tutor/tutorDashboard.php';
?>