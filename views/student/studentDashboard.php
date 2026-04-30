<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard – EduGuide</title>
    <link rel="stylesheet" href="/EduGuide-php/assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/EduGuide-php/assets/css/dashboard.css?v=1.1">
    <style>
        /* ── booking filter tabs ── */
        .booking-tab {
            cursor: pointer;
            user-select: none;
            transition: opacity .15s, transform .15s;
            font-size: .85rem;
            padding: 6px 16px;
            border-radius: 99px;
            font-weight: 600;
        }
        .booking-tab:hover  { opacity: .85; transform: translateY(-1px); }
        .booking-tab.active { box-shadow: 0 0 0 3px rgba(255,255,255,.6),
                                          0 0 0 5px rgba(11,72,164,.4); }
    </style>
</head>
<body>
<div class="d-flex p-3 gap-3 vh-100">

    <!-- ===================== SIDEBAR ===================== -->
    <div class="sidebar d-flex flex-column justify-content-between p-0 rounded-4" id="sidebar">

        <div class="p-3 border-bottom border-white border-opacity-25 d-flex align-items-center justify-content-between">
            <div>
                <div class="fw-bold fs-5 brand-title">EduGuide</div>
                <small class="brand-subtitle" style="opacity:.75;">Student Panel</small>
            </div>
            <button class="hamburger-btn" id="toggleBtn">
                <img src="/EduGuide-php/assets/icons/list.svg" alt="menu">
            </button>
        </div>

        <nav class="flex-grow-1 px-3 py-3 d-flex flex-column gap-2 fw-semibold">
            <a href="?page=dashboard" class="nav-link"><small>🏡</small> <span>Home</span></a>
            <a href="?page=profile"   class="nav-link"><small>👤</small> <span>My Profile</span></a>
            <a href="?page=browse"    class="nav-link"><small>🔍</small> <span>Browse Tutors</span></a>
            <a href="?page=bookings"  class="nav-link"><small>📅</small> <span>My Bookings</span></a>
            <a href="?page=reviews"   class="nav-link"><small>⭐</small> <span>My Reviews</span></a>
            <a href="?page=complaint" class="nav-link"><small>📢</small> <span>Raise Complaint</span></a>
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

        <!-- ==================== DASHBOARD ==================== -->
        <?php if ($page === 'dashboard'): ?>

            <div class="mb-4 greet-bar rounded-4 p-4 text-white">
                <span><?php echo $today; ?></span>
                <h4 class="fw-bold mb-1">Welcome Back, <?php echo htmlspecialchars($student['name'] ?? 'Student'); ?>! 🎓</h4>
                <p class="fst-italic mb-0">"Your future is created by what you do today."</p>
            </div>

            <!-- Stat Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-6">
                    <div class="cards card1 text-center px-2 py-3">
                        <div class="fw-semibold">Total Bookings</div>
                        <div class="fw-bold fs-4"><?php echo $totalBookings; ?></div>
                        <div style="font-size:.8rem;">All Requests</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="cards card2 text-center px-2 py-3">
                        <div class="fw-semibold">Pending</div>
                        <div class="fw-bold fs-4"><?php echo $pendingBookings; ?></div>
                        <div style="font-size:.8rem;">Awaiting Response</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="cards card3 text-center px-2 py-3">
                        <div class="fw-semibold">Confirmed</div>
                        <div class="fw-bold fs-4"><?php echo $confirmedBookings; ?></div>
                        <div style="font-size:.8rem;">Tutor Accepted</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="cards card4 text-center px-2 py-3">
                        <div class="fw-semibold">Completed</div>
                        <div class="fw-bold fs-4"><?php echo $completedBookings; ?></div>
                        <div style="font-size:.8rem;">Sessions Done</div>
                    </div>
                </div>
            </div>

            <!-- Top Tutors -->
            <div class="card p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0">🏆 Top Tutors</h6>
                    <a href="?page=browse" class="btn btn-sm fw-semibold px-3"
                       style="background:linear-gradient(90deg,#5c8fdc,#0b48a4); color:#fff; border:none;">
                        Browse All →
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0 align-middle">
                        <thead class="table-dark">
                            <tr><th>#</th><th>Name</th><th>Total Bookings</th></tr>
                        </thead>
                        <tbody>
                            <?php $n = 1; while ($t = $topTutors->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $n++; ?></td>
                                    <td class="fw-semibold"><?php echo htmlspecialchars($t['name']); ?></td>
                                    <td><span class="badge bg-primary"><?php echo $t['total']; ?></span></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <!-- ==================== MY PROFILE ==================== -->
        <?php elseif ($page === 'profile'): ?>

            <div class="mb-4 greet-bar rounded-4 p-3 text-white">
                <h5 class="fw-bold mb-0">👤 My Profile</h5>
                <small style="opacity:.85;">View and update your student information</small>
            </div>

            <?php if ($profileSuccess): ?>
                <div class="alert alert-success alert-dismissible fade show py-2">
                    ✅ <?php echo $profileSuccess; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if ($profileError): ?>
                <div class="alert alert-danger alert-dismissible fade show py-2">
                    ⚠️ <?php echo $profileError; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- View Profile -->
            <div id="profile-view">
                <div class="card shadow-sm p-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="card shadow-sm p-3 text-center h-100">
                                <?php
                                $image = !empty($student['profile_image'])
                                    ? "/EduGuide-php/assets/profile/" . $student['profile_image']
                                    : "/EduGuide-php/assets/default-user.png";
                                ?>
                                <img src="<?php echo $image; ?>" class="profile-img mx-auto mb-2">
                                <h6 class="fw-bold"><?php echo htmlspecialchars($student['name']); ?></h6>
                                <small class="text-muted"><?php echo htmlspecialchars($student['class_name'] ?? ''); ?></small>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card shadow-sm p-3 h-100">
                                <h6 class="fw-bold mb-3 text-center" style="color:#0b48a4;">Your Details</h6>
                                <table class="table table-sm align-middle mb-0">
                                    <tr>
                                        <td class="fw-semibold text-primary" style="width:40%;">Email</td>
                                        <td class="text-muted"><?php echo htmlspecialchars($student['email']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-primary">Gender</td>
                                        <td class="text-muted"><?php echo htmlspecialchars($student['gender']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-primary">Class</td>
                                        <td class="text-muted"><?php echo htmlspecialchars($student['class_name'] ?? '—'); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-primary">Board</td>
                                        <td class="text-muted"><?php echo htmlspecialchars($student['board_name'] ?? '—'); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-primary">Exam</td>
                                        <td class="text-muted"><?php echo htmlspecialchars($student['exam_name'] ?? '—'); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-primary">School</td>
                                        <td class="text-muted"><?php echo htmlspecialchars($student['school_name'] ?? '—'); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-primary">Parent</td>
                                        <td class="text-muted"><?php echo htmlspecialchars($student['parent_name'] ?? '—'); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-primary">Parent Phone</td>
                                        <td class="text-muted"><?php echo htmlspecialchars($student['parent_phone'] ?? '—'); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-primary">Address</td>
                                        <td class="text-muted"><?php echo htmlspecialchars($student['address'] ?? '—'); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-primary px-4" onclick="toggleEdit()">✏️ Edit Profile</button>
                    </div>
                </div>
            </div>

            <!-- Edit Profile -->
            <div id="edit-form" style="display:none;">
                <div class="card shadow-sm p-4">
                    <h6 class="fw-bold mb-3" style="color:#0b48a4;">✏️ Edit Profile</h6>
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <form method="POST" enctype="multipart/form-data">

                                <label class="form-label fw-semibold text-primary">Profile Photo</label>
                                <input type="file" name="profile_image" class="form-control mb-3" accept="image/*">

                                <label class="form-label fw-semibold text-primary">Class</label>
                                <select name="edit_class" class="form-control mb-3" required>
                                    <option value="">Select Class</option>
                                    <?php while ($c = $classes->fetch_assoc()): ?>
                                        <option value="<?php echo $c['id']; ?>"
                                            <?php echo $c['name'] == $student['class_name'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($c['name']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>

                                <label class="form-label fw-semibold text-primary">Education Board</label>
                                <select name="edit_board" class="form-control mb-3" required>
                                    <option value="">Select Board</option>
                                    <?php while ($b = $boards->fetch_assoc()): ?>
                                        <option value="<?php echo $b['id']; ?>"
                                            <?php echo $b['name'] == $student['board_name'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($b['name']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>

                                <label class="form-label fw-semibold text-primary">Exam</label>
                                <select name="edit_exam" class="form-control mb-3">
                                    <option value="">Select Exam (optional)</option>
                                    <?php while ($e = $exams->fetch_assoc()): ?>
                                        <option value="<?php echo $e['id']; ?>"
                                            <?php echo $e['name'] == $student['exam_name'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($e['name']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>

                                <label class="form-label fw-semibold text-primary">Parent's Phone</label>
                                <input type="tel" name="edit_phone" class="form-control mb-3"
                                    value="<?php echo htmlspecialchars($student['parent_phone'] ?? ''); ?>" required>

                                <label class="form-label fw-semibold text-primary">School Name</label>
                                <input type="text" name="edit_school" class="form-control mb-3"
                                    value="<?php echo htmlspecialchars($student['school_name'] ?? ''); ?>" required>

                                <label class="form-label fw-semibold text-primary">Address</label>
                                <input type="text" name="edit_address" class="form-control mb-4"
                                    value="<?php echo htmlspecialchars($student['address'] ?? ''); ?>" required>

                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-secondary flex-fill" onclick="toggleEdit()">Cancel</button>
                                    <button type="submit" class="btn btn-success flex-fill fw-semibold">💾 Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        <!-- ==================== BROWSE TUTORS ==================== -->
        <?php elseif ($page === 'browse'): ?>

            <div class="mb-4 greet-bar rounded-4 p-3 text-white d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-0">🔍 Browse Tutors</h5>
                    <small style="opacity:.85;">Find the right tutor for your learning needs</small>
                </div>
            </div>

            <?php if ($bookingSuccess): ?>
                <div class="alert alert-success alert-dismissible fade show py-2 mb-3">
                    ✅ <?php echo $bookingSuccess; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Search & Filters -->
            <form method="GET" class="mb-4">
                <input type="hidden" name="page" value="browse">
                <div class="row g-2 mb-2">
                    <div class="col-md-10">
                        <input type="text" name="search" class="form-control py-2"
                               placeholder="Search by name, subject, board or location..."
                               value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                    </div>
                    <div class="col-md-2">
                        <button class="btn w-100 fw-semibold"
                                style="background:linear-gradient(90deg,#5c8fdc,#0b48a4); color:#fff;">🔍 Search</button>
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col-md-3">
                        <select name="gender" class="form-select">
                            <option value="">All Genders</option>
                            <option value="Male"   <?php echo ($_GET['gender'] ?? '') === 'Male'   ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo ($_GET['gender'] ?? '') === 'Female' ? 'selected' : ''; ?>>Female</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="sort" class="form-select">
                            <option value="">Sort By</option>
                            <option value="asc"         <?php echo ($_GET['sort'] ?? '') === 'asc'         ? 'selected' : ''; ?>>Name A → Z</option>
                            <option value="desc"        <?php echo ($_GET['sort'] ?? '') === 'desc'        ? 'selected' : ''; ?>>Name Z → A</option>
                            <option value="exp"         <?php echo ($_GET['sort'] ?? '') === 'exp'         ? 'selected' : ''; ?>>Experience</option>
                            <option value="rating_high" <?php echo ($_GET['sort'] ?? '') === 'rating_high' ? 'selected' : ''; ?>>Rating ↑</option>
                            <option value="rating_low"  <?php echo ($_GET['sort'] ?? '') === 'rating_low'  ? 'selected' : ''; ?>>Rating ↓</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success w-100 fw-semibold">Apply</button>
                    </div>
                    <div class="col-md-2">
                        <a href="?page=browse" class="btn btn-outline-secondary w-100">✖ Clear</a>
                    </div>
                </div>
            </form>

            <!-- Tutors Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover bg-white shadow-sm align-middle text-center">
                    <thead style="background:linear-gradient(90deg,#5c8fdc,#0b48a4);">
                        <tr>
                            <th class="text-white">Photo</th>
                            <th class="text-white">Name</th>
                            <th class="text-white">Subjects</th>
                            <th class="text-white">Board</th>
                            <th class="text-white">Experience</th>
                            <th class="text-white">Gender</th>
                            <th class="text-white">Rating</th>
                            <th class="text-white">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($tutors->num_rows > 0): ?>
                            <?php while ($t = $tutors->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <?php $img = !empty($t['profile_image'])
                                            ? "/EduGuide-php/assets/profile/" . $t['profile_image']
                                            : "/EduGuide-php/assets/default-user.png"; ?>
                                        <img src="<?php echo $img; ?>" class="rounded-circle"
                                             width="48" height="48" style="object-fit:cover;">
                                    </td>
                                    <td class="fw-semibold"><?php echo htmlspecialchars($t['name']); ?></td>
                                    <td><?php echo htmlspecialchars($t['subject_names'] ?? '—'); ?></td>
                                    <td><?php echo htmlspecialchars($t['board_name'] ?? '—'); ?></td>
                                    <td><?php echo $t['experience']; ?> yrs</td>
                                    <td><?php echo htmlspecialchars($t['gender']); ?></td>
                                    <td>⭐ <?php echo number_format($t['rating'], 1); ?></td>
                                    <td>
                                        <button class="btn btn-sm fw-semibold"
                                            style="background:linear-gradient(90deg,#5c8fdc,#0b48a4); color:#fff; border:none;"
                                            onclick="openBookingModal(<?php echo $t['id']; ?>, '<?php echo addslashes(htmlspecialchars($t['name'])); ?>')">
                                            📅 Book Now
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <div style="font-size:2rem;">🔍</div>
                                    No tutors found.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        <!-- ==================== MY BOOKINGS ==================== -->
        <?php elseif ($page === 'bookings'): ?>

            <div class="mb-4 greet-bar rounded-4 p-3 text-white d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-0">📅 My Booking Sessions</h5>
                    <small style="opacity:.85;">Track all your tutor session requests in one place</small>
                </div>
                <a href="?page=browse" class="btn btn-light btn-sm fw-semibold px-3" style="color:#0b48a4;">
                    + Book New Session
                </a>
            </div>

            <!-- Stat Cards -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="cards card1 text-center px-2 py-3">
                        <div class="fw-semibold">Total</div>
                        <div class="fw-bold fs-4"><?php echo $totalBookings; ?></div>
                        <div style="font-size:.8rem;">All Requests</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="cards card2 text-center px-2 py-3">
                        <div class="fw-semibold">Pending</div>
                        <div class="fw-bold fs-4"><?php echo $pendingBookings; ?></div>
                        <div style="font-size:.8rem;">Awaiting Response</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="cards card3 text-center px-2 py-3">
                        <div class="fw-semibold">Confirmed</div>
                        <div class="fw-bold fs-4"><?php echo $confirmedBookings; ?></div>
                        <div style="font-size:.8rem;">Tutor Accepted</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="cards card4 text-center px-2 py-3">
                        <div class="fw-semibold">Completed</div>
                        <div class="fw-bold fs-4"><?php echo $completedBookings; ?></div>
                        <div style="font-size:.8rem;">Sessions Done</div>
                    </div>
                </div>
            </div>

            <!-- Alerts -->
            <?php if (!empty($cancelSuccess)): ?>
                <div class="alert alert-success alert-dismissible fade show py-2 mb-3">
                    ✅ <?php echo $cancelSuccess; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (!empty($cancelError)): ?>
                <div class="alert alert-danger alert-dismissible fade show py-2 mb-3">
                    ⚠️ <?php echo $cancelError; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Filter Tabs -->
            <div class="d-flex gap-2 flex-wrap mb-3">
                <button class="booking-tab active"
                        style="background:linear-gradient(90deg,#5c8fdc,#0b48a4); color:#fff; border:none;"
                        data-filter="all">
                    All (<?php echo $totalBookings; ?>)
                </button>
                <button class="booking-tab"
                        style="background:#fff3cd; color:#664d03; border:1px solid #ffc107;"
                        data-filter="Pending">
                    ⏳ Pending (<?php echo $pendingBookings; ?>)
                </button>
                <button class="booking-tab"
                        style="background:#d1e7dd; color:#0f5132; border:1px solid #198754;"
                        data-filter="Confirmed">
                    ✔ Confirmed (<?php echo $confirmedBookings; ?>)
                </button>
                <button class="booking-tab"
                        style="background:#cfe2ff; color:#084298; border:1px solid #0d6efd;"
                        data-filter="Completed">
                    🎓 Completed (<?php echo $completedBookings; ?>)
                </button>
                <button class="booking-tab"
                        style="background:#f8d7da; color:#842029; border:1px solid #dc3545;"
                        data-filter="Cancelled">
                    ✖ Cancelled
                </button>
            </div>

            <!-- Booking Cards -->
            <?php
            $allBookings = [];
            while ($bk = $bookings->fetch_assoc()) $allBookings[] = $bk;
            ?>

            <?php if (empty($allBookings)): ?>
                <div class="text-center py-5" style="background:rgba(255,255,255,.7); border-radius:16px; border:1.5px dashed #5c8fdc;">
                    <div style="font-size:3rem;">📭</div>
                    <h5 class="fw-bold text-primary mt-2 mb-2">No Booking Sessions Yet</h5>
                    <p class="text-muted mb-4">Start learning today by booking a tutor!</p>
                    <a href="?page=browse" class="btn fw-semibold px-4"
                       style="background:linear-gradient(90deg,#5c8fdc,#0b48a4); color:#fff; border-radius:8px;">
                        🔍 Browse Tutors
                    </a>
                </div>
            <?php else: ?>

                <div id="bookingCardsList">
                <?php foreach ($allBookings as $bk):
                    $status      = $bk['status'];
                    $borderColor = match($status) {
                        'Pending'   => '#ffc107',
                        'Confirmed' => '#198754',
                        'Completed' => '#0d6efd',
                        'Cancelled' => '#dc3545',
                        default     => '#5c8fdc'
                    };
                    $badgeClass = match($status) {
                        'Pending'   => 'bg-warning text-dark',
                        'Confirmed' => 'bg-success text-white',
                        'Completed' => 'bg-primary text-white',
                        'Cancelled' => 'bg-danger text-white',
                        default     => 'bg-secondary text-white'
                    };
                    $avatarStyle = match($status) {
                        'Pending'   => 'background:#fff3cd; color:#664d03;',
                        'Confirmed' => 'background:#d1e7dd; color:#0f5132;',
                        'Completed' => 'background:#cfe2ff; color:#084298;',
                        'Cancelled' => 'background:#f8d7da; color:#842029;',
                        default     => 'background:#e0f0ff; color:#0b48a4;'
                    };
                    $progressStep = match($status) {
                        'Pending'   => 1, 'Confirmed' => 3,
                        'Completed' => 4, default     => 0
                    };
                    $nameParts   = explode(' ', $bk['tutor_name']);
                    $initials    = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
                    $reqPreview  = strlen($bk['requirement']) > 70 ? substr($bk['requirement'], 0, 70) . '...' : $bk['requirement'];
                    $bookingDate = date('d M Y', strtotime($bk['created_at']));
                ?>
                <div class="booking-card-item mb-3" data-status="<?php echo $status; ?>">
                    <div class="p-3 rounded-4 shadow-sm"
                         style="background:rgba(255,255,255,.92); border-left:4px solid <?php echo $borderColor; ?>; border-top:.5px solid #dee2e6; border-right:.5px solid #dee2e6; border-bottom:.5px solid #dee2e6;">

                        <!-- Top Row -->
                        <div class="d-flex align-items-center gap-3 flex-wrap">
                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                 style="width:46px; height:46px; font-size:15px; <?php echo $avatarStyle; ?>">
                                <?php echo $initials; ?>
                            </div>
                            <div class="flex-grow-1" style="min-width:0;">
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="fw-bold" style="color:#0b48a4; font-size:1rem;">
                                        <?php echo htmlspecialchars($bk['tutor_name']); ?>
                                    </span>
                                    <span class="badge <?php echo $badgeClass; ?> rounded-pill" style="font-size:.72rem;">
                                        <?php echo $status; ?>
                                    </span>
                                </div>
                                <div class="text-muted" style="font-size:.82rem; margin-top:2px;">
                                    📚 <?php echo htmlspecialchars($bk['subject_name'] ?? 'N/A'); ?>
                                    &nbsp;·&nbsp;
                                    🗓 <?php echo $bk['duration_months']; ?> month<?php echo $bk['duration_months'] > 1 ? 's' : ''; ?>
                                    &nbsp;·&nbsp;
                                    📆 <?php echo $bookingDate; ?>
                                </div>
                                <div class="text-muted mt-1" style="font-size:.8rem; font-style:italic;">
                                    "<?php echo htmlspecialchars($reqPreview); ?>"
                                </div>
                            </div>
                            <button class="btn btn-sm ms-auto flex-shrink-0"
                                    style="background:rgba(92,143,220,.12); color:#0b48a4; border:none; border-radius:8px; font-size:.78rem; padding:4px 12px;"
                                    onclick="toggleCard('detail-<?php echo $bk['id']; ?>', this)">
                                View Details ▾
                            </button>
                        </div>

                        <!-- Progress Steps -->
                        <?php if ($status !== 'Cancelled'): ?>
                        <div class="mt-3 px-1">
                            <div class="d-flex align-items-center gap-1" style="font-size:.7rem;">
                                <?php
                                $steps = ['Requested', 'Reviewed', 'Confirmed', 'Completed'];
                                foreach ($steps as $i => $stepLabel):
                                    $stepNum   = $i + 1;
                                    $isDone    = $stepNum <= $progressStep;
                                    $isCurrent = $stepNum === $progressStep;
                                    $dotColor  = $isDone ? ($status === 'Completed' ? '#0d6efd' : '#198754') : '#ced4da';
                                    $textColor = $isDone ? '#0b48a4' : '#adb5bd';
                                ?>
                                <div class="text-center" style="flex:1;">
                                    <div style="width:10px; height:10px; border-radius:50%; background:<?php echo $dotColor; ?>; margin:0 auto 3px; <?php echo $isCurrent ? 'outline:2px solid '.$borderColor.'; outline-offset:2px;' : ''; ?>"></div>
                                    <div style="color:<?php echo $textColor; ?>; font-size:.65rem; white-space:nowrap;"><?php echo $stepLabel; ?></div>
                                </div>
                                <?php if ($i < 3): ?>
                                <div style="flex:2; height:2px; background:<?php echo $stepNum < $progressStep ? $dotColor : '#ced4da'; ?>; border-radius:1px; margin-bottom:13px;"></div>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="mt-2">
                            <span style="font-size:.75rem; color:#842029; background:#f8d7da; padding:2px 10px; border-radius:99px;">
                                ✖ This booking was cancelled
                            </span>
                        </div>
                        <?php endif; ?>

                        <!-- Expandable Detail -->
                        <div id="detail-<?php echo $bk['id']; ?>" style="display:none; margin-top:14px; padding-top:12px; border-top:1px dashed #dee2e6;">
                            <div class="row g-2" style="font-size:.85rem;">
                                <div class="col-md-6">
                                    <div style="background:#f8f9fa; border-radius:8px; padding:10px 14px;">
                                        <div class="fw-semibold text-muted mb-1" style="font-size:.75rem; text-transform:uppercase; letter-spacing:.05em;">Full Requirement</div>
                                        <div><?php echo nl2br(htmlspecialchars($bk['requirement'])); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div style="background:#f8f9fa; border-radius:8px; padding:10px 14px;">
                                        <div class="fw-semibold text-muted mb-1" style="font-size:.75rem; text-transform:uppercase; letter-spacing:.05em;">Session Info</div>
                                        <table style="width:100%; font-size:.82rem;">
                                            <tr>
                                                <td class="text-muted">Subject</td>
                                                <td class="fw-semibold text-end"><?php echo htmlspecialchars($bk['subject_name'] ?? 'N/A'); ?></td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Duration</td>
                                                <td class="fw-semibold text-end"><?php echo $bk['duration_months']; ?> month(s)</td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Booked On</td>
                                                <td class="fw-semibold text-end"><?php echo $bookingDate; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="text-muted">Status</td>
                                                <td class="text-end"><span class="badge <?php echo $badgeClass; ?> rounded-pill" style="font-size:.7rem;"><?php echo $status; ?></span></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2 mt-3 flex-wrap">
                                <?php if ($status === 'Pending'): ?>
                                    <form method="POST" onsubmit="return confirm('Cancel this booking?')">
                                        <input type="hidden" name="cancel_booking_id" value="<?php echo $bk['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger px-3 fw-semibold">✖ Cancel Booking</button>
                                    </form>
                                    <a href="?page=browse" class="btn btn-sm px-3 fw-semibold"
                                       style="background:rgba(92,143,220,.15); color:#0b48a4; border:1px solid #5c8fdc;">
                                        🔍 Browse Other Tutors
                                    </a>
                                <?php elseif ($status === 'Confirmed'): ?>
                                    <a href="?page=browse" class="btn btn-sm px-3 fw-semibold"
                                       style="background:linear-gradient(90deg,#5c8fdc,#0b48a4); color:#fff; border:none;">
                                        + Book Another Subject
                                    </a>
                                <?php elseif ($status === 'Cancelled'): ?>
                                    <a href="?page=browse" class="btn btn-sm px-3 fw-semibold"
                                       style="background:#fff3cd; color:#664d03; border:1px solid #ffc107;">
                                        🔄 Book Again
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>
                <?php endforeach; ?>

                <div id="noFilterResult" class="text-center py-4 text-muted" style="display:none;">
                    <div style="font-size:2.5rem;">🔍</div>
                    <p class="mt-2">No bookings match this filter.</p>
                </div>
                </div>

            <?php endif; ?>

        <!-- ==================== MY REVIEWS ==================== -->
        <?php elseif ($page === 'reviews'): ?>

            <div class="mb-4 greet-bar rounded-4 p-3 text-white d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-0">⭐ My Reviews</h5>
                    <small style="opacity:.85;">Rate your completed tutor sessions</small>
                </div>
            </div>

            <?php if ($reviewSuccess): ?>
                <div class="alert alert-success alert-dismissible fade show py-2 mb-3">
                    ✅ <?php echo $reviewSuccess; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if ($reviewError): ?>
                <div class="alert alert-danger alert-dismissible fade show py-2 mb-3">
                    ⚠️ <?php echo $reviewError; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php
            $completedBookingsForReview->data_seek(0);
            $reviewRows = [];
            while ($r = $completedBookingsForReview->fetch_assoc()) $reviewRows[] = $r;
            ?>

            <?php if (empty($reviewRows)): ?>
                <div class="text-center py-5" style="background:rgba(255,255,255,.7); border-radius:16px; border:1.5px dashed #ffc107;">
                    <div style="font-size:3rem;">⭐</div>
                    <h5 class="fw-bold mt-2 mb-2" style="color:#0b48a4;">No Completed Sessions Yet</h5>
                    <p class="text-muted mb-4">Complete a tutor session to leave a review.</p>
                    <a href="?page=browse" class="btn fw-semibold px-4"
                       style="background:linear-gradient(90deg,#5c8fdc,#0b48a4); color:#fff; border-radius:8px;">
                        🔍 Browse Tutors
                    </a>
                </div>

            <?php else: ?>

                <?php foreach ($reviewRows as $row):
                    $alreadyReviewed = !empty($row['review_id']);
                    $nameParts = explode(' ', $row['tutor_name']);
                    $initials  = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
                    $sessionDate = date('d M Y', strtotime($row['created_at']));
                ?>

                <div class="mb-3 rounded-4 shadow-sm overflow-hidden"
                     style="border-left:4px solid <?php echo $alreadyReviewed ? '#0d6efd' : '#ffc107'; ?>; border-top:.5px solid #dee2e6; border-right:.5px solid #dee2e6; border-bottom:.5px solid #dee2e6; background:#fff;">

                    <!-- Session Info Row -->
                    <div class="d-flex align-items-center gap-3 p-3 flex-wrap">
                        <!-- Avatar -->
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                             style="width:46px; height:46px; font-size:15px;
                                    background:<?php echo $alreadyReviewed ? '#cfe2ff' : '#fff3cd'; ?>;
                                    color:<?php echo $alreadyReviewed ? '#084298' : '#664d03'; ?>;">
                            <?php echo $initials; ?>
                        </div>

                        <div class="flex-grow-1" style="min-width:0;">
                            <div class="fw-bold" style="color:#0b48a4; font-size:1rem;">
                                <?php echo htmlspecialchars($row['tutor_name']); ?>
                            </div>
                            <div class="text-muted" style="font-size:.82rem;">
                                📚 <?php echo htmlspecialchars($row['subject_name'] ?? 'N/A'); ?>
                                &nbsp;·&nbsp; 📆 <?php echo $sessionDate; ?>
                            </div>
                        </div>

                        <div class="flex-shrink-0">
                            <?php if ($alreadyReviewed): ?>
                                <span class="badge bg-primary rounded-pill">✔ Reviewed</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark rounded-pill">⭐ Pending Review</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Already reviewed — show the submitted review -->
                    <?php if ($alreadyReviewed): ?>
                        <div class="mx-3 mb-3 p-3 rounded-3" style="background:#f0f6ff; border:1px solid #cfe2ff;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <span style="font-size:1.2rem; color:<?php echo $i <= $row['rating'] ? '#ffc107' : '#dee2e6'; ?>;">★</span>
                                    <?php endfor; ?>
                                    <span class="ms-1 fw-semibold" style="color:#0b48a4; font-size:.85rem;">
                                        <?php echo $row['rating']; ?>/5
                                    </span>
                                </div>
                            </div>
                            <p class="text-muted mb-0" style="font-size:.88rem; font-style:italic;">
                                "<?php echo htmlspecialchars($row['comment']); ?>"
                            </p>
                        </div>

                    <!-- Not yet reviewed — show the review form -->
                    <?php else: ?>
                        <div class="mx-3 mb-3 p-3 rounded-3" style="background:#fffbf0; border:1px solid #ffe8a1;">
                            <div class="fw-semibold mb-2" style="color:#664d03; font-size:.88rem;">
                                📝 Leave your review for this session
                            </div>
                            <form method="POST">
                                <input type="hidden" name="submit_review"     value="1">
                                <input type="hidden" name="review_booking_id" value="<?php echo $row['booking_id']; ?>">
                                <input type="hidden" name="review_tutor_id"   value="<?php echo $row['tutor_id']; ?>">

                                <!-- Star Rating -->
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <span class="fw-semibold text-primary" style="font-size:.88rem;">Rating:</span>
                                    <div class="star-group" id="stars-<?php echo $row['booking_id']; ?>">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span class="star"
                                                  data-val="<?php echo $i; ?>"
                                                  data-group="<?php echo $row['booking_id']; ?>"
                                                  onclick="setRating(<?php echo $row['booking_id']; ?>, <?php echo $i; ?>)"
                                                  style="font-size:1.6rem; cursor:pointer; color:#dee2e6; transition:color .1s;">★</span>
                                        <?php endfor; ?>
                                        <input type="hidden" name="rating" id="rating-<?php echo $row['booking_id']; ?>" value="0">
                                        <small class="text-muted ms-1" id="ratingLabel-<?php echo $row['booking_id']; ?>" style="font-size:.8rem;"></small>
                                    </div>
                                </div>

                                <!-- Comment -->
                                <textarea name="comment" class="form-control mb-3" rows="2"
                                    placeholder="Share your experience with this tutor..."
                                    required style="font-size:.88rem; resize:none;"></textarea>

                                <button type="submit" class="btn btn-sm fw-semibold px-4"
                                        style="background:linear-gradient(90deg,#5c8fdc,#0b48a4); color:#fff; border:none; border-radius:8px;">
                                    ✉️ Submit Review
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>

                </div>
                <?php endforeach; ?>

            <?php endif; ?>

            <!-- Star rating JS -->
            <script>
                const ratingLabels = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];

                function setRating(groupId, val) {
                    document.getElementById('rating-' + groupId).value = val;
                    document.getElementById('ratingLabel-' + groupId).textContent = ratingLabels[val];

                    const stars = document.querySelectorAll('.star[data-group="' + groupId + '"]');
                    stars.forEach(function(s) {
                        s.style.color = parseInt(s.dataset.val) <= val ? '#ffc107' : '#dee2e6';
                    });
                }

                // Hover effect
                document.querySelectorAll('.star').forEach(function(star) {
                    star.addEventListener('mouseenter', function() {
                        const groupId = this.dataset.group;
                        const val     = parseInt(this.dataset.val);
                        document.querySelectorAll('.star[data-group="' + groupId + '"]').forEach(function(s) {
                            s.style.color = parseInt(s.dataset.val) <= val ? '#ffc107' : '#dee2e6';
                        });
                    });
                    star.addEventListener('mouseleave', function() {
                        const groupId = this.dataset.group;
                        const current = parseInt(document.getElementById('rating-' + groupId).value);
                        document.querySelectorAll('.star[data-group="' + groupId + '"]').forEach(function(s) {
                            s.style.color = parseInt(s.dataset.val) <= current ? '#ffc107' : '#dee2e6';
                        });
                    });
                });

                <?php if ($reviewError): ?>
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                <?php endif; ?>
            </script>

        <!-- ==================== RAISE COMPLAINT ==================== -->
        <?php elseif ($page === 'complaint'): ?>

            <div class="mb-3 greet-bar rounded-4 p-3 text-white">
                <h5 class="fw-bold mb-0">📢 Raise Complaint</h5>
                <small style="opacity:.85;">Report an issue to the admin team</small>
            </div>

            <?php if ($complaintSuccess): ?>
                <div class="alert alert-success alert-dismissible fade show py-2">
                    ✅ <?php echo $complaintSuccess; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if ($complaintError): ?>
                <div class="alert alert-danger alert-dismissible fade show py-2">
                    ⚠️ <?php echo $complaintError; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm p-4" style="max-width:640px; margin:0 auto;">
                <form method="POST">
                    <label class="form-label fw-semibold text-primary">Subject <span class="text-danger">*</span></label>
                    <input type="text" name="complaint_subject" class="form-control mb-3"
                           placeholder="Brief subject of your complaint" required>

                    <label class="form-label fw-semibold text-primary">Message <span class="text-danger">*</span></label>
                    <textarea name="complaint_message" class="form-control mb-4" rows="5"
                              placeholder="Describe your issue in detail..." required></textarea>

                    <button type="submit" class="btn fw-semibold w-100"
                            style="background:linear-gradient(90deg,#5c8fdc,#0b48a4); color:#fff;">
                        📤 Submit Complaint
                    </button>
                </form>
            </div>

        <?php endif; ?>
    </div>
</div>

<!-- ==================== BOOKING MODAL ==================== -->
<div id="bookingModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.55); z-index:1050; overflow-y:auto;">
    <div style="background:#fff; width:480px; max-width:96%; margin:60px auto; border-radius:18px; box-shadow:0 12px 48px rgba(11,72,164,.25); overflow:hidden;">

        <div style="background:linear-gradient(90deg,#5c8fdc,#0b48a4); padding:18px 24px;" class="d-flex align-items-center justify-content-between">
            <h5 class="fw-bold text-white mb-0" id="modalTitle">📅 Book a Tutor Session</h5>
            <button onclick="closeBookingModal()"
                style="background:rgba(255,255,255,.2); border:none; border-radius:50%; width:32px; height:32px; color:#fff; font-size:1.1rem; cursor:pointer; line-height:1;">✕</button>
        </div>

        <div style="padding:20px 24px;">
            <?php if ($bookingSuccess): ?>
                <div class="alert alert-success py-2">✅ <?php echo $bookingSuccess; ?></div>
            <?php endif; ?>
            <?php if ($bookingError): ?>
                <div class="alert alert-danger py-2">⚠️ <?php echo $bookingError; ?></div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="tutor_id" id="tutor_id">

                <label class="form-label fw-semibold text-primary">Subject <span class="text-danger">*</span></label>
                <select name="subject_id" class="form-select mb-3" required>
                    <option value="">— Select Subject —</option>
                    <?php if ($modalSubjects): $modalSubjects->data_seek(0); while ($ms = $modalSubjects->fetch_assoc()): ?>
                        <option value="<?php echo $ms['id']; ?>"
                            <?php echo (isset($_POST['subject_id']) && $_POST['subject_id'] == $ms['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($ms['name']); ?>
                        </option>
                    <?php endwhile; endif; ?>
                </select>

                <label class="form-label fw-semibold text-primary">Your Requirement <span class="text-danger">*</span></label>
                <textarea name="requirement" class="form-control mb-3" rows="3"
                    placeholder="e.g. Need help with Class 10 Maths — Algebra and Geometry"
                    required><?php echo htmlspecialchars($_POST['requirement'] ?? ''); ?></textarea>

                <label class="form-label fw-semibold text-primary">Duration (Months) <span class="text-danger">*</span></label>
                <input type="number" name="duration_months" class="form-control mb-4"
                    min="1" max="24" placeholder="e.g. 3"
                    value="<?php echo htmlspecialchars($_POST['duration_months'] ?? ''); ?>"
                    required>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary flex-fill" onclick="closeBookingModal()">Cancel</button>
                    <button type="submit" name="book_tutor" class="btn flex-fill fw-semibold"
                            style="background:linear-gradient(90deg,#5c8fdc,#0b48a4); color:#fff;">
                        ✉️ Send Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/EduGuide-php/assets/bootstrap/bootstrap.bundle.min.js"></script>
<script>
    // ── Sidebar toggle ──
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

    // ── Profile edit toggle ──
    function toggleEdit() {
        const view = document.getElementById('profile-view');
        const form = document.getElementById('edit-form');
        if (view && form) {
            const showForm = view.style.display !== 'none';
            view.style.display = showForm ? 'none'  : 'block';
            form.style.display = showForm ? 'block' : 'none';
        }
    }

    <?php if (!empty($profileError) && $page === 'profile'): ?>
        window.addEventListener('DOMContentLoaded', toggleEdit);
    <?php endif; ?>

    // ── Booking modal ──
    function openBookingModal(tutorId, tutorName) {
        document.getElementById('bookingModal').style.display = 'block';
        document.getElementById('tutor_id').value = tutorId;
        if (tutorName) {
            document.getElementById('modalTitle').textContent = '📅 Book Session with ' + tutorName;
        }
    }

    function closeBookingModal() {
        document.getElementById('bookingModal').style.display = 'none';
    }

    document.getElementById('bookingModal').addEventListener('click', function(e) {
        if (e.target === this) closeBookingModal();
    });

    <?php if ($bookingError): ?>
        window.addEventListener('DOMContentLoaded', function () {
            document.getElementById('bookingModal').style.display = 'block';
            <?php if (isset($_POST['tutor_id'])): ?>
                document.getElementById('tutor_id').value = <?php echo intval($_POST['tutor_id']); ?>;
            <?php endif; ?>
        });
    <?php endif; ?>

    // ── Booking filter tabs ──
    document.querySelectorAll('.booking-tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.booking-tab').forEach(function(t) {
                t.classList.remove('active');
                t.style.opacity = '.75';
            });
            this.classList.add('active');
            this.style.opacity = '1';

            const filter  = this.dataset.filter;
            const cards   = document.querySelectorAll('.booking-card-item');
            let   visible = 0;

            cards.forEach(function(card) {
                const match = filter === 'all' || card.dataset.status === filter;
                card.style.display = match ? '' : 'none';
                if (match) visible++;
            });

            const noResult = document.getElementById('noFilterResult');
            if (noResult) noResult.style.display = visible === 0 ? 'block' : 'none';
        });
    });

    // ── Expand / Collapse booking detail ──
    function toggleCard(detailId, btn) {
        const panel = document.getElementById(detailId);
        if (panel.style.display === 'none') {
            panel.style.display = 'block';
            btn.textContent = 'Hide Details ▴';
        } else {
            panel.style.display = 'none';
            btn.textContent = 'View Details ▾';
        }
    }
</script>
</body>
</html>