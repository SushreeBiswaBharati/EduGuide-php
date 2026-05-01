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

// Get student_id from students table
$sid_stmt = $conn->prepare("SELECT id FROM students WHERE user_id = ?");
$sid_stmt->bind_param("i", $_SESSION['user_id']);
$sid_stmt->execute();
$sid_row    = $sid_stmt->get_result()->fetch_assoc();
$sid_stmt->close();
$student_id = $sid_row['id'] ?? 0;

// Booking counts for dashboard cards
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
    if ($row['status'] === 'Confirmed') $confirmedBookings = $row['cnt'];
    if ($row['status'] === 'Pending')   $pendingBookings   = $row['cnt'];
    if ($row['status'] === 'Completed') $completedBookings = $row['cnt'];
}
$count_stmt->close();

// Booking history (My Bookings page)
$bookings_stmt = $conn->prepare("
    SELECT bk.id, bk.status, bk.created_at, bk.requirement, bk.duration_months,
           u.name   AS tutor_name,
           sub.name AS subject_name
    FROM bookings bk
    JOIN tutors   t   ON t.id = bk.tutor_id
    JOIN users    u   ON u.id = t.user_id
    LEFT JOIN subjects sub ON sub.id = bk.subject_id
    WHERE bk.student_id = ?
    ORDER BY bk.created_at DESC
");
$bookings_stmt->bind_param("i", $student_id);
$bookings_stmt->execute();
$bookings = $bookings_stmt->get_result();
$bookings_stmt->close();

// ============================================================
//  BOOK TUTOR
// ============================================================
$bookingSuccess = "";
$bookingError   = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_tutor'])) {

    $tutor_id        = intval($_POST['tutor_id']        ?? 0);
    $subject_id      = intval($_POST['subject_id']      ?? 0);
    $requirement     = trim($_POST['requirement']       ?? '');
    $duration_months = intval($_POST['duration_months'] ?? 0);

    if ($tutor_id <= 0) {
        $bookingError = "Invalid tutor selected.";
    } elseif ($subject_id <= 0) {
        $bookingError = "Please select a subject.";
    } elseif ($requirement === '') {
        $bookingError = "Please describe your requirement.";
    } elseif ($duration_months <= 0 || $duration_months > 24) {
        $bookingError = "Duration must be between 1 and 24 months.";
    } else {
        // Check for existing pending booking with same tutor
        $dupCheck = $conn->prepare("
            SELECT id FROM bookings
            WHERE student_id = ? AND tutor_id = ? AND status = 'Pending'
        ");
        $dupCheck->bind_param("ii", $student_id, $tutor_id);
        $dupCheck->execute();
        $dupCheck->store_result();

        if ($dupCheck->num_rows > 0) {
            $bookingError = "You already have a pending request with this tutor. Please wait for their response.";
        } else {
            // Insert into bookings — exact schema columns only
            $ins = $conn->prepare("
                INSERT INTO bookings (student_id, tutor_id, subject_id, requirement, duration_months)
                VALUES (?, ?, ?, ?, ?)
            ");
            $ins->bind_param("iiisi", $student_id, $tutor_id, $subject_id, $requirement, $duration_months);

            if ($ins->execute()) {

                // Fetch tutor email + name for notification email
                $tInfo = $conn->prepare("
                    SELECT u.email, u.name AS tutor_name
                    FROM tutors t JOIN users u ON u.id = t.user_id
                    WHERE t.id = ?
                ");
                $tInfo->bind_param("i", $tutor_id);
                $tInfo->execute();
                $tRow = $tInfo->get_result()->fetch_assoc();
                $tInfo->close();

                // Fetch subject name for email body
                $subStmt = $conn->prepare("SELECT name FROM subjects WHERE id = ?");
                $subStmt->bind_param("i", $subject_id);
                $subStmt->execute();
                $subName = $subStmt->get_result()->fetch_assoc()['name'] ?? 'N/A';
                $subStmt->close();

                if ($tRow) {
                    $tutorEmail  = $tRow['email'];
                    $tutorName   = $tRow['tutor_name'];
                    $studentName = $student['name'];

                    $emailSubject = "New Tuition Request from {$studentName} - EduGuide";

                    $body  = "Dear {$tutorName},\r\n\r\n";
                    $body .= "You have received a new tuition session request on EduGuide.\r\n\r\n";
                    $body .= "========== Request Details ==========\r\n";
                    $body .= "Student     : {$studentName}\r\n";
                    $body .= "Subject     : {$subName}\r\n";
                    $body .= "Requirement : {$requirement}\r\n";
                    $body .= "Duration    : {$duration_months} month(s)\r\n";
                    $body .= "=====================================\r\n\r\n";
                    $body .= "Log in to your EduGuide Tutor Dashboard to Accept or Reject this request:\r\n";
                    $body .= "http://localhost/EduGuide-php/controllers/TutorDashboardController.php?page=requests\r\n\r\n";
                    $body .= "Regards,\r\nEduGuide Team\r\n";

                    $headers  = "From: no-reply@eduguide.com\r\n";
                    $headers .= "Reply-To: no-reply@eduguide.com\r\n";
                    $headers .= "X-Mailer: PHP/" . phpversion();

                    @mail($tutorEmail, $emailSubject, $body, $headers);
                }

                $bookingSuccess = "Your booking request has been sent successfully! The tutor will be notified by email.";

            } else {
                $bookingError = "Failed to send booking request. Please try again.";
            }
            $ins->close();
        }
        $dupCheck->close();
    }
}

// ============================================================
//  CANCEL BOOKING (student cancels their own Pending booking)
// ============================================================
$cancelSuccess = "";
$cancelError   = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_booking_id'])) {
    $cancel_id = intval($_POST['cancel_booking_id']);

    // Verify the booking belongs to this student and is still Pending
    $chk = $conn->prepare("
        SELECT id FROM bookings
        WHERE id = ? AND student_id = ? AND status = 'Pending'
    ");
    $chk->bind_param("ii", $cancel_id, $student_id);
    $chk->execute();
    $chk->store_result();

    if ($chk->num_rows === 0) {
        $cancelError = "Booking not found or cannot be cancelled.";
    } else {
        $upd = $conn->prepare("UPDATE bookings SET status = 'Cancelled' WHERE id = ?");
        $upd->bind_param("i", $cancel_id);
        if ($upd->execute()) {
            $cancelSuccess = "Booking cancelled successfully.";
        } else {
            $cancelError = "Failed to cancel. Please try again.";
        }
        $upd->close();
    }
    $chk->close();

    // Refresh booking counts after cancel
    $totalBookings = $confirmedBookings = $pendingBookings = $completedBookings = 0;
    $count_stmt2 = $conn->prepare("SELECT status, COUNT(*) AS cnt FROM bookings WHERE student_id = ? GROUP BY status");
    $count_stmt2->bind_param("i", $student_id);
    $count_stmt2->execute();
    $cr2 = $count_stmt2->get_result();
    while ($row = $cr2->fetch_assoc()) {
        $totalBookings += $row['cnt'];
        if ($row['status'] === 'Confirmed') $confirmedBookings = $row['cnt'];
        if ($row['status'] === 'Pending')   $pendingBookings   = $row['cnt'];
        if ($row['status'] === 'Completed') $completedBookings = $row['cnt'];
    }
    $count_stmt2->close();

    // Re-fetch bookings list
    $bookings_stmt2 = $conn->prepare("
        SELECT bk.id, bk.status, bk.created_at, bk.requirement, bk.duration_months,
               u.name   AS tutor_name,
               sub.name AS subject_name
        FROM bookings bk
        JOIN tutors   t   ON t.id = bk.tutor_id
        JOIN users    u   ON u.id = t.user_id
        LEFT JOIN subjects sub ON sub.id = bk.subject_id
        WHERE bk.student_id = ?
        ORDER BY bk.created_at DESC
    ");
    $bookings_stmt2->bind_param("i", $student_id);
    $bookings_stmt2->execute();
    $bookings = $bookings_stmt2->get_result();
    $bookings_stmt2->close();
}

// ============================================================
//  COMPLAINT SUBMISSION
// ============================================================
$complaintSuccess = "";
$complaintError   = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complaint_subject'])) {
    $cSubject = trim($_POST['complaint_subject']);
    $cMessage = trim($_POST['complaint_message']);
    if ($cSubject !== '' && $cMessage !== '') {
        $cstmt = $conn->prepare("INSERT INTO complaints (user_id, subject, message) VALUES (?, ?, ?)");
        $cstmt->bind_param("iss", $_SESSION['user_id'], $cSubject, $cMessage);
        $cstmt->execute();
        $cstmt->close();
        $complaintSuccess = "Your complaint has been submitted successfully!";
    } else {
        $complaintError = "Please fill in all fields.";
    }
}

// ============================================================
//  EDIT PROFILE
// ============================================================
$profileSuccess = "";
$profileError   = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_class'])) {

    $class_id = $_POST['edit_class'] ?? '';
    $board_id = $_POST['edit_board'] ?? '';
    $exam_id  = !empty($_POST['edit_exam']) ? intval($_POST['edit_exam']) : null;
    $school   = trim($_POST['edit_school']  ?? '');
    $phone    = trim($_POST['edit_phone']   ?? '');
    $address  = trim($_POST['edit_address'] ?? '');

    if ($class_id === '') {
        $profileError = "Class cannot be empty.";
    } elseif ($board_id === '') {
        $profileError = "Board cannot be empty.";
    } elseif ($school === '') {
        $profileError = "School name cannot be empty.";
    } elseif ($phone === '') {
        $profileError = "Phone number cannot be empty.";
    } elseif (!preg_match('/^[6-9][0-9]{9}$/', $phone)) {
        $profileError = "Please enter a valid 10-digit phone number.";
    } elseif ($address === '') {
        $profileError = "Address cannot be empty.";
    } else {

        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
            $fileName = $_FILES['profile_image']['name'];
            $tmpName  = $_FILES['profile_image']['tmp_name'];
            $ext      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png'])) {
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
        $stmt->bind_param("iiisssi", $class_id, $board_id, $exam_id, $school, $phone, $address, $_SESSION['user_id']);

        if ($stmt->execute()) {
            $profileSuccess = "Profile updated successfully!";
        } else {
            $profileError = "Update failed. Please try again.";
        }
        $stmt->close();

        // Refresh student data after update
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

// Dropdowns for profile edit
$classes = $conn->query("SELECT id, name FROM classes WHERE is_active = 1 ORDER BY id ASC");
$boards  = $conn->query("SELECT id, name FROM boards  WHERE is_active = 1 ORDER BY name ASC");
$exams   = $conn->query("SELECT id, name FROM exams   WHERE is_active = 1 ORDER BY name ASC");

// ============================================================
//  BROWSE TUTORS
// ============================================================
$subjects     = $conn->query("SELECT id, name FROM subjects WHERE is_active = 1 ORDER BY name ASC");
$browseBoards = $conn->query("SELECT id, name FROM boards   WHERE is_active = 1 ORDER BY name ASC");

$search = $_GET['search'] ?? '';
$gender = $_GET['gender'] ?? '';
$sort   = $_GET['sort']   ?? '';

$sql = "
    SELECT t.id, u.profile_image, u.name, t.gender, t.experience,
           ROUND(COALESCE(AVG(rv.rating), 0), 1) AS rating,
           GROUP_CONCAT(DISTINCT sub.name ORDER BY sub.name SEPARATOR ', ') AS subject_names,
           b.name AS board_name
    FROM tutors t
    JOIN users u ON u.id = t.user_id
    LEFT JOIN tutor_subjects ts ON ts.tutor_id = t.id
    LEFT JOIN subjects sub ON sub.id = ts.subject_id
    LEFT JOIN boards b ON b.id = t.board_id
    LEFT JOIN reviews rv ON rv.tutor_id = t.id
    WHERE t.is_verified = 1
";
$params = [];
$types  = "";

if ($search !== '') {
    $sql .= " AND (u.name LIKE ? OR sub.name LIKE ? OR b.name LIKE ? OR t.address LIKE ?)";
    $term     = "%{$search}%";
    $params   = [$term, $term, $term, $term];
    $types    = "ssss";
}
if ($gender !== '') {
    $sql    .= " AND t.gender = ?";
    $params[] = $gender;
    $types   .= "s";
}

$sql .= " GROUP BY t.id, u.profile_image, u.name, t.gender, t.experience, b.name";

if ($sort === 'asc')          $sql .= " ORDER BY u.name ASC";
elseif ($sort === 'desc')     $sql .= " ORDER BY u.name DESC";
elseif ($sort === 'exp')      $sql .= " ORDER BY t.experience DESC";
elseif ($sort === 'rating_high') $sql .= " ORDER BY t.rating DESC";
elseif ($sort === 'rating_low')  $sql .= " ORDER BY t.rating ASC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$tutors = $stmt->get_result();
$stmt->close();

// ============================================================
//  TOP TUTORS (dashboard widget)
// ============================================================
// ============================================================
//  SUBMIT REVIEW
// ============================================================
$reviewSuccess = "";
$reviewError   = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $review_booking_id = intval($_POST['review_booking_id'] ?? 0);
    $review_tutor_id   = intval($_POST['review_tutor_id']   ?? 0);
    $rating            = intval($_POST['rating']             ?? 0);
    $comment           = trim($_POST['comment']              ?? '');

    if ($rating < 1 || $rating > 5) {
        $reviewError = "Please select a rating between 1 and 5 stars.";
    } elseif ($comment === '') {
        $reviewError = "Please write a comment before submitting.";
    } else {
        // Verify booking belongs to this student and is Completed
        $chk = $conn->prepare("
            SELECT id FROM bookings
            WHERE id = ? AND student_id = ? AND tutor_id = ? AND status = 'Completed'
        ");
        $chk->bind_param("iii", $review_booking_id, $student_id, $review_tutor_id);
        $chk->execute();
        $chk->store_result();

        if ($chk->num_rows === 0) {
            $reviewError = "Invalid booking or session not completed.";
        } else {
            // Check if already reviewed
            $dup = $conn->prepare("SELECT id FROM reviews WHERE booking_id = ? AND student_id = ?");
            $dup->bind_param("ii", $review_booking_id, $student_id);
            $dup->execute();
            $dup->store_result();

            if ($dup->num_rows > 0) {
                $reviewError = "You have already submitted a review for this session.";
            } else {
                $ins = $conn->prepare("
                    INSERT INTO reviews (student_id, tutor_id, booking_id, rating, comment)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $ins->bind_param("iiiis", $student_id, $review_tutor_id, $review_booking_id, $rating, $comment);
                if ($ins->execute()) {

                    // Recalculate and update tutors.rating from all reviews
                    $conn->query("
                        UPDATE tutors
                        SET rating = (
                            SELECT ROUND(AVG(rating), 1)
                            FROM reviews
                            WHERE tutor_id = $review_tutor_id
                        )
                        WHERE id = $review_tutor_id
                    ");

                    $reviewSuccess = "Your review has been submitted successfully!";
                } else {
                    $reviewError = "Failed to submit review. Please try again.";
                }
                $ins->close();
            }
            $dup->close();
        }
        $chk->close();
    }
}

// Fetch completed bookings for review page
$completed_stmt = $conn->prepare("
    SELECT bk.id AS booking_id, bk.created_at, bk.tutor_id,
           u.name   AS tutor_name,
           sub.name AS subject_name,
           rv.id    AS review_id,
           rv.rating, rv.comment
    FROM bookings bk
    JOIN tutors   t   ON t.id  = bk.tutor_id
    JOIN users    u   ON u.id  = t.user_id
    LEFT JOIN subjects sub ON sub.id = bk.subject_id
    LEFT JOIN reviews  rv  ON rv.booking_id = bk.id AND rv.student_id = ?
    WHERE bk.student_id = ? AND bk.status = 'Completed'
    ORDER BY bk.created_at DESC
");
$completed_stmt->bind_param("ii", $student_id, $student_id);
$completed_stmt->execute();
$completedBookingsForReview = $completed_stmt->get_result();
$completed_stmt->close();

$topTutors = $conn->query("
    SELECT u.name, IFNULL(COUNT(b.id), 0) AS total
    FROM tutors t
    JOIN users u ON t.user_id = u.id
    LEFT JOIN bookings b ON b.tutor_id = t.id
    WHERE t.is_verified = 1
    GROUP BY t.id
    ORDER BY total DESC
    LIMIT 5
");

// Subjects for booking modal
$modalSubjects = $conn->query("SELECT id, name FROM subjects WHERE is_active = 1 ORDER BY name ASC");

// Misc vars
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