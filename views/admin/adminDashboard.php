<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard – EduGuide</title>
    <link rel="stylesheet" href="/EduGuide-php/assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/EduGuide-php/assets/css/dashboard.css?v=1.1">
</head>
<body>
<div class="d-flex p-3 gap-3 vh-100">

    <!-- ===================== SIDEBAR ===================== -->
    <div class="sidebar d-flex flex-column justify-content-between p-0 rounded-4" id="sidebar">

        <div class="p-3 border-bottom border-white border-opacity-25 d-flex align-items-center justify-content-between">
            <div>
                <div class="fw-bold fs-5 brand-title">EduGuide</div>
                <small class="brand-subtitle" style="opacity:.75;">Admin Panel</small>
            </div>
            <button class="hamburger-btn" id="toggleBtn">
                <img src="/EduGuide-php/assets/icons/list.svg" alt="menu">
            </button>
        </div>

        <nav class="flex-grow-1 px-3 py-3 d-flex flex-column gap-2 fw-semibold">
            <a href="?page=dashboard"      class="nav-link"><small>🏡</small> <span>Home</span></a>
            <a href="?page=manage_tutor"   class="nav-link"><small>🧑🏻‍🏫</small> <span>Manage Tutors</span></a>
            <a href="?page=manage_student" class="nav-link"><small>🧑🏻‍🎓</small> <span>Manage Students</span></a>
            <a href="?page=booking"        class="nav-link"><small>📅</small> <span>View Bookings</span></a>
            <a href="?page=dropdown"       class="nav-link"><small>⚙️</small> <span>Academic Setings</span></a>
            <a href="?page=complaint"      class="nav-link"><small>📢</small> <span>Handle Complaints</span></a>
        </nav>

        <div class="pb-3 px-2 mb-3 d-flex justify-content-center">
            <a href="/EduGuide-php/views/auth/logout.php"
               class="logout nav-link fw-semibold btn btn-sm shadow w-75 rounded"
               onclick="return confirm('Are you sure you want to logout?')">
                <small style="font-size:larger;">👉</small><span class="logout-span">Logout</span>
            </a>
        </div>
    </div>

    <!-- ===================== MAIN CONTENT ===================== -->
    <div class="flex-grow-1 main-content p-4 rounded-4 shadow-sm" style="overflow-y:auto;">

        <?php if (!empty($message)): ?>
            <div class="alert alert-info alert-dismissible fade show py-2 mb-3">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- ==================== DASHBOARD ==================== -->
        <?php if ($page === 'dashboard'): ?>

            <div class="mb-4 greet-bar rounded-4 p-4 text-white">
                <span><?php echo date('l, F j, Y'); ?></span>
                <h4 class="fw-bold mb-1">Welcome Back, Admin! 👑</h4>
                <p class="fst-italic mb-0">"You don't just manage users — you shape the platform."</p>
            </div>

            <!-- Stat Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-6">
                    <div class="cards card1 text-center px-2 py-3">
                        <div class="fw-semibold">Total Tutors</div>
                        <div class="fw-bold fs-4"><?php echo $totalTutors; ?></div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="cards card2 text-center px-2 py-3">
                        <div class="fw-semibold">Total Students</div>
                        <div class="fw-bold fs-4"><?php echo $totalStudents; ?></div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="cards card3 text-center px-2 py-3">
                        <div class="fw-semibold">Bookings</div>
                        <div class="fw-bold fs-4"><?php echo $totalBookings; ?></div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="cards card4 text-center px-2 py-3">
                        <div class="fw-semibold">Complaints</div>
                        <div class="fw-bold fs-4"><?php echo $totalComplaints; ?></div>
                    </div>
                </div>
            </div>

            <!-- Growth Charts -->
            <div class="row g-3 mb-4">

                <!-- Monthly Bookings Bar Chart -->
                <div class="col-md-6">
                    <div class="card p-3 h-100">
                        <h6 class="fw-bold text-center mb-3">📅 Bookings — Last 3 Months</h6>
                        <?php foreach ($monthlyBookings as $mb):
                            $barWidth = $maxMonthlyBookings > 0
                                ? round(($mb['total'] / $maxMonthlyBookings) * 100)
                                : 0;
                        ?>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div style="width:68px; font-size:.78rem; color:#555; flex-shrink:0;">
                                    <?php echo $mb['month_label']; ?>
                                </div>
                                <div class="progress flex-grow-1" style="height:20px; border-radius:6px;">
                                    <div class="progress-bar progress-bar-striped"
                                         style="width:<?php echo $barWidth; ?>%; background:linear-gradient(90deg,#5c8fdc,#0b48a4); border-radius:6px; font-size:.72rem; line-height:20px;">
                                        <?php if ($mb['total'] > 0) echo $mb['total']; ?>
                                    </div>
                                </div>
                                <div style="width:22px; text-align:right; font-size:.8rem; font-weight:600; color:#0b48a4; flex-shrink:0;">
                                    <?php echo $mb['total']; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <small class="text-muted mt-2 d-block" style="font-size:.75rem;">
                            Total: <strong><?php echo array_sum(array_column($monthlyBookings, 'total')); ?></strong> bookings in 3 months
                        </small>
                    </div>
                </div>

                <!-- Tutor Verified vs Pending -->
                <div class="col-md-6">
                    <div class="card p-3 h-100">
                        <h6 class="fw-bold text-center mb-3">🧑🏻‍🏫 Tutor Verification Status</h6>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="fw-semibold text-success">✔ Verified</small>
                                <small class="fw-bold"><?php echo $totalVerifiedTutors; ?> tutors (<?php echo $verifiedPct; ?>%)</small>
                            </div>
                            <div class="progress" style="height:22px; border-radius:8px;">
                                <div class="progress-bar progress-bar-striped"
                                    style="width:<?php echo $verifiedPct; ?>%; background:linear-gradient(90deg,#198754,#20c997); border-radius:8px; font-size:.78rem; line-height:22px;">
                                    <?php if ($verifiedPct > 10) echo $verifiedPct . '%'; ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="fw-semibold text-warning">⏳ Pending</small>
                                <small class="fw-bold"><?php echo $totalPendingTutors; ?> tutors (<?php echo $pendingPct; ?>%)</small>
                            </div>
                            <div class="progress" style="height:22px; border-radius:8px;">
                                <div class="progress-bar progress-bar-striped"
                                     style="width:<?php echo $pendingPct; ?>%;
                                            background:linear-gradient(90deg,#ffc107,#fd7e14);
                                            border-radius:8px; font-size:.78rem; line-height:22px; color:#333;">
                                    <?php if ($pendingPct > 10) echo $pendingPct . '%'; ?>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                            <div class="text-center py-2 rounded-3 flex-fill" style="background:#d1e7dd; min-width:70px;">
                                <div class="fw-bold fs-5 text-success"><?php echo $totalVerifiedTutors; ?></div>
                                <div style="font-size:.75rem; color:#0f5132;">Verified</div>
                            </div>
                            <div class="text-center py-2 rounded-3 flex-fill" style="background:#fff3cd; min-width:70px;">
                                <div class="fw-bold fs-5" style="color:#664d03;"><?php echo $totalPendingTutors; ?></div>
                                <div style="font-size:.75rem; color:#664d03;">Pending</div>
                            </div>
                            <div class="text-center py-2 rounded-3 flex-fill" style="background:#e0f0ff; min-width:70px;">
                                <div class="fw-bold fs-5" style="color:#0b48a4;"><?php echo $totalTutorsForChart; ?></div>
                                <div style="font-size:.75rem; color:#0b48a4;">Total</div>
                            </div>
                        </div>

                        <?php if ($totalPendingTutors > 0): ?>
                            <div class="text-center mt-3">
                                <a href="?page=manage_tutor&status=0"
                                   class="btn btn-sm btn-warning fw-semibold px-3">
                                    View <?php echo $totalPendingTutors; ?> Pending →
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Top Tutors -->
            <div class="card p-3">
                <h6 class="fw-bold mb-2">🏆 Top Tutors by Bookings</h6>
                <div class="table-responsive">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-dark">
                        <tr><th>#</th><th>Name</th><th>Total Bookings</th></tr>
                    </thead>
                    <tbody>
                        <?php $n = 1; while ($t = $topTutors->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $n++; ?></td>
                                <td><?php echo htmlspecialchars($t['name']); ?></td>
                                <td><span class="badge bg-primary"><?php echo $t['total']; ?></span></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                </div>
            </div>

        <!-- ==================== MANAGE TUTORS ==================== -->
        <?php elseif ($page === 'manage_tutor'): ?>

            <div class="mb-3 greet-bar rounded-4 p-3 text-white">
                <h5 class="fw-bold mb-0">🧑🏻‍🏫 Manage Tutors</h5>
            </div>

            <!-- Search / Filter -->
            <form method="GET" action="AdminDashboardController.php" class="row g-2 mb-3">
                <input type="hidden" name="page" value="manage_tutor">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control"
                           placeholder="Search name, subject, address..."
                           value="<?php echo htmlspecialchars($search_t); ?>">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="0" <?php echo $status_t === '0' ? 'selected' : ''; ?>>Pending</option>
                        <option value="1" <?php echo $status_t === '1' ? 'selected' : ''; ?>>Verified</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort" class="form-select">
                        <option value="">Sort By</option>
                        <option value="rating" <?php echo $sort_t === 'rating' ? 'selected' : ''; ?>>Highest Rating</option>
                        <option value="exp"    <?php echo $sort_t === 'exp'    ? 'selected' : ''; ?>>Experience</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-success w-100">🔍 Search</button>
                </div>
                <div class="col-md-2">
                    <a href="?page=manage_tutor" class="btn btn-outline-secondary w-100">✖ Clear</a>
                </div>
            </form>

            <!-- Summary Badges -->
            <?php
            $totalAll      = $conn->query("SELECT COUNT(*) AS c FROM tutors")->fetch_assoc()['c'];
            $totalVerified = $conn->query("SELECT COUNT(*) AS c FROM tutors WHERE is_verified = 1")->fetch_assoc()['c'];
            $totalPending  = $conn->query("SELECT COUNT(*) AS c FROM tutors WHERE is_verified = 0")->fetch_assoc()['c'];
            ?>
            <div class="d-flex gap-2 mb-3 flex-wrap">
                <span class="badge bg-secondary px-3 py-2">Total: <?php echo $totalAll; ?></span>
                <span class="badge bg-success px-3 py-2">Verified: <?php echo $totalVerified; ?></span>
                <span class="badge bg-warning text-dark px-3 py-2">Pending: <?php echo $totalPending; ?></span>
                <?php if ($tutors->num_rows != $totalAll): ?>
                    <span class="badge bg-info text-dark px-3 py-2">Showing: <?php echo $tutors->num_rows; ?></span>
                <?php endif; ?>
            </div>

            <!-- Tutors Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle bg-white shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-white bg-black">#</th>
                            <th class="text-white bg-black">Name</th>
                            <th class="text-white bg-black">Email</th>
                            <th class="text-white bg-black">Phone</th>
                            <th class="text-white bg-black">Subjects</th>
                            <th class="text-white bg-black">Qualification</th>
                            <th class="text-white bg-black">Exp (yrs)</th>
                            <th class="text-white bg-black">Rating</th>
                            <th class="text-white bg-black">Status</th>
                            <th class="text-white bg-black">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($tutors && $tutors->num_rows > 0): ?>
                            <?php $sno = 1; while ($t = $tutors->fetch_assoc()): ?>
                                <?php $isVerified = intval($t['is_verified']); ?>
                                <tr>
                                    <td><?php echo $sno++; ?></td>
                                    <td class="fw-semibold"><?php echo htmlspecialchars($t['name']); ?></td>
                                    <td><?php echo htmlspecialchars($t['email']); ?></td>
                                    <td><?php echo htmlspecialchars($t['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($t['subjects'] ?? '—'); ?></td>
                                    <td><?php echo htmlspecialchars($t['qualification'] ?? '—'); ?></td>
                                    <td class="text-center"><?php echo $t['experience']; ?></td>
                                    <td class="text-center">
                                        <?php
                                        $rat = floatval($t['rating']);
                                        $cnt = intval($t['review_count'] ?? 0);
                                        ?>
                                        <div>
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <span style="color:<?php echo $i <= round($rat) ? '#ffc107' : '#dee2e6'; ?>; font-size:.85rem;">★</span>
                                            <?php endfor; ?>
                                        </div>
                                        <small style="font-size:.75rem; color:#555;">
                                            <?php echo number_format($rat, 1); ?>
                                            (<?php echo $cnt; ?> review<?php echo $cnt != 1 ? 's' : ''; ?>)
                                        </small>
                                    </td>
                                    <td>
                                        <?php if ($isVerified === 1): ?>
                                            <span class="badge bg-success">Verified</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($isVerified === 0): ?>
                                            <form method="POST" action="?page=manage_tutor" style="display:inline;">
                                                <input type="hidden" name="tutor_id" value="<?php echo $t['id']; ?>">
                                                <input type="hidden" name="tutor_action" value="verify">
                                                <button type="submit" class="btn btn-success btn-sm"
                                                    onclick="return confirm('Verify this tutor?')">✔ Verify</button>
                                            </form>
                                        <?php else: ?>
                                            <form method="POST" action="?page=manage_tutor" style="display:inline;">
                                                <input type="hidden" name="tutor_id" value="<?php echo $t['id']; ?>">
                                                <input type="hidden" name="tutor_action" value="revoke">
                                                <button type="submit" class="btn btn-warning btn-sm text-dark"
                                                    onclick="return confirm('Revoke this tutor\'s access?')">✖ Revoke</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">No tutors found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        <!-- ==================== MANAGE STUDENTS ==================== -->
        <?php elseif ($page === 'manage_student'): ?>

            <div class="mb-3 greet-bar rounded-4 p-3 text-white">
                <h5 class="fw-bold mb-0">🧑🏻‍🎓 Manage Students</h5>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle bg-white shadow-sm">
                    <thead class="table-dark">
                        <tr><th>#</th><th>Name</th><th>Email</th><th class="text-center">Action</th></tr>
                    </thead>
                    <tbody>
                        <?php if ($students && $students->num_rows > 0): ?>
                            <?php $sno = 1; while ($s = $students->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $sno++; ?></td>
                                    <td class="fw-semibold"><?php echo htmlspecialchars($s['name']); ?></td>
                                    <td><?php echo htmlspecialchars($s['email']); ?></td>
                                    <td class="text-center">
                                        <form method="POST" action="?page=manage_student" style="display:inline;">
                                            <input type="hidden" name="delete_student_id" value="<?php echo $s['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Delete this student permanently?')">
                                                🗑 Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No students found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        <!-- ==================== VIEW BOOKINGS ==================== -->
        <?php elseif ($page === 'booking'): ?>

            <div class="mb-3 greet-bar rounded-4 p-3 text-white">
                <h5 class="fw-bold mb-0">📅 All Bookings</h5>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle bg-white shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Tutor</th>
                            <th>Subject</th>
                            <th>Requirement</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($bookings && $bookings->num_rows > 0): ?>
                            <?php $sno = 1; while ($b = $bookings->fetch_assoc()): ?>
                                <?php
                                $badgeClass = match($b['status']) {
                                    'Confirmed' => 'bg-success',
                                    'Pending'   => 'bg-warning text-dark',
                                    'Cancelled' => 'bg-danger',
                                    'Completed' => 'bg-primary',
                                    default     => 'bg-secondary'
                                };
                                ?>
                                <tr>
                                    <td><?php echo $sno++; ?></td>
                                    <td class="fw-semibold"><?php echo htmlspecialchars($b['student_name']); ?></td>
                                    <td><?php echo htmlspecialchars($b['tutor_name']); ?></td>
                                    <td><?php echo htmlspecialchars($b['subject_name'] ?? '—'); ?></td>
                                    <td style="max-width:180px;">
                                        <?php
                                        $req = $b['requirement'];
                                        echo htmlspecialchars(strlen($req) > 60 ? substr($req, 0, 60) . '...' : $req);
                                        ?>
                                    </td>
                                    <td class="text-center"><?php echo $b['duration_months']; ?> mo</td>
                                    <td><span class="badge <?php echo $badgeClass; ?>"><?php echo $b['status']; ?></span></td>
                                    <td><?php echo date('d M Y', strtotime($b['created_at'])); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">No bookings yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        <!-- ==================== MANAGE DROPDOWNS ==================== -->
        <?php elseif ($page === 'dropdown'): ?>

            <div class="mb-3 greet-bar rounded-4 p-3 text-white">
                <h5 class="fw-bold mb-0">⚙️ Manage Dropdowns</h5>
            </div>

            <?php
            // Helper function — renders one section card
            function showSection($title, $icon, $type, $rows) {
                $totalRows = $rows ? $rows->num_rows : 0;
            ?>
            <div class="card shadow-sm mb-4">
                <div class="card-header py-2 d-flex justify-content-between align-items-center"
                     style="background:linear-gradient(90deg,#5c8fdc,#0b48a4);">
                    <span class="fw-bold text-white"><?php echo $icon . ' ' . $title; ?></span>
                    <span class="badge bg-white text-primary"><?php echo $totalRows; ?> entries</span>
                </div>
                <div class="card-body p-3">

                    <!-- Add form -->
                    <form method="POST" action="?page=dropdown" class="d-flex gap-2 mb-3">
                        <input type="hidden" name="add_type" value="<?php echo $type; ?>">
                        <input type="text" name="add_value" class="form-control form-control-sm"
                               placeholder="Enter new <?php echo strtolower($title); ?> name..." required>
                        <button type="submit" class="btn btn-success btn-sm px-3">+ Add</button>
                    </form>

                    <!-- List table -->
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>#</th><th>Name</th><th class="text-center">Action</th></tr>
                        </thead>
                        <tbody>
                            <?php if ($rows && $rows->num_rows > 0):
                                $n = 1;
                                while ($r = $rows->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $n++; ?></td>
                                    <td><?php echo htmlspecialchars($r['name']); ?></td>
                                    <td class="text-center">
                                        <form method="POST" action="?page=dropdown" style="display:inline;">
                                            <input type="hidden" name="delete_id"   value="<?php echo $r['id']; ?>">
                                            <input type="hidden" name="delete_type" value="<?php echo $type; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Delete \'<?php echo htmlspecialchars($r['name'], ENT_QUOTES); ?>\'?')">
                                                🗑 Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; else: ?>
                                <tr><td colspan="3" class="text-center text-muted py-2">No entries yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php } ?>

            <div class="row g-3">
                <div class="col-md-6"><?php showSection('Classes',  '📚', 'class',   $dd_classes);  ?></div>
                <div class="col-md-6"><?php showSection('Boards',   '🏫', 'board',   $dd_boards);   ?></div>
                <div class="col-md-6"><?php showSection('Exams',    '📝', 'exam',    $dd_exams);    ?></div>
                <div class="col-md-6"><?php showSection('Subjects', '🔬', 'subject', $dd_subjects); ?></div>
            </div>

        <!-- ==================== HANDLE COMPLAINTS ==================== -->
        <?php elseif ($page === 'complaint'): ?>

            <div class="mb-3 greet-bar rounded-4 p-3 text-white">
                <h5 class="fw-bold mb-0">📢 Handle Complaints</h5>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle bg-white shadow-sm">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-white bg-black">#</th>
                            <th class="text-white bg-black">Submitted By</th>
                            <th class="text-white bg-black">Subject</th>
                            <th class="text-white bg-black">Message</th>
                            <th class="text-white bg-black">Date</th>
                            <th class="text-white bg-black">Status</th>
                            <th class="text-white bg-black">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($complaints && $complaints->num_rows > 0): ?>
                            <?php $sno = 1; while ($c = $complaints->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $sno++; ?></td>
                                    <td class="fw-semibold"><?php echo htmlspecialchars($c['submitted_by']); ?></td>
                                    <td><?php echo htmlspecialchars($c['subject']); ?></td>
                                    <td style="max-width:200px;"><?php echo htmlspecialchars($c['message']); ?></td>
                                    <td><?php echo date('d M Y', strtotime($c['created_at'])); ?></td>
                                    <td>
                                        <?php if ($c['status'] === 'Resolved'): ?>
                                            <span class="badge bg-success">Resolved</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Open</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($c['status'] !== 'Resolved'): ?>
                                            <form method="POST" action="?page=complaint" style="display:inline;">
                                                <input type="hidden" name="resolve_id" value="<?php echo $c['id']; ?>">
                                                <button type="submit" class="btn btn-success btn-sm">✔ Resolve</button>
                                            </form>
                                        <?php endif; ?>
                                        <form method="POST" action="?page=complaint" style="display:inline;">
                                            <input type="hidden" name="delete_complaint_id" value="<?php echo $c['id']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Delete this complaint?')">🗑 Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No complaints found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        <?php endif; ?>
    </div>
</div>

<script src="/EduGuide-php/assets/bootstrap/bootstrap.bundle.min.js"></script>
<script>
    // Sidebar toggle
    const toggleBtn  = document.getElementById('toggleBtn');
    const sidebar    = document.getElementById('sidebar');
    const navSpans   = sidebar.querySelectorAll('nav .nav-link span');
    const brandTitle = sidebar.querySelector('.brand-title');
    const subTitle   = sidebar.querySelector('.brand-subtitle');
    const logoutSpan = sidebar.querySelector('.logout-span');
    let isCollapsed  = false;

    toggleBtn.addEventListener('click', function () {
        isCollapsed = !isCollapsed;
        if (isCollapsed) {
            sidebar.style.width = '100px';
            navSpans.forEach(s => s.style.display = 'none');
            brandTitle.style.display = 'none';
            subTitle.style.display   = 'none';
            logoutSpan.style.display = 'none';
        } else {
            sidebar.style.width = '300px';
            navSpans.forEach(s => s.style.display = 'inline');
            brandTitle.style.display = 'block';
            subTitle.style.display   = 'block';
            logoutSpan.style.display = 'inline';
        }
    });
</script>
</body>
</html>