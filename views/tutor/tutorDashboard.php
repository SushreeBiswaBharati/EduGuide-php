<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Dashboard – EduGuide</title>
    <link rel="stylesheet" href="/EduGuide-php/assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/EduGuide-php/assets/css/dashboard.css?v=1.1">
    <style>
        /* ── filter badges ── */
        .filter-badge {
            cursor: pointer;
            user-select: none;
            transition: opacity .15s, transform .15s;
            font-size: .85rem;
            padding: 6px 16px;
            border-radius: 99px;
            border: none;
            font-weight: 600;
        }
        .filter-badge:hover  { opacity: .85; transform: translateY(-1px); }
        .filter-badge.active { box-shadow: 0 0 0 3px rgba(255,255,255,.6),
                                           0 0 0 5px rgba(11,72,164,.4); }

        /* ── request table row height ── */
        #requestsTable tbody tr { height: 52px; }
        #requestsTable td       { vertical-align: middle; padding: 8px 10px !important; }
        #requestsTable thead th { padding: 10px !important; white-space: nowrap; }
    </style>
</head>
<body>
<div class="d-flex p-3 gap-3 vh-100">

    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column justify-content-between p-0 rounded-4" id="sidebar">

        <div class="p-3 border-bottom border-white border-opacity-25 d-flex align-items-center justify-content-between">
            <div>
                <div class="fw-bold fs-5 brand-title">EduGuide</div>
                <small class="brand-subtitle" style="opacity:.75;">Tutor Panel</small>
            </div>
            <button class="hamburger-btn" id="toggleBtn">
                <img src="/EduGuide-php/assets/icons/list.svg" alt="menu">
            </button>
        </div>

        <nav class="flex-grow-1 px-3 py-3 d-flex flex-column gap-2 fw-semibold">
            <a href="?page=dashboard" class="nav-link"><small>🏡</small> <span>Home</span></a>
            <a href="?page=profile"   class="nav-link"><small>👤</small> <span>My Profile</span></a>
            <a href="?page=requests"  class="nav-link"><small>📋</small> <span>Student Requests</span></a>
            <a href="?page=schedules" class="nav-link"><small>📅</small> <span>My Schedules</span></a>
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

    <!-- Main Content -->
    <div class="flex-grow-1 main-content p-4 rounded-4 shadow-sm" style="overflow-y:auto;">

        <!-- Home -->
        <?php if ($page === 'dashboard'): ?>

            <div class="mb-4 greet-bar rounded-4 p-4 text-white">
                <span><?php echo $today; ?></span>
                <h4 class="fw-bold mb-1">Welcome Back, <?php echo htmlspecialchars($tutor['name'] ?? 'Tutor'); ?>! 👨‍🏫</h4>
                <p class="fst-italic mb-0">"Teaching is the greatest act of optimism."</p>
            </div>

            <!-- Stat Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-6">
                    <div class="cards card1 text-center px-2 py-3">
                        <div class="fw-semibold">Total Requests</div>
                        <div class="fw-bold fs-4"><?php echo $totalRequests; ?></div>
                        <div style="font-size:.8rem;">All Bookings</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="cards card2 text-center px-2 py-3">
                        <div class="fw-semibold">Pending</div>
                        <div class="fw-bold fs-4"><?php echo $pendingCount; ?></div>
                        <div style="font-size:.8rem;">Awaiting Action</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="cards card3 text-center px-2 py-3">
                        <div class="fw-semibold">Confirmed</div>
                        <div class="fw-bold fs-4"><?php echo $confirmedCount; ?></div>
                        <div style="font-size:.8rem;">Active Sessions</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="cards card4 text-center px-2 py-3">
                        <div class="fw-semibold">Completed</div>
                        <div class="fw-bold fs-4"><?php echo $completedCount; ?></div>
                        <div style="font-size:.8rem;">Sessions Done</div>
                    </div>
                </div>
            </div>

            <!-- Recent Requests Preview -->
            <div class="card p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="fw-bold mb-0">📋 Recent Student Requests</h6>
                    <a href="?page=requests" class="btn btn-sm fw-semibold px-3"
                       style="background:linear-gradient(90deg,#5c8fdc,#0b48a4); color:#fff; border:none;">
                        View All →
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0 align-middle">
                        <thead class="table-dark">
                            <tr><th>#</th><th>Student</th><th>Subject</th><th>Duration</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            <?php
                            $previewCount = 0;
                            if ($requests && $requests->num_rows > 0):
                                $requests->data_seek(0);
                                while ($r = $requests->fetch_assoc() and $previewCount < 5):
                                    $previewCount++;
                                    $bc = $statusBadge[$r['status']] ?? 'bg-secondary';
                            ?>
                                <tr>
                                    <td><?php echo $previewCount; ?></td>
                                    <td class="fw-semibold"><?php echo htmlspecialchars($r['student_name']); ?></td>
                                    <td><?php echo htmlspecialchars($r['subject_name'] ?? '—'); ?></td>
                                    <td><?php echo $r['duration_months']; ?> mo</td>
                                    <td><span class="badge <?php echo $bc; ?>"><?php echo $r['status']; ?></span></td>
                                </tr>
                            <?php endwhile; else: ?>
                                <tr><td colspan="5" class="text-center text-muted py-3">No requests yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <!-- My Profile -->
        <?php elseif ($page === 'profile'): ?>

            <div class="mb-4 greet-bar rounded-4 p-3 text-white">
                <h5 class="fw-bold mb-0">👤 My Profile</h5>
                <small style="opacity:.85;">View and update your tutor information</small>
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
                                $image = !empty($tutor['profile_image'])
                                    ? "/EduGuide-php/assets/profile/" . $tutor['profile_image']
                                    : "/EduGuide-php/assets/default-user.png";
                                ?>
                                <img src="<?php echo $image; ?>" class="profile-img mx-auto mb-2">
                                <h6 class="fw-bold"><?php echo htmlspecialchars($tutor['name']); ?></h6>
                                <small class="text-muted"><?php echo htmlspecialchars($tutor['qualification'] ?? ''); ?></small>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card shadow-sm p-3 h-100">
                                <h6 class="fw-bold mb-3 text-center" style="color:#0b48a4;">Your Details</h6>
                                <table class="table table-sm align-middle mb-0">
                                    <tr>
                                        <td class="fw-semibold text-primary" style="width:40%;">Email</td>
                                        <td class="text-muted"><?php echo htmlspecialchars($tutor['email']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-primary">Gender</td>
                                        <td class="text-muted"><?php echo htmlspecialchars($tutor['gender']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-primary">Qualification</td>
                                        <td class="text-muted"><?php echo htmlspecialchars($tutor['qualification']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-primary">Subjects</td>
                                        <td class="text-muted"><?php echo htmlspecialchars($tutor['subject_names'] ?? '—'); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-primary">Experience</td>
                                        <td class="text-muted"><?php echo $tutor['experience']; ?> yrs</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-primary">Phone</td>
                                        <td class="text-muted"><?php echo htmlspecialchars($tutor['phone']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-primary">Address</td>
                                        <td class="text-muted"><?php echo htmlspecialchars($tutor['address']); ?></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-primary">Availability</td>
                                        <td>
                                            <?php if ($tutor['availability'] === 'Yes'): ?>
                                                <span class="badge bg-success">Available</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Unavailable</span>
                                            <?php endif; ?>
                                        </td>
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

                                <label class="form-label fw-semibold text-primary">Phone</label>
                                <input type="tel" name="edit_phone" class="form-control mb-3"
                                    value="<?php echo htmlspecialchars($tutor['phone']); ?>">

                                <label class="form-label fw-semibold text-primary">Qualification</label>
                                <input type="text" name="edit_qualification" class="form-control mb-3"
                                    value="<?php echo htmlspecialchars($tutor['qualification']); ?>">

                                <label class="form-label fw-semibold text-primary">Experience (years)</label>
                                <input type="number" name="edit_experience" class="form-control mb-3"
                                    value="<?php echo $tutor['experience']; ?>">

                                <label class="form-label fw-semibold text-primary">Address</label>
                                <input type="text" name="edit_address" class="form-control mb-3"
                                    value="<?php echo htmlspecialchars($tutor['address']); ?>">

                                <label class="form-label fw-semibold text-primary">Availability</label>
                                <select name="edit_availability" class="form-control mb-4">
                                    <option value="Yes" <?php echo $tutor['availability'] === 'Yes' ? 'selected' : ''; ?>>Yes</option>
                                    <option value="No"  <?php echo $tutor['availability'] === 'No'  ? 'selected' : ''; ?>>No</option>
                                </select>

                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-secondary flex-fill" onclick="toggleEdit()">Cancel</button>
                                    <button type="submit" class="btn btn-success flex-fill fw-semibold">💾 Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Student Requests -->
        <?php elseif ($page === 'requests'): ?>

            <div class="mb-3 greet-bar rounded-4 p-3 text-white">
                <h5 class="fw-bold mb-0">📋 Student Requests</h5>
                <small style="opacity:.85;">Accept, reject or mark sessions as completed</small>
            </div>

            <!-- Clickable Filter Badges -->
            <div class="d-flex gap-2 flex-wrap mb-3">
                <button class="filter-badge active"
                        style="background:linear-gradient(90deg,#5c8fdc,#0b48a4); color:#fff;"
                        data-filter="all" onclick="filterRequests(this, 'all')">
                    All (<?php echo $totalRequests; ?>)
                </button>
                <button class="filter-badge"
                        style="background:#fff3cd; color:#664d03;"
                        data-filter="Pending" onclick="filterRequests(this, 'Pending')">
                    ⏳ Pending (<?php echo $pendingCount; ?>)
                </button>
                <button class="filter-badge"
                        style="background:#d1e7dd; color:#0f5132;"
                        data-filter="Confirmed" onclick="filterRequests(this, 'Confirmed')">
                    ✔ Confirmed (<?php echo $confirmedCount; ?>)
                </button>
                <button class="filter-badge"
                        style="background:#cfe2ff; color:#084298;"
                        data-filter="Completed" onclick="filterRequests(this, 'Completed')">
                    🎓 Completed (<?php echo $completedCount; ?>)
                </button>
                <button class="filter-badge"
                        style="background:#f8d7da; color:#842029;"
                        data-filter="Cancelled" onclick="filterRequests(this, 'Cancelled')">
                    ✖ Cancelled
                </button>
            </div>

            <?php if ($requests->num_rows === 0): ?>
                <div class="text-center py-5" style="background:rgba(255,255,255,.7); border-radius:14px; border:1.5px dashed #5c8fdc;">
                    <div style="font-size:3rem;">📭</div>
                    <p class="mt-2 text-muted">No booking requests yet.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover bg-white shadow-sm align-middle" id="requestsTable">
                        <thead class="text-center" style="background:linear-gradient(90deg,#5c8fdc,#0b48a4);">
                            <tr>
                                <th class="text-white">#</th>
                                <th class="text-white">Student</th>
                                <th class="text-white">Subject</th>
                                <th class="text-white">Requirement</th>
                                <th class="text-white">Duration</th>
                                <th class="text-white">Date</th>
                                <th class="text-white">Status</th>
                                <th class="text-white">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $requests->data_seek(0);
                            $sno = 1;
                            while ($req = $requests->fetch_assoc()):
                                $bc = $statusBadge[$req['status']] ?? 'bg-secondary';
                                // Left border accent color per status
                                $rowBorder = match($req['status']) {
                                    'Pending'   => '#ffc107',
                                    'Confirmed' => '#198754',
                                    'Completed' => '#0d6efd',
                                    'Cancelled' => '#dc3545',
                                    default     => '#5c8fdc'
                                };
                            ?>
                                <tr class="request-row" data-status="<?php echo $req['status']; ?>"
                                    style="border-left: 4px solid <?php echo $rowBorder; ?>;">
                                    <td class="text-center fw-semibold"><?php echo $sno++; ?></td>
                                    <td class="fw-semibold"><?php echo htmlspecialchars($req['student_name']); ?></td>
                                    <td><?php echo htmlspecialchars($req['subject_name'] ?? '—'); ?></td>
                                    <td style="max-width:200px; font-size:.85rem;">
                                        <?php
                                        $req_text = $req['requirement'] ?? '—';
                                        echo nl2br(htmlspecialchars(
                                            strlen($req_text) > 70 ? substr($req_text, 0, 70) . '…' : $req_text
                                        ));
                                        ?>
                                    </td>
                                    <td class="text-center"><?php echo $req['duration_months'] ?? '—'; ?> mo</td>
                                    <td class="text-center" style="font-size:.82rem; white-space:nowrap;">
                                        <?php echo date('d M Y', strtotime($req['created_at'])); ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge <?php echo $bc; ?> rounded-pill">
                                            <?php echo $req['status']; ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($req['status'] === 'Pending'): ?>
                                            <div class="d-flex gap-1 justify-content-center">
                                                <form method="POST" onsubmit="return confirm('Accept this booking?')">
                                                    <input type="hidden" name="booking_id"     value="<?php echo $req['id']; ?>">
                                                    <input type="hidden" name="booking_action" value="Confirmed">
                                                    <button type="submit" class="btn btn-success btn-sm fw-semibold px-2">✔ Accept</button>
                                                </form>
                                                <form method="POST" onsubmit="return confirm('Reject this booking?')">
                                                    <input type="hidden" name="booking_id"     value="<?php echo $req['id']; ?>">
                                                    <input type="hidden" name="booking_action" value="Cancelled">
                                                    <button type="submit" class="btn btn-danger btn-sm fw-semibold px-2">✘ Reject</button>
                                                </form>
                                            </div>
                                        <?php elseif ($req['status'] === 'Confirmed'): ?>
                                            <form method="POST" onsubmit="return confirm('Mark as Completed? This cannot be undone.')">
                                                <input type="hidden" name="booking_id"     value="<?php echo $req['id']; ?>">
                                                <input type="hidden" name="booking_action" value="Completed">
                                                <button type="submit" class="btn btn-primary btn-sm fw-semibold px-2">🎓 Complete</button>
                                            </form>
                                        <?php else: ?>
                                            <span class="text-muted small">—</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div id="noFilterMsg" class="text-center py-4 text-muted" style="display:none;">
                    <div style="font-size:2rem;">🔍</div>
                    <p class="mt-1">No requests match this filter.</p>
                </div>
            <?php endif; ?>

        <!-- My schedule -->
        <?php elseif ($page === 'schedules'): ?>

            <div class="mb-3 greet-bar rounded-4 p-3 text-white">
                <h5 class="fw-bold mb-0">📅 My Schedules</h5>
                <small style="opacity:.85;">Your confirmed active sessions</small>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle bg-white shadow-sm">
                    <thead class="text-white" style="background:linear-gradient(90deg,#5c8fdc,#0b48a4);">
                        <tr>
                            <th class="text-white">#</th>
                            <th class="text-white">Student</th>
                            <th class="text-white">Subject</th>
                            <th class="text-white">Booked On</th>
                            <th class="text-white">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($schedule && $schedule->num_rows > 0):
                            $n = 1; while ($sc = $schedule->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $n++; ?></td>
                                <td class="fw-semibold"><?php echo htmlspecialchars($sc['student_name']); ?></td>
                                <td><?php echo htmlspecialchars($sc['subject_name'] ?? '—'); ?></td>
                                <td><?php echo date('d M Y', strtotime($sc['created_at'])); ?></td>
                                <td><span class="badge bg-success">Confirmed</span></td>
                            </tr>
                        <?php endwhile; else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <div style="font-size:2rem;">📭</div>
                                    No confirmed sessions yet.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        <!-- My Reviews -->
        <?php elseif ($page === 'reviews'): ?>

            <div class="mb-3 greet-bar rounded-4 p-3 text-white d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h5 class="fw-bold mb-0">⭐ My Reviews</h5>
                    <small style="opacity:.85;">What students say about you</small>
                </div>
                <div class="d-flex gap-2">
                    <div class="text-center px-3 py-1 rounded-3" style="background:rgba(255,255,255,.2);">
                        <div class="fw-bold fs-5">⭐ <?php echo $avgRating; ?></div>
                        <div style="font-size:.75rem; opacity:.85;">Avg Rating</div>
                    </div>
                    <div class="text-center px-3 py-1 rounded-3" style="background:rgba(255,255,255,.2);">
                        <div class="fw-bold fs-5"><?php echo $totalReviews; ?></div>
                        <div style="font-size:.75rem; opacity:.85;">Total Reviews</div>
                    </div>
                </div>
            </div>

            <?php if ($reviews && $reviews->num_rows > 0): ?>
                <div class="row g-3">
                    <?php while ($rv = $reviews->fetch_assoc()): ?>
                        <div class="col-md-6">
                            <div class="card shadow-sm p-3 h-100" style="border-left:4px solid #ffc107;">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="fw-bold" style="color:#0b48a4;">
                                        <?php echo htmlspecialchars($rv['student_name']); ?>
                                    </div>
                                    <div>
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span style="color:<?php echo $i <= $rv['rating'] ? '#ffc107' : '#dee2e6'; ?>; font-size:1rem;">★</span>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <p class="text-muted mb-1" style="font-size:.88rem; font-style:italic;">
                                    "<?php echo htmlspecialchars($rv['comment'] ?? 'No comment.'); ?>"
                                </p>
                                <small class="text-muted"><?php echo date('d M Y', strtotime($rv['created_at'])); ?></small>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5" style="background:rgba(255,255,255,.7); border-radius:14px; border:1.5px dashed #ffc107;">
                    <div style="font-size:3rem;">⭐</div>
                    <p class="mt-2 text-muted">No reviews yet. Complete sessions to receive ratings!</p>
                </div>
            <?php endif; ?>

        <!-- Raise Complaints -->
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

<script src="/EduGuide-php/assets/bootstrap/bootstrap.bundle.min.js"></script>
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

    // ── Profile edit toggle ──
    function toggleEdit() {
        const view = document.getElementById('profile-view');
        const form = document.getElementById('edit-form');
        if (view && form) {
            const showForm = view.style.display !== 'none';
            view.style.display = showForm ? 'none' : 'block';
            form.style.display = showForm ? 'block' : 'none';
        }
    }

    // ── Auto-open edit form on profile error ──
    <?php if (!empty($profileError) && $page === 'profile'): ?>
        window.addEventListener('DOMContentLoaded', toggleEdit);
    <?php endif; ?>

    // ── Filter badges on requests page ──
    function filterRequests(btn, filter) {
        // Update active badge style
        document.querySelectorAll('.filter-badge').forEach(b => {
            b.classList.remove('active');
            b.style.opacity = '.75';
        });
        btn.classList.add('active');
        btn.style.opacity = '1';

        // Show/hide rows
        const rows    = document.querySelectorAll('.request-row');
        let   visible = 0;
        rows.forEach(function(row) {
            const match = filter === 'all' || row.dataset.status === filter;
            row.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        const noMsg = document.getElementById('noFilterMsg');
        if (noMsg) noMsg.style.display = visible === 0 ? 'block' : 'none';
    }
</script>
</body>
</html>