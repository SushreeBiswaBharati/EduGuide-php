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
        .booking-tab.active { box-shadow: 0 0 0 3px rgba(255,255,255,.6), 0 0 0 5px rgba(11,72,164,.4); }
    </style>
</head>
<body>
<div class="d-flex p-3 gap-3 vh-100">

    <!-- sidebar -->
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

        <?php
        $currentPage = $_GET['page'] ?? 'dashboard';
        ?>

        <nav class="flex-grow-1 px-3 py-3 d-flex flex-column gap-2 fw-semibold">

            <a href="?page=dashboard"
            class="nav-link <?php echo $currentPage === 'dashboard' ? 'active-link' : ''; ?>">
                <small>🏡</small>
                <span>Home</span>
            </a>

            <a href="?page=profile"
            class="nav-link <?php echo $currentPage === 'profile' ? 'active-link' : ''; ?>">
                <small>👤</small>
                <span>My Profile</span>
            </a>

            <a href="?page=browse"
            class="nav-link <?php echo $currentPage === 'browse' ? 'active-link' : ''; ?>">
                <small>🔍</small>
                <span>Browse Tutors</span>
            </a>

            <a href="?page=bookings"
            class="nav-link <?php echo $currentPage === 'bookings' ? 'active-link' : ''; ?>">
                <small>📅</small>
                <span>My Bookings</span>
            </a>

            <a href="?page=reviews"
            class="nav-link <?php echo $currentPage === 'reviews' ? 'active-link' : ''; ?>">
                <small>⭐</small>
                <span>My Reviews</span>
            </a>

            <a href="?page=complaint"
            class="nav-link <?php echo $currentPage === 'complaint' ? 'active-link' : ''; ?>">
                <small>📢</small>
                <span>Raise Complaint</span>
            </a>

        </nav>

        <div class="pb-3 px-2 mb-3 d-flex justify-content-center">
            <a href="/EduGuide-php/views/auth/logout.php"
               class="logout nav-link fw-semibold btn btn-sm shadow w-75 rounded"
               onclick="return confirm('Are you sure you want to logout?')">
                <small style="font-size:larger;">👉</small><span class="logout-span">Logout</span>
            </a>
        </div>
    </div>

    <!--main content-->
    <div class="flex-grow-1 main-content p-4 rounded-4 shadow-sm" style="overflow-y:auto;">

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

        <!-- my profile-->
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

        <!-- Browse tutors -->
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
                            <th class="text-white bg-black">Photo</th>
                            <th class="text-white bg-black">Name</th>
                            <th class="text-white bg-black">Subjects</th>
                            <th class="text-white bg-black">Board</th>
                            <th class="text-white bg-black">Experience</th>
                            <th class="text-white bg-black">Gender</th>
                            <th class="text-white bg-black">Availability</th>
                            <th class="text-white bg-black">Rating</th>
                            <th class="text-white bg-black">Action</th>
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
                                    <td class="text-center">
                                        <?php if ($t['availability'] === 'Yes'): ?>
                                            <span class="badge bg-success rounded-pill">✔ Available</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger rounded-pill">✖ Unavailable</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div>
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <span style="color:<?php echo $i <= round($t['rating']) ? '#ffc107' : '#dee2e6'; ?>; font-size:.85rem;">★</span>
                                            <?php endfor; ?>
                                        </div>
                                        <small style="font-size:.75rem; color:#555;">
                                            <?php echo number_format($t['rating'], 1); ?>
                                            (<?php echo intval($t['review_count']); ?>)
                                        </small>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1 justify-content-center flex-wrap">
                                            <button class="btn btn-sm fw-semibold"
                                                style="background:linear-gradient(90deg,#5c8fdc,#0b48a4); color:#fff; border:none;"
                                                onclick="openBookingModal(<?php echo $t['id']; ?>, '<?php echo addslashes(htmlspecialchars($t['name'])); ?>')">
                                                📅 Book
                                            </button>
                                            <button class="btn btn-sm fw-semibold"
                                                style="background:#fff3cd; color:#664d03; border:1px solid #ffc107;"
                                                onclick="openReviewsModal(<?php echo $t['id']; ?>, '<?php echo addslashes(htmlspecialchars($t['name'])); ?>')">
                                                ⭐ Reviews
                                            </button>
                                        </div>
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

        <!--my bookings-->
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
                <div class="alert alert-success alert-dismissible fade show py-3 mb-3">
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

                <div class="text-center py-5 bg-white rounded shadow-sm">
                    <div style="font-size:3rem;">📭</div>
                    <h5 class="fw-bold text-primary mt-2">No Booking Sessions Yet</h5>
                    <p class="text-muted">Start learning today by booking a tutor.</p>

                    <a href="?page=browse" class="btn btn-primary">
                        Browse Tutors
                    </a>
                </div>

            <?php else: ?>

            <div id="bookingCardsList">

                <?php foreach ($allBookings as $bk): ?>

                    <?php
                    $status = $bk['status'];

                    $badgeClass = match($status) {
                        'Pending'   => 'bg-warning text-dark',
                        'Confirmed' => 'bg-success',
                        'Completed' => 'bg-primary',
                        'Cancelled' => 'bg-danger',
                        default     => 'bg-secondary'
                    };

                    $bookingDate = date('d M Y', strtotime($bk['created_at']));
                    ?>

                    <div class="booking-card-item mb-3" data-status="<?php echo $status; ?>">

                        <div class="card shadow-sm border-1-black ">

                            <div class="card-body">

                                <!-- Top -->
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">

                                    <div>
                                        <h6 class="fw-bold fs-3 text-primary mb-1">
                                            <?php echo htmlspecialchars($bk['tutor_name']); ?>
                                        </h6>

                                        <div class="text-muted fs-5 small">
                                            <?php echo htmlspecialchars($bk['subject_name'] ?? 'N/A'); ?>
                                        </div>
                                    </div>

                                    <span class="badge <?php echo $badgeClass; ?>">
                                        <?php echo $status; ?>
                                    </span>

                                </div>

                                <!-- Booking Info -->
                                <div class="row mt-3">

                                    <div class="col-md-4 mb-2">
                                        <small class="text-muted d-block">Duration</small>
                                        <span class="fw-semibold">
                                            <?php echo $bk['duration_months']; ?> Month(s)
                                        </span>
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <small class="text-muted d-block">Booked On</small>
                                        <span class="fw-semibold">
                                            <?php echo $bookingDate; ?>
                                        </span>
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <small class="text-muted d-block">Progress</small>

                                        <span class="fw-semibold">
                                            <?php
                                            if ($status === 'Pending') {
                                                echo 'Requested';
                                            } elseif ($status === 'Confirmed') {
                                                echo 'Confirmed';
                                            } elseif ($status === 'Completed') {
                                                echo 'Completed';
                                            } else {
                                                echo 'Cancelled';
                                            }
                                            ?>
                                        </span>
                                    </div>

                                </div>

                                <!-- Requirement -->
                                <div class="mt-3">

                                    <small class="text-muted d-block mb-1">
                                        Requirement
                                    </small>

                                    <div class="border rounded p-2 bg-light small">
                                        <?php echo nl2br(htmlspecialchars($bk['requirement'])); ?>
                                    </div>

                                </div>

                                <!-- Actions -->
                                <div class="d-flex gap-2 flex-wrap mt-3">

                                    <?php if ($status === 'Pending'): ?>

                                        <form method="POST"
                                            onsubmit="return confirm('Cancel this booking?')">

                                            <input type="hidden"
                                                name="cancel_booking_id"
                                                value="<?php echo $bk['id']; ?>">

                                            <button type="submit"
                                                    class="btn btn-danger btn-sm">
                                                Cancel Booking
                                            </button>

                                        </form>

                                    <?php endif; ?>

                                    <a href="?page=browse"
                                    class="btn btn-outline-primary btn-sm">
                                        Browse Tutors
                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>

                <div id="noFilterResult"
                    class="text-center py-4 text-muted"
                    style="display:none;">

                    <div style="font-size:2rem;">🔍</div>
                    <p class="mt-2">No bookings match this filter.</p>

                </div>

            </div>

        <?php endif; ?>

        <!--My reviews-->
        <?php elseif ($page === 'reviews'): ?>

            <!-- Header -->
            <div class="mb-4 greet-bar rounded-4 p-3 text-white d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-0">⭐ My Reviews</h5>
                    <small style="opacity:.85;">Rate your completed tutor sessions</small>
                </div>
            </div>

            <!-- Alerts -->
            <?php if ($reviewSuccess): ?>
                <div class="alert alert-success alert-dismissible fade show py-2 mb-3">
                    <?php echo $reviewSuccess; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($reviewError): ?>
                <div class="alert alert-danger alert-dismissible fade show py-2 mb-3">
                    <?php echo $reviewError; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php
            $completedBookingsForReview->data_seek(0);
            $reviewRows = [];

            while ($r = $completedBookingsForReview->fetch_assoc()) {
                $reviewRows[] = $r;
            }
            ?>

            <?php if (empty($reviewRows)): ?>

                <div class="card shadow-sm border-0">
                    <div class="card-body text-center py-5">
                        <h5 class="fw-semibold mb-2">No Reviews Available</h5>
                        <p class="text-muted mb-3">
                            Complete a tutor session to leave a review.
                        </p>

                        <a href="?page=browse" class="btn btn-primary">
                            Browse Tutors
                        </a>
                    </div>
                </div>

            <?php else: ?>
                <?php foreach ($reviewRows as $row):
                    $alreadyReviewed = !empty($row['review_id']);
                    $sessionDate = date('d M Y', strtotime($row['created_at']));
                ?>

                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body">

                        <!-- Top Info -->
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">

                            <div>
                                <h6 class="fw-bold mb-1">
                                    <?php echo htmlspecialchars($row['tutor_name']); ?>
                                </h6>

                                <div class="text-muted small">
                                    Subject:
                                    <?php echo htmlspecialchars($row['subject_name'] ?? 'N/A'); ?>
                                </div>

                                <div class="text-muted small">
                                    Session Date:
                                    <?php echo $sessionDate; ?>
                                </div>
                            </div>

                            <div>
                                <?php if ($alreadyReviewed): ?>
                                    <span class="badge bg-success">
                                        Reviewed
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">
                                        Pending
                                    </span>
                                <?php endif; ?>
                            </div>

                        </div>

                        <!-- Already Reviewed -->
                        <?php if ($alreadyReviewed): ?>

                            <div class="border rounded p-3 bg-light">

                                <div class="mb-2">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <span style="color:<?php echo $i <= $row['rating'] ? '#ffc107' : '#dee2e6'; ?>; font-size:1.1rem;">
                                            ★
                                        </span>
                                    <?php endfor; ?>

                                    <span class="fw-semibold ms-2">
                                        <?php echo $row['rating']; ?>/5
                                    </span>
                                </div>

                                <p class="mb-0 text-muted">
                                    "<?php echo htmlspecialchars($row['comment']); ?>"
                                </p>

                            </div>

                        <!-- Review Form -->
                        <?php else: ?>

                            <form method="POST" class="border rounded p-3 bg-light">

                                <input type="hidden" name="submit_review" value="1">
                                <input type="hidden" name="review_booking_id"
                                    value="<?php echo $row['booking_id']; ?>">
                                <input type="hidden" name="review_tutor_id"
                                    value="<?php echo $row['tutor_id']; ?>">

                                <!-- Rating -->
                                <div class="mb-3">

                                    <label class="form-label fw-semibold">
                                        Rating
                                    </label>

                                    <div class="mb-1">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span class="star"
                                                data-val="<?php echo $i; ?>"
                                                data-group="<?php echo $row['booking_id']; ?>"
                                                onclick="setRating(<?php echo $row['booking_id']; ?>, <?php echo $i; ?>)"
                                                style="font-size:1.8rem; cursor:pointer; color:#dee2e6;">
                                                ★
                                            </span>
                                        <?php endfor; ?>
                                    </div>

                                    <input type="hidden"
                                        name="rating"
                                        id="rating-<?php echo $row['booking_id']; ?>"
                                        value="0">

                                    <small class="text-muted"
                                        id="ratingLabel-<?php echo $row['booking_id']; ?>">
                                    </small>

                                </div>

                                <!-- Comment -->
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        Comment
                                    </label>

                                    <textarea name="comment"
                                            class="form-control"
                                            rows="3"
                                            placeholder="Write your feedback..."
                                            required></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    Submit Review
                                </button>

                            </form>

                        <?php endif; ?>

                    </div>
                </div>

                <?php endforeach; ?>

            <?php endif; ?>

            <!-- Rating Script -->
            <script>
                const ratingLabels = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];

                function setRating(groupId, val) {

                    document.getElementById('rating-' + groupId).value = val;

                    document.getElementById('ratingLabel-' + groupId).textContent =
                        ratingLabels[val];

                    const stars = document.querySelectorAll(
                        '.star[data-group="' + groupId + '"]'
                    );

                    stars.forEach(function(star) {

                        if (parseInt(star.dataset.val) <= val) {
                            star.style.color = '#ffc107';
                        } else {
                            star.style.color = '#dee2e6';
                        }

                    });
                }
            </script>


        <!-- Raise complaint -->
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

            <!-- Complaint Form -->
            <div class="card shadow-sm p-4 mb-4" style="max-width:640px; margin:0 auto;">
                <h6 class="fw-bold mb-3" style="color:#0b48a4;">📝 Submit a New Complaint</h6>
                <form method="POST">
                    <label class="form-label fw-semibold text-primary">Subject <span class="text-danger">*</span></label>
                    <input type="text" name="complaint_subject" class="form-control mb-3"
                           placeholder="Brief subject of your complaint" required>

                    <label class="form-label fw-semibold text-primary">Message <span class="text-danger">*</span></label>
                    <textarea name="complaint_message" class="form-control mb-4" rows="4"
                              placeholder="Describe your issue in detail..." required></textarea>

                    <button type="submit" class="btn fw-semibold w-100"
                            style="background:linear-gradient(90deg,#5c8fdc,#0b48a4); color:#fff;">
                        📤 Submit Complaint
                    </button>
                </form>
            </div>

            <!-- My Submitted Complaints -->
            <div style="max-width:640px; margin:0 auto;">
                <h6 class="fw-bold mb-3" style="color:#0b48a4;">📋 My Submitted Complaints</h6>

                <?php
                // Load all complaints into array
                $myComplaintList = [];
                while ($c = $myComplaints->fetch_assoc()) {
                    $myComplaintList[] = $c;
                }
                ?>

                <?php if (empty($myComplaintList)): ?>
                    <div class="text-center py-4"
                         style="background:rgba(255,255,255,.7); border-radius:14px; border:1.5px dashed #5c8fdc;">
                        <div style="font-size:2.5rem;">📭</div>
                        <p class="text-muted mt-2 mb-0">You have not submitted any complaints yet.</p>
                    </div>

                <?php else: ?>
                    <?php foreach ($myComplaintList as $c):
                        $isResolved  = $c['status'] === 'Resolved';
                        $borderColor = $isResolved ? '#198754' : '#ffc107';
                        $badgeClass  = $isResolved ? 'bg-success' : 'bg-warning text-dark';
                        $statusIcon  = $isResolved ? '✔' : '⏳';
                        $datePosted  = date('d M Y', strtotime($c['created_at']));
                    ?>
                    <div class="card shadow-sm mb-3"
                         style="border-left:4px solid <?php echo $borderColor; ?>;">
                        <div class="card-body py-3 px-4">

                            <!-- Top row: subject + status badge -->
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                <div class="fw-bold" style="color:#0b48a4; font-size:.95rem;">
                                    <?php echo htmlspecialchars($c['subject']); ?>
                                </div>
                                <span class="badge <?php echo $badgeClass; ?> rounded-pill flex-shrink-0">
                                    <?php echo $statusIcon; ?> <?php echo $c['status']; ?>
                                </span>
                            </div>

                            <!-- Message -->
                            <p class="text-muted mb-2" style="font-size:.88rem; line-height:1.5;">
                                <?php echo nl2br(htmlspecialchars($c['message'])); ?>
                            </p>

                            <!-- Date + resolution note + delete -->
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <small class="text-muted">📆 Submitted: <?php echo $datePosted; ?></small>
                                <div class="d-flex align-items-center gap-3">
                                    <?php if ($isResolved): ?>
                                        <small style="color:#198754; font-weight:500;">✔ Resolved by Admin</small>
                                    <?php else: ?>
                                        <small style="color:#856404;">⏳ Awaiting admin review</small>
                                    <?php endif; ?>
                                    <form method="POST"
                                          onsubmit="return confirm('Delete this complaint permanently?')">
                                        <input type="hidden" name="delete_my_complaint" value="<?php echo $c['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm fw-semibold px-3">
                                            🗑 Delete
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        <?php endif; ?>
    </div>
