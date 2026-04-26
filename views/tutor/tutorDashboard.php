<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Dashboard – EduGuide</title>
    <link rel="stylesheet" href="/EduGuide-php/assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/EduGuide-php/assets/css/dashboard.css?v=1.1">
    
</head>
<body>
<div class="d-flex p-3 gap-3 vh-100">
    
    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column justify-content-between p-0 rounded-4" id="sidebar">

        <div class="p-3 border-bottom border-white border-opacity-25 d-flex align-items-center justify-content-between">
            <div>
                <div class="fw-bold fs-5 brand-title">EduGuide</div>
                <small class="brand-subtitle" style="opacity:0.75;">Tutor Panel</small>
            </div>
            <button class="hamburger-btn" id="toggleBtn">
                <img src="/EduGuide-php/assets/icons/list.svg" alt="menu">
            </button>
        </div>

        <!-- Nav Links -->
        <nav class="flex-grow-1 px-3 py-3 d-flex flex-column gap-2 fw-semibold">

            <a href="?page=dashboard" class="nav-link active">
                <small>🏡</small> <span>Dashboard</span>
            </a>

            <a href="?page=profile" class="nav-link">
                <small>👤</small> <span>My Profile</span>
            </a>

            <a href="?page=requests" class="nav-link">
                <small>🔍</small> <span>Booking Requests</span>
            </a>

            <a href="?page=schedules" class="nav-link">
                <small>📅</small> <span>My Schedules</span>
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

    <!-- Main Content -->
    <div class="flex-grow-1 main-content p-4 rounded-4 shadow-sm">
        <?php if ($page === 'dashboard'): ?>
            <!-- Dashboard -->
            <div class="mb-4 greet-bar rounded-4 p-4 text-white">
                <span><?php echo $today; ?></span>
                <h4 class="fw-bold mb-1">Welcome Back, <?php echo htmlspecialchars($tutor['name'] ?? 'Tutor'); ?>! 👨‍🏫</h4>
                <p class="fst-italic mb-3">"Teaching is the greatest act of optimism."</p>
                <span class="badge bg-white bg-opacity-75 text-primary px-3 py-2">✅ Verified Tutor</span>
            </div>

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

                <?php elseif ($page === 'profile'): ?>
                <div class="mb-4 greet-bar rounded-4 p-3 text-white">
                    <h5 class="fw-bold mb-0">👤 My Profile</h5>
                    <small style="opacity:.85;">View and update your tutor information</small>
                </div>
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

                                <div class=" my-auto">
                                    <?php
                                    $image = !empty($tutor['profile_image'])
                                                ? "/EduGuide-php/assets/profile/" . $tutor['profile_image']
                                                : "/EduGuide-php/assets/default-user.png";
                                    ?>
                                    <img src="<?php echo $image; ?>" class="profile-img">
                                </div>

                                <h6 class="fw-bold my-2"><?php echo $tutor['name']; ?></h6>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="shadow-sm p-3 h-100">

                                <h6 class="fw-bold fs-4 mb-3 text-center text-warning">Your Details</h6>

                                <div class="table-responsive">
                                    <table class="table table-sm align-middle mb-0">

                                        <tr>
                                            <td class="fw-semibold text-primary">Email</td>
                                            <td class="fw-semibold text-muted"><?php echo $tutor['email']; ?></td>
                                        </tr>

                                        <tr>
                                            <td class="fw-semibold text-primary">Gender</td>
                                            <td class="fw-semibold text-muted"><?php echo $tutor['gender']; ?></td>
                                        </tr>

                                        <tr>
                                            <td class="fw-semibold text-primary">Qualification</td>
                                            <td class="fw-semibold text-muted"><?php echo $tutor['qualification']; ?></td>
                                        </tr>

                                        <tr>
                                            <td class="fw-semibold text-primary">Subjects</td>
                                            <td class="fw-semibold text-muted">
                                                <?php echo $tutor['subject_names']?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="fw-semibold text-primary">Experience</td>
                                            <td class="fw-semibold text-muted"><?php echo $tutor['experience']; ?> yrs</td>
                                        </tr>

                                        <tr>
                                            <td class="fw-semibold text-primary">Phone</td>
                                            <td class="fw-semibold text-muted"><?php echo $tutor['phone']; ?></td>
                                        </tr>

                                        <tr>
                                            <td class="fw-semibold text-primary">Address</td>
                                            <td class="fw-semibold text-muted"><?php echo $tutor['address']; ?></td>
                                        </tr>

                                        <tr>
                                            <td class="fw-semibold text-primary">Availability</td>
                                            <td class="fw-semibold text-muted"><?php echo $tutor['availability']; ?></td>
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

                                    <!-- Profile Image -->
                                    <label class="form-label fw-semibold fs-5 text-primary">Profile Photo</label>
                                    <input type="file" name="profile_image" class="form-control mb-3" accept="image/*">

                                    <label class="form-label fw-semibold fs-5 text-primary">Phone</label>
                                    <input type="tel" name="edit_phone" class="form-control mb-3"
                                        value="<?php echo $tutor['phone']; ?>">

                                    <label class="form-label fw-semibold fs-5 text-primary">Qualification</label>
                                    <input type="text" name="edit_qualification" class="form-control mb-3"
                                        value="<?php echo $tutor['qualification']; ?>">

                                    <label class="form-label fw-semibold fs-5 text-primary">Experience (years)</label>
                                    <input type="number" name="edit_experience" class="form-control mb-3"
                                        value="<?php echo $tutor['experience']; ?>">

                                    <label class="form-label fw-semibold fs-5 text-primary">Address</label>
                                    <input type="text" name="edit_address" class="form-control mb-3"
                                        value="<?php echo $tutor['address']; ?>">

                                    <label class="form-label fw-semibold fs-5 text-primary">Availability</label>
                                    <select name="edit_availability" class="form-control mb-3">
                                        <option value="Yes" <?php if($tutor['availability']=='Yes') echo 'selected'; ?>>Yes</option>
                                        <option value="No" <?php if($tutor['availability']=='No') echo 'selected'; ?>>No</option>
                                    </select>

                                    <div class="d-flex gap-2 mt-3">
                                        <button type="button" class="btn btn-secondary" onclick="toggleEdit()">Cancel</button>
                                        <button type="submit" class="btn btn-success">Save</button>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>

                <?php elseif ($page === 'requests'): ?>
                    <div class="mb-4 greet-bar rounded-4 p-3 text-white d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h5 class="fw-bold mb-0">📋 Booking Requests</h5>
                            <small style="opacity:.85;">Accept, reject or complete your student session requests</small>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="badge bg-white text-warning">⏳ Pending: <?php echo $pendingCount; ?></span>
                            <span class="badge bg-white text-success">✔ Confirmed: <?php echo $confirmedCount; ?></span>
                            <span class="badge bg-white text-primary">🎓 Completed: <?php echo $completedCount; ?></span>
                        </div>
                    </div>

                    <?php if ($requests->num_rows === 0): ?>
                        <div class="text-center py-5 text-muted">
                            <div style="font-size:3rem;">📭</div>
                            <p class="mt-2">No booking requests yet.</p>
                        </div>

                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover bg-white shadow-sm align-middle">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Student</th>
                                        <th>Subject</th>
                                        <th>Requirement</th>
                                        <th>Duration</th>
                                        <th>Requested On</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sno = 1; while ($req = $requests->fetch_assoc()): ?>
                                        <tr>
                                            <td class="text-center"><?php echo $sno++; ?></td>
                                            <td class="fw-semibold"><?php echo htmlspecialchars($req['student_name']); ?></td>
                                            <td><?php echo htmlspecialchars($req['subject_name'] ?? '—'); ?></td>
                                            <td style="max-width:220px;">
                                                <?php echo nl2br(htmlspecialchars($req['requirement'] ?? '—')); ?>
                                            </td>
                                            <td class="text-center"><?php echo $req['duration_months'] ?? '—'; ?> mo</td>
                                            <td class="text-center">
                                                <?php echo date('d M Y', strtotime($req['created_at'])); ?>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge <?php echo $statusBadge[$req['status']] ?? 'bg-secondary'; ?>">
                                                    <?php echo $req['status']; ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($req['status'] === 'Pending'): ?>
                                                    <div class="d-flex gap-1 justify-content-center">
                                                        <form method="POST" onsubmit="return confirm('Accept this booking request?')">
                                                            <input type="hidden" name="booking_id" value="<?php echo $req['id']; ?>">
                                                            <input type="hidden" name="booking_action" value="Confirmed">
                                                            <button type="submit" class="btn btn-success btn-sm">✔ Accept</button>
                                                        </form>
                                                        <form method="POST" onsubmit="return confirm('Reject this booking request?')">
                                                            <input type="hidden" name="booking_id" value="<?php echo $req['id']; ?>">
                                                            <input type="hidden" name="booking_action" value="Cancelled">
                                                            <button type="submit" class="btn btn-danger btn-sm">✘ Reject</button>
                                                        </form>
                                                    </div>
                                                <?php elseif ($req['status'] === 'Confirmed'): ?>
                                                    <form method="POST" onsubmit="return confirm('Mark this session as Completed? This cannot be undone.')">
                                                        <input type="hidden" name="booking_id" value="<?php echo $req['id']; ?>">
                                                        <input type="hidden" name="booking_action" value="Completed">
                                                        <button type="submit" class="btn btn-primary btn-sm">🎓 Mark Complete</button>
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
                    <?php endif; ?>
                
                <?php elseif ($page === 'schedules'): ?>

                    <h5>My Bookings</h5>

                <?php elseif ($page === 'reviews'): ?>

                    <h5>My Reviews</h5>

                <?php elseif ($page === 'complaint'): ?>

                    <h5>Raise Complaint</h5>

                <?php endif; ?>
            </div>
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
    </script>
</body>
</html>