<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="/EduGuide-php/assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/EduGuide-php/assets/css/dashboard.css?v=1.1">
</head>
<body>
    <div class="d-flex p-3 gap-3 vh-100">
        <!-- Sidebar-->
        <div class="sidebar d-flex flex-column justify-content-between p-0 rounded-4" id="sidebar">

            <div class="p-3 border-bottom border-white border-opacity-25 d-flex align-items-center justify-content-between">
                <div>
                    <div class="fw-bold fs-5 brand-title">EduGuide</div>
                    <small class="brand-subtitle" style="opacity:0.75;">Student Panel</small>
                </div>
                <button class="hamburger-btn" id="toggleBtn">
                    <img src="/EduGuide-php/assets/icons/list.svg" alt="menu">
                </button>
            </div>

            <!-- Nav Links -->
            <nav class="flex-grow-1 px-3 py-3 d-flex flex-column gap-2 fw-semibold">

                <a href="?page=dashboard" class="nav-link active">
                    <small>🏡</small> <span>Home</span>
                </a>

                <a href="?page=profile" class="nav-link">
                    <small>👤</small> <span>My Profile</span>
                </a>

                <a href="?page=browse" class="nav-link">
                    <small>🔍</small> <span>Browse Tutors</span>
                </a>

                <a href="?page=bookings" class="nav-link">
                    <small>📅</small> <span>My Bookings</span>
                </a>

                <a href="?page=reviews" class="nav-link">
                    <small>⭐</small> <span>My Reviews</span>
                </a>

                <a href="?page=complaint" class="nav-link">
                    <small>📢</small> <span>Raise Complaint</span>
                </a>

            </nav>

            <!-- Logout -->
           <div class="pb-3 px-2 mb-3 d-flex justify-content-center">
                <a href="/EduGuide-php/views/auth/logout.php" class="logout nav-link fw-semibold btn btn-sm shadow w-75 rounded"
                onclick="return confirm('Are you sure you want to logout?')">
                <small style="font-size: larger;">👉</small><span class="logout-span">Logout</span>
                </a>
            </div>
        </div>

        <!--Main Content-->
        <div class="flex-grow-1 main-content p-4 rounded-4 shadow-sm">
            <?php if ($page === 'dashboard'): ?>
                <!-- Dashboard -->
                <div class="mb-4 greet-bar rounded-4 p-4 text-white">
                    <span><?php echo $today;?></span>
                    <h4 class="fw-bold mb-1">
                        Welcome Back <?php echo htmlspecialchars($student['name'] ?? 'Student') ?>!
                    </h4>
                    <p class="fst-italic mb-3">"Your future is created by what you do today."</p>
                    <span class="badge bg-white bg-opacity-75 text-primary px-3 py-2">Student</span>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-3 col-6">
                        <div class="cards card1 text-center px-2 py-3 ">
                            <div class="fw-semibold">Total Bookings</div>
                            <div class="fw-bold fs-4"><?php echo $totalBookings; ?></div>
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="cards card2 text-center px-2 py-3 ">
                            <div class="fw-semibold">Confirmed</div>
                            <div class="fw-bold fs-4"><?php echo $confirmedBookings; ?></div>
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="cards card3 text-center px-2 py-3 ">
                            <div class="fw-semibold">Pending</div>
                            <div class="fw-bold fs-4"><?php echo $pendingBookings; ?></div>
                        </div>
                    </div>

                    <div class="col-md-3 col-6">
                        <div class="cards card4 text-center px-2 py-3 ">
                            <div class="fw-semibold">Completed</div>
                            <div class="fw-bold fs-4"><?php echo $completedBookings; ?></div>
                        </div>
                    </div>
                </div>


                <!-- Top Tutors Table -->
                <div class="card p-3 mb-4">
                    <h6>Top Tutors</h6>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Bookings</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($t = $topTutors->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $t['name']; ?></td>
                                    <td><?php echo $t['total']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <?php elseif ($page === 'profile'): ?>
                    <!-- Profile -->
                    <h5 class="fw-bold text-primary mb-4">👤 My Profile</h5>
                    <?php if ($profileSuccess): ?>
                        <div class="alert alert-success"><?php echo $profileSuccess; ?></div>
                    <?php endif; ?>

                    <?php if ($profileError): ?>
                        <div class="alert alert-danger"><?php echo $profileError; ?></div>
                    <?php endif; ?>

                <!-- View profile -->
                <div id="profile-view">
                    <div class="card shadow-sm p-4">
                        <div class="row g-3">
                            <!-- Left : profile image -->
                            <div class="col-md-4">
                                <div class="card shadow-sm p-3 text-center h-100">
                                    <!-- Profile Image -->
                                    <div class=" my-auto">
                                        <?php
                                        $image = !empty($student['profile_image'])
                                            ? "/EduGuide-php/assets/profile/" . $student['profile_image']
                                            : "/EduGuide-php/assets/default-user.png";
                                        ?>
                                        <img src="<?php echo $image; ?>" class="profile-img">
                                    </div>

                                    <h6 class="fw-bold my-2"><?php echo $student['name']; ?></h6>
                                    <small class="text-muted d-block">Class: <?php echo $student['class_name']; ?></small>
                                </div>
                            </div>

                            <!-- RIGHT: DETAILS TABLE -->
                            <div class="col-md-8">
                                <div class="shadow-sm p-3 h-100">
                                    <h6 class="fw-bold fs-4 mb-3 text-center text-warning">Your Details</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm align-middle mb-0">

                                            <tr>
                                                <td class="fw-semibold text-primary">Email</td>
                                                <td class="fw-semibold text-muted"><?php echo $student['email']; ?></td>
                                            </tr>

                                            <tr>
                                                <td class="fw-semibold text-primary">Gender</td>
                                                <td class="fw-semibold text-muted"><?php echo $student['gender']; ?></td>
                                            </tr>

                                            <tr>
                                                <td class="fw-semibold text-primary">Board</td>
                                                <td class="fw-semibold text-muted"><?php echo $student['board_name']; ?></td>
                                            </tr>

                                            <tr>
                                                <td class="fw-semibold text-primary">Exam</td>
                                                <td class="fw-semibold text-muted"><?php echo $student['exam_name']; ?></td>
                                            </tr>

                                            <tr>
                                                <td class="fw-semibold text-primary">School Name</td>
                                                <td class="fw-semibold text-muted"><?php echo $student['school_name']; ?></td>
                                            </tr>

                                            <tr>
                                                <td class="fw-semibold text-primary">Parent</td>
                                                <td class="fw-semibold text-muted"><?php echo $student['parent_name']; ?></td>
                                            </tr>

                                            <tr>
                                                <td class="fw-semibold text-primary">Phone</td>
                                                <td class="fw-semibold text-muted"><?php echo $student['parent_phone']; ?></td>
                                            </tr>

                                            <tr>
                                                <td class="fw-semibold text-primary">Address</td>
                                                <td class="fw-semibold text-muted"><?php echo $student['address']; ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center py-3 ">
                            <button type="button" class="btn btn-primary mt-3 w-25" onclick="toggleEdit()">Edit Profile</button>
                        </div>
                    </div>
                </div>

                <!-- Edit profile -->
                <div id="edit-form" style="display:none;">
                    <div class="card shadow-sm p-4">
                        <div class="row g-3 d-flex justify-content-center">
                            <div class="col-md-8">
                                
                                <form method="POST" enctype="multipart/form-data">
                                    <label class="form-label fw-semibold fs-5 text-primary">Profile Photo</label>
                                    <input type="file" name="profile_image" class="form-control mb-3" accept="image/*">
                                    <label for="class" class="form-label fw-semibold fs-5 text-primary">Class:</label>
                                    <select name="edit_class" class="form-control mb-3" required>
                                        <option value="">Select Class</option>
                                        <?php while($c = $classes->fetch_assoc()): ?>
                                            <option value="<?php echo $c['id']; ?>" <?php if($c['name']==$student['class_name']) echo 'selected'; ?>>
                                                <?php echo $c['name']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>

                                    <!-- BOARD -->
                                    <label for="class" class="form-label fw-semibold fs-5 text-primary">Education Board:</label>
                                    <select name="edit_board" class="form-control mb-3" required>
                                        <option value="">Select Board</option>
                                        <?php while($b = $boards->fetch_assoc()): ?>
                                            <option value="<?php echo $b['id']; ?>" <?php if($b['name']==$student['board_name']) echo 'selected'; ?>>
                                                <?php echo $b['name']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>

                                    <!-- EXAM -->
                                    <label for="class" class="form-label fw-semibold fs-5 text-primary">Exam:</label>
                                    <select name="edit_exam" class="form-control mb-3" required>
                                        <option value="">Select Exam</option>
                                        <?php while($e = $exams->fetch_assoc()): ?>
                                            <option value="<?php echo $e['id']; ?>" <?php if($e['name']==$student['exam_name']) echo 'selected'; ?>>
                                                <?php echo $e['name']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>

                                    <label for="class" class="form-label fw-semibold fs-5 text-primary">Parent's Phone:</label>
                                    <input type="tel" name="edit_phone" class="form-control mb-3" value="<?php echo $student['parent_phone']; ?>" required>
                                    
                                    <label for="class" class="form-label fw-semibold fs-5 text-primary">School Name:</label>
                                    <input type="text" name="edit_school" class="form-control mb-3" value="<?php echo $student['school_name']; ?>" required>
                                    
                                    <label for="class" class="form-label fw-semibold fs-5 text-primary">Address:</label>
                                    <input type="text" name="edit_address" class="form-control mb-3" value="<?php echo $student['address']; ?>" required>

                                    <div class="d-flex gap-2 mt-3">
                                        <button type="button" class="btn btn-secondary" onclick="toggleEdit()">Cancel</button>
                                        <button type="submit" class="btn btn-success">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <?php elseif ($page === 'browse'): ?>
                <h5 class="fw-bold text-primary mb-3">Browse Tutors</h5>

                <?php if ($bookingSuccess): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        ✅ <?php echo $bookingSuccess; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="GET" class="mb-4">
                    <input type="hidden" name="page" value="browse">

                    <div class="row mb-3">
                        <div class="col-12 col-md-10 mb-2">
                            <input type="text" name="search" class="form-control py-3"
                            placeholder="Search by name, subject, board, or location..."
                            value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                        </div>

                        <div class="col-12 col-md-2 d-flex align-items-md-center">
                            <button class="btn btn-primary w-100 ">Search</button>
                        </div>
                    </div>

                    <!-- FILTERS -->
                    <div class="row g-2">

                        <div class="col-md-3">
                            <select name="subject" class="form-control">
                                <option value="">All Subjects</option>
                                <?php while($s = $subjects->fetch_assoc()): ?>
                                    <option value="<?php echo $s['id']; ?>">
                                        <?php echo $s['name']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <select name="board" class="form-control">
                                <option value="">All Boards</option>
                                <?php while($b = $browseBoards->fetch_assoc()): ?>
                                    <option value="<?php echo $b['id']; ?>">
                                        <?php echo $b['name']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <select name="gender" class="form-control">
                                <option value="">Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <select name="sort" class="form-control">
                                <option value="">Sort By</option>
                                <option value="asc">A → Z</option>
                                <option value="desc">Z → A</option>
                                <option value="exp">Experience</option>
                                <option value="rating_high">Rating High</option>
                                <option value="rating_low">Rating Low</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <button class="btn btn-success w-100">Apply</button>
                        </div>

                    </div>
                </form>

                <!-- TABLE -->
                <div class="d-flex justidy-content-center">
                    <div class="table-responsive w-100">
                        <table class="table table-bordered table-hover bg-white shadow-sm text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th>Photo</th>
                                    <th>Name</th>
                                    <th>Subject</th>
                                    <th>Board</th>
                                    <th>Experience</th>
                                    <th>Gender</th>
                                    <th>Rating</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if ($tutors->num_rows > 0): ?>
                                    <?php while($t = $tutors->fetch_assoc()): ?>
                                        <tr>
                                            <td>
                                                <?php 
                                                    $img = !empty($t['profile_image']) ? "/EduGuide-php/assets/profile/" . $t['profile_image'] : "/EduGuide-php/assets/default.png";
                                                ?>
                                                <img src="<?php echo $img; ?>" class="rounded-circle" width="70" height="70"
                                                style="object-fit:cover;" alt="profie image">
                                            </td>
                                            <td><?php echo htmlspecialchars($t['name']); ?></td>
                                            <td><?php echo $t['subject_names']; ?></td>
                                            <td><?php echo $t['board_name']; ?></td>
                                            <td><?php echo $t['experience']; ?> yrs</td>
                                            <td><?php echo $t['gender']; ?></td>
                                            <td><?php echo $t['rating']; ?> ⭐</td>
                                            <td>
                                                <button class="btn btn-primary btn-sm"
                                                    onclick="openBookingModal(<?php echo $t['id']; ?>, '<?php echo addslashes(htmlspecialchars($t['name'])); ?>')">
                                                    Book Now
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No tutors found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php elseif ($page === 'bookings'): ?>

                <!-- ── PAGE HEADER ── -->
                <div class="mb-4 greet-bar rounded-4 p-4 text-white d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h4 class="fw-bold mb-1">📅 My Booking Sessions</h4>
                        <p class="mb-0" style="opacity:.85; font-size:.95rem;">Track all your tutor session requests in one place</p>
                    </div>
                    <a href="?page=browse" class="btn btn-light btn-sm fw-semibold px-3" style="color:#0b48a4;">
                        + Book New Session
                    </a>
                </div>

                <!-- ── STAT CARDS ── -->
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <div class="cards card1 text-center px-2 py-3">
                            <div class="fw-semibold text-white" style="font-size:.85rem;">Total</div>
                            <div class="fw-bold fs-3 text-white"><?php echo $totalBookings; ?></div>
                            <div style="font-size:.75rem; color:rgba(255,255,255,.8);">All Requests</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="cards card2 text-center px-2 py-3" >
                            <div class="fw-semibold" style="font-size:.85rem; color:#664d03;">Pending</div>
                            <div class="fw-bold fs-3" style="color:#664d03;"><?php echo $pendingBookings; ?></div>
                            <div style="font-size:.75rem; color:#856404;">Awaiting Response</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="cards card3 text-center px-2 py-3">
                            <div class="fw-semibold text-white" style="font-size:.85rem;">Confirmed</div>
                            <div class="fw-bold fs-3 text-white"><?php echo $confirmedBookings; ?></div>
                            <div style="font-size:.75rem; color:rgba(255,255,255,.85);">Tutor Accepted</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="cards card4 text-center px-2 py-3">
                            <div class="fw-semibold text-white" style="font-size:.85rem;">Completed</div>
                            <div class="fw-bold fs-3 text-white"><?php echo $completedBookings; ?></div>
                            <div style="font-size:.75rem; color:rgba(255,255,255,.85);">Sessions Done</div>
                        </div>
                    </div>
                </div>

                <!-- ── ALERTS ── -->
                <?php if (!empty($cancelSuccess)): ?>
                    <div class="alert alert-success alert-dismissible fade show py-2 mb-3" role="alert">
                        ✅ <?php echo $cancelSuccess; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if (!empty($cancelError)): ?>
                    <div class="alert alert-danger alert-dismissible fade show py-2 mb-3" role="alert">
                        ⚠️ <?php echo $cancelError; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- ── FILTER TABS ── -->
                <div class="d-flex gap-2 flex-wrap mb-3" id="bookingTabs">
                    <button class="btn btn-sm fw-semibold px-3 rounded-pill booking-tab active-tab" data-filter="all"
                        style="background:linear-gradient(90deg,#5c8fdc,#0b48a4); color:#fff; border:none;">
                        All (<?php echo $totalBookings; ?>)
                    </button>
                    <button class="btn btn-sm fw-semibold px-3 rounded-pill booking-tab" data-filter="Pending"
                        style="background:#fff3cd; color:#664d03; border:1px solid #ffc107;">
                        ⏳ Pending (<?php echo $pendingBookings; ?>)
                    </button>
                    <button class="btn btn-sm fw-semibold px-3 rounded-pill booking-tab" data-filter="Confirmed"
                        style="background:#d1e7dd; color:#0f5132; border:1px solid #198754;">
                        ✅ Confirmed (<?php echo $confirmedBookings; ?>)
                    </button>
                    <button class="btn btn-sm fw-semibold px-3 rounded-pill booking-tab" data-filter="Completed"
                        style="background:#cfe2ff; color:#084298; border:1px solid #0d6efd;">
                        🎓 Completed (<?php echo $completedBookings; ?>)
                    </button>
                    <button class="btn btn-sm fw-semibold px-3 rounded-pill booking-tab" data-filter="Cancelled"
                        style="background:#f8d7da; color:#842029; border:1px solid #dc3545;">
                        ✖ Cancelled
                    </button>
                </div>

                <!-- ── BOOKING CARDS LIST ── -->
                <div id="bookingList">
                <?php
                $allBookings = [];
                while ($bk = $bookings->fetch_assoc()) {
                    $allBookings[] = $bk;
                }
                ?>

                <?php if (empty($allBookings)): ?>
                    <!-- Empty State -->
                    <div class="text-center py-5" style="background:rgba(255,255,255,0.7); border-radius:16px; border:1.5px dashed #5c8fdc;">
                        <div style="font-size:3.5rem; margin-bottom:12px;">📭</div>
                        <h5 class="fw-bold text-primary mb-2">No Booking Sessions Yet</h5>
                        <p class="text-muted mb-4">You haven't booked any tutor sessions. Start learning today!</p>
                        <a href="?page=browse" class="btn fw-semibold px-4"
                           style="background:linear-gradient(90deg,#5c8fdc,#0b48a4); color:#fff; border-radius:8px;">
                            🔍 Browse Tutors
                        </a>
                    </div>

                <?php else: ?>

                    <?php foreach ($allBookings as $bk):
                        $status = $bk['status'];

                        // Left border color per status — matches project palette
                        $borderColor = match($status) {
                            'Pending'   => '#ffc107',
                            'Confirmed' => '#198754',
                            'Completed' => '#0d6efd',
                            'Cancelled' => '#dc3545',
                            default     => '#5c8fdc'
                        };
                        // Badge class
                        $badgeClass = match($status) {
                            'Pending'   => 'bg-warning text-dark',
                            'Confirmed' => 'bg-success text-white',
                            'Completed' => 'bg-primary text-white',
                            'Cancelled' => 'bg-danger text-white',
                            default     => 'bg-secondary text-white'
                        };
                        // Avatar bg
                        $avatarStyle = match($status) {
                            'Pending'   => 'background:#fff3cd; color:#664d03;',
                            'Confirmed' => 'background:#d1e7dd; color:#0f5132;',
                            'Completed' => 'background:#cfe2ff; color:#084298;',
                            'Cancelled' => 'background:#f8d7da; color:#842029;',
                            default     => 'background:#e0f0ff; color:#0b48a4;'
                        };

                        // Progress step (1=Requested, 2=Reviewed, 3=Confirmed, 4=Completed)
                        $progressStep = match($status) {
                            'Pending'   => 1,
                            'Confirmed' => 3,
                            'Completed' => 4,
                            'Cancelled' => 0,
                            default     => 1
                        };

                        // Tutor initials
                        $nameParts = explode(' ', $bk['tutor_name']);
                        $initials  = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));

                        // Requirement preview
                        $reqPreview = strlen($bk['requirement']) > 70
                            ? substr($bk['requirement'], 0, 70) . '...'
                            : $bk['requirement'];

                        $bookingDate = date('d M Y', strtotime($bk['created_at']));
                        $cardId      = 'booking-' . $bk['id'];
                    ?>
                    <div class="booking-card-item mb-3" data-status="<?php echo $status; ?>" id="<?php echo $cardId; ?>">
                        <div class="p-3 rounded-4 shadow-sm"
                             style="background:rgba(255,255,255,0.92); border-left:4px solid <?php echo $borderColor; ?>; border-top:.5px solid #dee2e6; border-right:.5px solid #dee2e6; border-bottom:.5px solid #dee2e6; transition:.2s;">

                            <!-- Card Top Row -->
                            <div class="d-flex align-items-center gap-3 flex-wrap">

                                <!-- Avatar -->
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                     style="width:46px; height:46px; font-size:15px; <?php echo $avatarStyle; ?>">
                                    <?php echo $initials; ?>
                                </div>

                                <!-- Main Info -->
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

                                <!-- Expand toggle -->
                                <button class="btn btn-sm ms-auto flex-shrink-0"
                                        style="background:rgba(92,143,220,.12); color:#0b48a4; border:none; border-radius:8px; font-size:.78rem; padding:4px 12px;"
                                        onclick="toggleCard('detail-<?php echo $bk['id']; ?>', this)">
                                    View Details ▾
                                </button>
                            </div>

                            <!-- Progress Bar (not for Cancelled) -->
                            <?php if ($status !== 'Cancelled'): ?>
                            <div class="mt-3 px-1">
                                <div class="d-flex align-items-center gap-1" style="font-size:.7rem;">
                                    <?php
                                    $steps = ['Requested', 'Reviewed', 'Confirmed', 'Completed'];
                                    foreach ($steps as $i => $stepLabel):
                                        $stepNum   = $i + 1;
                                        $isDone    = $stepNum <= $progressStep;
                                        $isCurrent = $stepNum === $progressStep;
                                        $dotColor  = $isDone
                                            ? ($status === 'Completed' ? '#0d6efd' : '#198754')
                                            : '#ced4da';
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

                            <!-- Expandable Detail Panel -->
                            <div id="detail-<?php echo $bk['id']; ?>" style="display:none; margin-top:14px; padding-top:12px; border-top:1px dashed #dee2e6;">
                                <div class="row g-2" style="font-size:.85rem;">
                                    <div class="col-md-6">
                                        <div style="background:#f8f9fa; border-radius:8px; padding:10px 14px;">
                                            <div class="fw-semibold text-muted mb-1" style="font-size:.75rem; text-transform:uppercase; letter-spacing:.05em;">Full Requirement</div>
                                            <div style="color:#212529;"><?php echo nl2br(htmlspecialchars($bk['requirement'])); ?></div>
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
                                                    <td class="fw-semibold text-end"><?php echo $bk['duration_months']; ?> month<?php echo $bk['duration_months'] > 1 ? 's' : ''; ?></td>
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

                                <!-- Action Buttons -->
                                <div class="d-flex gap-2 mt-3 flex-wrap">
                                    <?php if ($status === 'Pending'): ?>
                                        <form method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                                            <input type="hidden" name="cancel_booking_id" value="<?php echo $bk['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger px-3 fw-semibold">
                                                ✖ Cancel Booking
                                            </button>
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

                    <!-- No results after filter -->
                    <div id="noFilterResult" class="text-center py-4 text-muted" style="display:none;">
                        <div style="font-size:2.5rem;">🔍</div>
                        <p class="mt-2">No bookings match this filter.</p>
                    </div>

                <?php endif; ?>
                </div>

                <!-- ── JS: Filter tabs + expand/collapse ── -->
                <script>
                    // Filter tabs
                    document.querySelectorAll('.booking-tab').forEach(function(tab) {
                        tab.addEventListener('click', function() {
                            var filter = this.dataset.filter;

                            // Active tab style
                            document.querySelectorAll('.booking-tab').forEach(function(t) {
                                t.classList.remove('active-tab');
                                t.style.opacity = '0.75';
                            });
                            this.classList.add('active-tab');
                            this.style.opacity = '1';
                            if (filter === 'all') {
                                this.style.background = 'linear-gradient(90deg,#5c8fdc,#0b48a4)';
                                this.style.color = '#fff';
                            }

                            var cards  = document.querySelectorAll('.booking-card-item');
                            var visible = 0;
                            cards.forEach(function(card) {
                                if (filter === 'all' || card.dataset.status === filter) {
                                    card.style.display = '';
                                    visible++;
                                } else {
                                    card.style.display = 'none';
                                }
                            });
                            var noResult = document.getElementById('noFilterResult');
                            if (noResult) noResult.style.display = visible === 0 ? 'block' : 'none';
                        });
                    });

                    // Expand / Collapse detail panel
                    function toggleCard(detailId, btn) {
                        var panel = document.getElementById(detailId);
                        if (panel.style.display === 'none') {
                            panel.style.display = 'block';
                            btn.textContent = 'Hide Details ▴';
                        } else {
                            panel.style.display = 'none';
                            btn.textContent = 'View Details ▾';
                        }
                    }
                </script>

                <?php elseif ($page === 'reviews'): ?>

                    <h5>My Reviews</h5>

                <?php elseif ($page === 'complaint'): ?>

                    <h5>Raise Complaint</h5>

                <?php endif; ?>  
            </div>
        </div>
    </div>

    <!-- Booking Modal -->
    <div id="bookingModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.55); z-index:1050; overflow-y:auto;">
        <div style="background:#fff; width:480px; max-width:95%; margin:60px auto; padding:28px 32px; border-radius:14px; box-shadow:0 8px 32px rgba(0,0,0,0.18);">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0" id="modalTitle">📅 Book a Tutor Session</h5>
                <button type="button" onclick="closeBookingModal()" style="background:none;border:none;font-size:1.4rem;line-height:1;cursor:pointer;color:#888;">&times;</button>
            </div>

            <?php if ($bookingSuccess): ?>
                <div class="alert alert-success py-2">✅ <?php echo $bookingSuccess; ?></div>
            <?php endif; ?>
            <?php if ($bookingError): ?>
                <div class="alert alert-danger py-2">⚠️ <?php echo $bookingError; ?></div>
            <?php endif; ?>

            <form method="POST">
                <input type="hidden" name="tutor_id" id="tutor_id">

                <!-- Subject -->
                <label class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                <select name="subject_id" class="form-select mb-3" required>
                    <option value="">— Select Subject —</option>
                    <?php if ($modalSubjects): $modalSubjects->data_seek(0); while ($ms = $modalSubjects->fetch_assoc()): ?>
                        <option value="<?php echo $ms['id']; ?>"
                            <?php if (isset($_POST['subject_id']) && $_POST['subject_id'] == $ms['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($ms['name']); ?>
                        </option>
                    <?php endwhile; endif; ?>
                </select>

                <!-- Requirement -->
                <label class="form-label fw-semibold">Your Requirement <span class="text-danger">*</span></label>
                <textarea name="requirement" class="form-control mb-3" rows="3"
                    placeholder="e.g. Need help with Class 10 Maths — Algebra and Geometry"
                    required><?php echo htmlspecialchars($_POST['requirement'] ?? ''); ?></textarea>

                <!-- Duration -->
                <label class="form-label fw-semibold">Duration (Months) <span class="text-danger">*</span></label>
                <input type="number" name="duration_months" class="form-control mb-4"
                    min="1" max="24" placeholder="e.g. 3"
                    value="<?php echo htmlspecialchars($_POST['duration_months'] ?? ''); ?>"
                    required>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-secondary flex-fill" onclick="closeBookingModal()">Cancel</button>
                    <button type="submit" name="book_tutor" class="btn btn-success flex-fill fw-semibold">
                        ✉️ Send Request
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const toggleBtn  = document.getElementById('toggleBtn');
        const sidebar    = document.getElementById('sidebar');
        const navSpans   = sidebar.querySelectorAll('nav .nav-link span');
        const brandTitle = sidebar.querySelector('.brand-title');
        const subTitle   = sidebar.querySelector('.brand-subtitle');
        const logoutSpan = sidebar.querySelector('.logout-span');

        let isCollapsed = false;

        toggleBtn.addEventListener('click', function () {
            isCollapsed = !isCollapsed;

            if (isCollapsed) {
                sidebar.style.width = '100px';
                sidebar.classList.add('collapsed');      
                navSpans.forEach(s => s.style.display = 'none');
                brandTitle.style.display = 'none';
                subTitle.style.display   = 'none';
                logoutSpan.style.display = 'none';

            } else {
                sidebar.style.width = '300px';
                sidebar.classList.remove('collapsed');   
                navSpans.forEach(s => s.style.display = 'inline');
                brandTitle.style.display = 'block';
                subTitle.style.display   = 'block';
                logoutSpan.style.display = 'inline';
            }
        });
        
        function toggleEdit() {
            const view = document.getElementById('profile-view');
            const form = document.getElementById('edit-form');

            if (view.style.display === 'none') {
                view.style.display = 'block';
                form.style.display = 'none';
            } else {
                view.style.display = 'none';
                form.style.display = 'block';
            }
        }
        window.onload = function () {
            <?php if ($profileError): ?>
                toggleEdit();
            <?php endif; ?>
        };

     
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

        // Auto-open modal if there was a booking error
        <?php if ($bookingError): ?>
            document.addEventListener('DOMContentLoaded', function () {
                document.getElementById('bookingModal').style.display = 'block';
                <?php if (isset($_POST['tutor_id'])): ?>
                    document.getElementById('tutor_id').value = <?php echo intval($_POST['tutor_id']); ?>;
                <?php endif; ?>
            });
        <?php endif; ?>
    </script>
</body>
</html>