</div>

<!-- Booking modal-->
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
<!--Tutor review modal -->

<?php
// Build reviews modal HTML for every tutor that has reviews
// This is output once — JS shows/hides the right one
foreach ($allTutorReviews as $tid => $reviews):
    // Calculate avg and star counts for this tutor
    $totalRatings = count($reviews);
    $sumRatings   = array_sum(array_column($reviews, 'rating'));
    $avgRating    = $totalRatings > 0 ? round($sumRatings / $totalRatings, 1) : 0;
    $starCounts   = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
    foreach ($reviews as $rv) $starCounts[intval($rv['rating'])]++;
?>
<div id="reviewsModal-<?php echo $tid; ?>"
     style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.55); z-index:1060; overflow-y:auto;"
     onclick="if(event.target===this) closeReviewsModal(<?php echo $tid; ?>)">

    <div style="background:#fff; width:560px; max-width:96%; margin:50px auto; border-radius:18px;
                box-shadow:0 12px 48px rgba(11,72,164,.25); overflow:hidden;">

        <!-- Header -->
        <div style="background:linear-gradient(90deg,#5c8fdc,#0b48a4); padding:16px 22px;"
             class="d-flex align-items-center justify-content-between">
            <div>
                <div class="fw-bold text-white fs-6" id="rv-title-<?php echo $tid; ?>"></div>
                <small class="text-white" style="opacity:.85;">
                    ⭐ <?php echo $avgRating; ?> &nbsp;·&nbsp;
                    <?php echo $totalRatings; ?> review<?php echo $totalRatings != 1 ? 's' : ''; ?>
                </small>
            </div>
            <button onclick="closeReviewsModal(<?php echo $tid; ?>)"
                style="background:rgba(255,255,255,.2); border:none; border-radius:50%;
                       width:32px; height:32px; color:#fff; font-size:1.1rem;
                       cursor:pointer; line-height:1;">✕</button>
        </div>

        <!-- Body -->
        <div style="padding:20px 22px;">

            <!-- Step 1: Summary card -->
            <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded-3"
                 style="background:#f0f6ff; border-left:4px solid #5c8fdc;">

                <!-- Average number + stars -->
                <div class="text-center" style="flex-shrink:0;">
                    <div style="font-size:2.4rem; font-weight:700; color:#0b48a4; line-height:1;">
                        <?php echo $avgRating; ?>
                    </div>
                    <div class="my-1">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span style="font-size:1.1rem;
                                  color:<?php echo $i <= round($avgRating) ? '#ffc107' : '#dee2e6'; ?>;">★</span>
                        <?php endfor; ?>
                    </div>
                    <small class="text-muted"><?php echo $totalRatings; ?> review<?php echo $totalRatings != 1 ? 's' : ''; ?></small>
                </div>

                <!-- Step 2: Star breakdown bars -->
                <div class="flex-grow-1">
                    <?php foreach ([5, 4, 3, 2, 1] as $star):
                        $count = $starCounts[$star];
                        $pct   = $totalRatings > 0 ? round(($count / $totalRatings) * 100) : 0;
                    ?>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <div style="width:24px; font-size:.78rem; font-weight:600;
                                    color:#664d03; text-align:right; flex-shrink:0;">
                            <?php echo $star; ?>★
                        </div>
                        <div style="flex:1; height:10px; background:#e9ecef;
                                    border-radius:5px; overflow:hidden;">
                            <div style="height:100%; width:<?php echo $pct; ?>%;
                                        background:linear-gradient(90deg,#ffc107,#fd7e14);
                                        border-radius:5px;"></div>
                        </div>
                        <div style="width:20px; font-size:.78rem; color:#555; flex-shrink:0;">
                            <?php echo $count; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Step 3: Individual review cards -->
            <?php if ($totalRatings === 0): ?>
                <div class="text-center py-4"
                     style="border:1.5px dashed #ffc107; border-radius:12px;">
                    <div style="font-size:2.5rem;">⭐</div>
                    <p class="text-muted mt-2 mb-0">No reviews yet for this tutor.</p>
                </div>

            <?php else: ?>
                <div class="d-flex flex-column gap-3" style="max-height:380px; overflow-y:auto; padding-right:4px;">
                    <?php foreach ($reviews as $rv):

                        // Star border color by rating
                        $starColor = match(intval($rv['rating'])) {
                            5 => '#198754',
                            4 => '#0d6efd',
                            3 => '#ffc107',
                            2 => '#fd7e14',
                            1 => '#dc3545',
                            default => '#6c757d'
                        };

                        // Student initials
                        $parts    = explode(' ', $rv['student_name']);
                        $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));

                        $reviewDate = date('d M Y', strtotime($rv['created_at']));
                    ?>
                    <div style="border-left:4px solid <?php echo $starColor; ?>;
                                background:#fafafa; border-radius:10px; padding:12px 14px;">

                        <!-- Reviewer name + date + rating badge -->
                        <div class="d-flex align-items-center gap-2 mb-1">

                            <!-- Avatar initials -->
                            <div style="width:36px; height:36px; border-radius:50%;
                                        background:#e0f0ff; color:#0b48a4;
                                        display:flex; align-items:center; justify-content:center;
                                        font-size:12px; font-weight:600; flex-shrink:0;">
                                <?php echo $initials; ?>
                            </div>

                            <div class="flex-grow-1">
                                <div style="font-weight:600; color:#0b48a4; font-size:.9rem;">
                                    <?php echo htmlspecialchars($rv['student_name']); ?>
                                </div>
                                <small style="color:#888;"><?php echo $reviewDate; ?></small>
                            </div>

                            <!-- Rating badge -->
                            <div style="font-size:.78rem; font-weight:700;
                                        color:<?php echo $starColor; ?>;
                                        background:<?php echo $starColor; ?>1a;
                                        border:1px solid <?php echo $starColor; ?>40;
                                        padding:2px 8px; border-radius:99px; flex-shrink:0;">
                                <?php echo $rv['rating']; ?> ★
                            </div>
                        </div>

                        <!-- Star row -->
                        <div class="mb-1">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <span style="font-size:.95rem;
                                      color:<?php echo $i <= $rv['rating'] ? '#ffc107' : '#dee2e6'; ?>;">★</span>
                            <?php endfor; ?>
                        </div>

                        <!-- Comment -->
                        <p style="font-size:.88rem; color:#555; font-style:italic;
                                  margin:6px 0 0 0; line-height:1.5;">
                            "<?php echo htmlspecialchars($rv['comment'] ?? 'No comment.'); ?>"
                        </p>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Close button -->
            <div class="text-center mt-4">
                <button onclick="closeReviewsModal(<?php echo $tid; ?>)"
                        class="btn fw-semibold px-5"
                        style="background:linear-gradient(90deg,#5c8fdc,#0b48a4); color:#fff; border:none;">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>

<script>
    // Open the correct tutor's review modal by tutor ID
    function openReviewsModal(tutorId, tutorName) {
        var modal = document.getElementById('reviewsModal-' + tutorId);
        if (modal) {
            // Set the tutor name in the header
            var title = document.getElementById('rv-title-' + tutorId);
            if (title) title.textContent = tutorName;
            modal.style.display = 'block';
        } else {
            alert(tutorName + ' has no reviews yet.');
        }
    }

    function closeReviewsModal(tutorId) {
        var modal = document.getElementById('reviewsModal-' + tutorId);
        if (modal) modal.style.display = 'none';
    }
</script>

</body>
</html>