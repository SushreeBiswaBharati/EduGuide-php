<?php
require_once '../middleware/auth.php';
requireRole('admin');
require_once '../database/dbconnection.php';

$page    = $_GET['page'] ?? 'dashboard';
$message = '';

// =====================================================
// MANAGE TUTORS — Verify / Revoke
// =====================================================
if ($page === 'manage_tutor' && isset($_POST['tutor_action'])) {
    $tutor_id = intval($_POST['tutor_id']);

    // Fetch tutor name + email for notification
    $tInfo = $conn->prepare("
        SELECT u.name, u.email
        FROM tutors t
        JOIN users u ON u.id = t.user_id
        WHERE t.id = ?
    ");
    $tInfo->bind_param("i", $tutor_id);
    $tInfo->execute();
    $tRow = $tInfo->get_result()->fetch_assoc();
    $tInfo->close();

    $tutorName  = $tRow['name']  ?? 'Tutor';
    $tutorEmail = $tRow['email'] ?? '';

    if ($_POST['tutor_action'] === 'verify') {
        $conn->query("UPDATE tutors SET is_verified = 1 WHERE id = $tutor_id");
        $message = "✅ Tutor <strong>" . htmlspecialchars($tutorName) . "</strong> has been verified successfully.";

        // Email to tutor — profile approved
        if ($tutorEmail !== '') {
            $subject  = "🎉 Your EduGuide Tutor Profile Has Been Approved!";
            $body     = "Dear {$tutorName},\r\n\r\n";
            $body    .= "Congratulations! Your tutor profile on EduGuide has been reviewed and approved by our admin team.\r\n\r\n";
            $body    .= "You can now log in to your Tutor Dashboard and start receiving student booking requests.\r\n\r\n";
            $body    .= "Dashboard: http://localhost/EduGuide-php/controllers/TutorDashboardController.php\r\n\r\n";
            $body    .= "If you have any questions, feel free to contact us.\r\n\r\n";
            $body    .= "Best regards,\r\nEduGuide Admin Team\r\n";
            $headers  = "From: no-reply@eduguide.com\r\n";
            $headers .= "Reply-To: no-reply@eduguide.com\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();
            @mail($tutorEmail, $subject, $body, $headers);
        }
    }

    if ($_POST['tutor_action'] === 'revoke') {
        $conn->query("UPDATE tutors SET is_verified = 0 WHERE id = $tutor_id");
        $message = "⚠️ Tutor <strong>" . htmlspecialchars($tutorName) . "</strong>'s access has been revoked.";

        // Email to tutor — profile revoked
        if ($tutorEmail !== '') {
            $subject  = "EduGuide — Tutor Profile Access Revoked";
            $body     = "Dear {$tutorName},\r\n\r\n";
            $body    .= "We're writing to inform you that your tutor profile on EduGuide has been temporarily revoked by our admin team.\r\n\r\n";
            $body    .= "This may be due to a policy review or incomplete information. Please contact our support team for more details.\r\n\r\n";
            $body    .= "Best regards,\r\nEduGuide Admin Team\r\n";
            $headers  = "From: no-reply@eduguide.com\r\n";
            $headers .= "Reply-To: no-reply@eduguide.com\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion();
            @mail($tutorEmail, $subject, $body, $headers);
        }
    }
}

// =====================================================
// MANAGE STUDENTS — Delete
// =====================================================
if ($page === 'manage_student' && isset($_POST['delete_student_id'])) {
    $sid = intval($_POST['delete_student_id']);

    $stmt = $conn->prepare("SELECT user_id FROM students WHERE id = ?");
    $stmt->bind_param("i", $sid);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($row) {
        $uid = intval($row['user_id']);
        $conn->query("DELETE FROM students WHERE id = $sid");
        $conn->query("DELETE FROM users WHERE id = $uid");
        $message = "Student deleted successfully.";
    }
}

// =====================================================
// MANAGE DROPDOWNS — Add / Delete (classes, boards, exams, subjects)
// =====================================================
$allowedTables = [
    'class'   => 'classes',
    'board'   => 'boards',
    'exam'    => 'exams',
    'subject' => 'subjects'
];

if ($page === 'dropdown') {

    // ADD
    if (isset($_POST['add_type'], $_POST['add_value'])) {
        $type  = trim($_POST['add_type']);
        $value = trim($_POST['add_value']);

        if ($value !== '' && array_key_exists($type, $allowedTables)) {
            $table = $allowedTables[$type];

            // Check duplicate
            $dup = $conn->prepare("SELECT id FROM $table WHERE name = ?");
            $dup->bind_param("s", $value);
            $dup->execute();
            $dup->store_result();

            if ($dup->num_rows > 0) {
                $message = "Error: '$value' already exists in $table.";
            } else {
                $ins = $conn->prepare("INSERT INTO $table (name) VALUES (?)");
                $ins->bind_param("s", $value);
                $ins->execute();
                $ins->close();
                $message = "'$value' added successfully.";
            }
            $dup->close();
        }
    }

    // DELETE
    if (isset($_POST['delete_id'], $_POST['delete_type'])) {
        $del_id   = intval($_POST['delete_id']);
        $del_type = trim($_POST['delete_type']);

        if (array_key_exists($del_type, $allowedTables)) {
            $table = $allowedTables[$del_type];

            // Subjects — check if assigned to a tutor
            if ($del_type === 'subject') {
                $chk = $conn->prepare("SELECT COUNT(*) AS c FROM tutor_subjects WHERE subject_id = ?");
                $chk->bind_param("i", $del_id);
                $chk->execute();
                $used = $chk->get_result()->fetch_assoc()['c'];
                $chk->close();

                if ($used > 0) {
                    $message = "Cannot delete: this subject is assigned to $used tutor(s).";
                } else {
                    $conn->query("DELETE FROM $table WHERE id = $del_id");
                    $message = "Deleted successfully.";
                }
            } else {
                $conn->query("DELETE FROM $table WHERE id = $del_id");
                $message = "Deleted successfully.";
            }
        }
    }
}

// =====================================================
// COMPLAINTS — Resolve / Delete
// =====================================================
if ($page === 'complaint') {

    if (isset($_POST['resolve_id'])) {
        $rid = intval($_POST['resolve_id']);
        $conn->query("UPDATE complaints SET status = 'Resolved' WHERE id = $rid");
        $message = "Complaint marked as resolved.";
    }

    if (isset($_POST['delete_complaint_id'])) {
        $did = intval($_POST['delete_complaint_id']);
        $conn->query("DELETE FROM complaints WHERE id = $did");
        $message = "Complaint deleted.";
    }
}

// =====================================================
// FETCH DATA FOR EACH PAGE
// =====================================================

// --- Dashboard counts ---
$totalTutors    = $conn->query("SELECT COUNT(*) AS c FROM tutors")->fetch_assoc()['c'];
$totalStudents  = $conn->query("SELECT COUNT(*) AS c FROM students")->fetch_assoc()['c'];
$totalBookings  = $conn->query("SELECT COUNT(*) AS c FROM bookings")->fetch_assoc()['c'];
$totalComplaints= $conn->query("SELECT COUNT(*) AS c FROM complaints")->fetch_assoc()['c'];

// Bookings per month (last 3 months)
$monthlyBookings = [];
for ($i = 2; $i >= 0; $i--) {
    $row = $conn->query("
        SELECT DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL $i MONTH), '%b %Y') AS month_label,
               COUNT(*) AS total
        FROM bookings
        WHERE YEAR(created_at)  = YEAR(DATE_SUB(CURDATE(),  INTERVAL $i MONTH))
          AND MONTH(created_at) = MONTH(DATE_SUB(CURDATE(), INTERVAL $i MONTH))
    ")->fetch_assoc();
    $monthlyBookings[] = $row;
}
$maxMonthlyBookings = max(array_column($monthlyBookings, 'total') ?: [1]);

// Tutor verified vs pending
$totalVerifiedTutors = $conn->query("SELECT COUNT(*) AS c FROM tutors WHERE is_verified = 1")->fetch_assoc()['c'];
$totalPendingTutors  = $conn->query("SELECT COUNT(*) AS c FROM tutors WHERE is_verified = 0")->fetch_assoc()['c'];
$totalTutorsForChart = $totalVerifiedTutors + $totalPendingTutors;
$verifiedPct = $totalTutorsForChart > 0 ? round(($totalVerifiedTutors / $totalTutorsForChart) * 100) : 0;
$pendingPct  = $totalTutorsForChart > 0 ? round(($totalPendingTutors  / $totalTutorsForChart) * 100) : 0;

$topTutors = $conn->query("
    SELECT u.name, COUNT(b.id) AS total
    FROM tutors t
    JOIN users u ON u.id = t.user_id
    LEFT JOIN bookings b ON b.tutor_id = t.id
    GROUP BY t.id
    ORDER BY total DESC
    LIMIT 5
");

// --- Manage Tutors ---
$search_t = trim($_GET['search'] ?? '');
$status_t = $_GET['status'] ?? '';
$sort_t   = $_GET['sort']   ?? '';

$tutorQuery = "
    SELECT t.id, u.name, u.email,
           COALESCE(t.phone, '') AS phone,
           COALESCE(t.address, '') AS address,
           COALESCE(t.experience, 0) AS experience,
           ROUND(COALESCE(AVG(rv.rating), 0), 1) AS rating,
           COUNT(rv.id) AS review_count,
           t.gender, t.qualification, t.is_verified, t.created_at,
           GROUP_CONCAT(DISTINCT s.name ORDER BY s.name SEPARATOR ', ') AS subjects
    FROM tutors t
    JOIN users u ON u.id = t.user_id
    LEFT JOIN tutor_subjects ts ON ts.tutor_id = t.id
    LEFT JOIN subjects s ON s.id = ts.subject_id
    LEFT JOIN reviews rv ON rv.tutor_id = t.id
    WHERE 1
";

if ($status_t !== '') {
    $tutorQuery .= " AND t.is_verified = " . intval($status_t);
}

$tutorQuery .= " GROUP BY t.id, u.name, u.email, t.phone, t.address,
                           t.experience, t.gender, t.qualification,
                           t.is_verified, t.created_at ";

if ($search_t !== '') {
    $safe        = $conn->real_escape_string($search_t);
    $tutorQuery .= " HAVING (u.name LIKE '%$safe%' OR subjects LIKE '%$safe%' OR t.address LIKE '%$safe%') ";
}

if ($sort_t === 'rating')     $tutorQuery .= " ORDER BY rating DESC";
elseif ($sort_t === 'exp')    $tutorQuery .= " ORDER BY t.experience DESC";
else                          $tutorQuery .= " ORDER BY t.id DESC";

$tutors = $conn->query($tutorQuery);

// --- Manage Students ---
$students = $conn->query("
    SELECT s.id, u.name, u.email
    FROM students s
    JOIN users u ON u.id = s.user_id
    ORDER BY s.id DESC
");

// --- Bookings ---
$bookings = $conn->query("
    SELECT b.id, b.status, b.created_at, b.requirement, b.duration_months,
           su.name AS student_name,
           tu.name AS tutor_name,
           sub.name AS subject_name
    FROM bookings b
    JOIN students st ON st.id = b.student_id
    JOIN users su ON su.id = st.user_id
    JOIN tutors t ON t.id = b.tutor_id
    JOIN users tu ON tu.id = t.user_id
    LEFT JOIN subjects sub ON sub.id = b.subject_id
    ORDER BY b.id DESC
");

// --- Dropdown lists ---
$dd_classes  = $conn->query("SELECT id, name FROM classes  ORDER BY name ASC");
$dd_boards   = $conn->query("SELECT id, name FROM boards   ORDER BY name ASC");
$dd_exams    = $conn->query("SELECT id, name FROM exams    ORDER BY name ASC");
$dd_subjects = $conn->query("SELECT id, name FROM subjects ORDER BY name ASC");

// --- Complaints ---
$complaints = $conn->query("
    SELECT c.id, c.subject, c.message, c.status, c.created_at,
           u.name AS submitted_by
    FROM complaints c
    JOIN users u ON u.id = c.user_id
    ORDER BY c.id DESC
");

require_once '../views/admin/adminDashboard.php';
?>