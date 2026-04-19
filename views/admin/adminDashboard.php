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

    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column justify-content-between p-0 rounded-4" id="sidebar">

        <div class="p-3 border-bottom border-white border-opacity-25 d-flex align-items-center justify-content-between">
            <div>
                <div class="fw-bold fs-5 brand-title">EduGuide</div>
                <small class="brand-subtitle" style="opacity:0.75;">Admin Panel</small>
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

            <a href="?page=manage_tutor" class="nav-link">
                <small>🧑🏻‍🏫</small> <span>Manage Tutors</span>
            </a>

            <a href="?page=manage_student" class="nav-link">
                <small>🧑🏻‍🎓</small> <span>Manage Students</span>
            </a>

            <a href="?page=booking" class="nav-link">
                <small>📅</small> <span>View Bookings</span>
            </a>

            <a href="?page=dropdown" class="nav-link">
                <small>⚙️</small> <span>Manage Dropdowns</span>
            </a>

            <a href="?page=complaint" class="nav-link">
                <small>📢</small> <span>Handle Complaints</span>
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
            <div class="mb-4 greet-bar rounded-4 p-4 text-white">
                <span><?php echo date('l, F j, Y');?></span>
                <h4 class="fw-bold mb-1">
                    Welcome Back Admin! 👑
                </h4>
                <p class="fst-italic mb-3">"You do't just manage users — you shape the platform."</p>
            </div>
            <!-- Dashboard Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-6">
                    <div class="cards text-center px-2 py-3">
                        <div class="fw-semibold">Total Tutors</div>
                        <div class="fw-bold fs-4"><?php echo $totalTutors; ?></div>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="cards text-center px-2 py-3 ">
                        <div class="fw-semibold">Total Students</div>
                        <div class="fw-bold fs-4"><?php echo $totalStudents; ?></div>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="cards text-center px-2 py-3 ">
                        <div class="fw-semibold">Bookings</div>
                        <div class="fw-bold fs-4"><?php echo $totalBookings; ?></div>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="cards text-center px-2 py-3 ">
                        <div class="fw-semibold">Complaints</div>
                        <div class="fw-bold fs-4"><?php echo $totalComplaints; ?></div>
                    </div>
                </div>
            </div>
            <!-- Charts -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="card p-3">
                        <h5 class="mb-3 text-center fw-bold">Bookings Growth</h5>
                        <div class="mb-2">
                            <small class="fw-semibold">Today (<?php echo $todayBookings; ?>)</small>
                            <div class="progress">
                                <div class="progress-bar bg-success progress-bar-striped"
                                    style="width: <?php echo $todayBookings * 10; ?>%">
                                </div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <small class="fw-semibold">Yesterday (<?php echo $yesterdayBookings; ?>)</small>
                            <div class="progress">
                                <div class="progress-bar bg-success progress-bar-striped"
                                    style="width: <?php echo $yesterdayBookings * 10; ?>%">
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="fs-6 fw-semibold">Growth:
                                <span class="<?php echo $bookingGrowth >= 0 ? 'text-success' : 'text-danger'; ?>">
                                    <?php echo $bookingGrowth; ?>%
                                </span>
                            </small>
                        </div>
                        <small class="text-muted mt-2 fw-semibold fs-6">
                            <?php echo $todayBookings > $yesterdayBookings ? '📈 Increasing bookings' : '📉 Drop in bookings'; ?>
                        </small>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card p-3">
                        <h5 class="mb-3 text-center fw-bold">Tutor Registration</h5>
                        <div class="mb-2">
                            <?php
                                $todayRegs     = $todayRegs ?? 0;
                                $yesterdayRegs = $yesterdayRegs ?? 0;
                                $growth        = $growth ?? 0;
                            ?>
                                <small class="fw-semibold">Today (<?php echo $todayRegs; ?>)</small>
                                <div class="progress">
                                    <div class="progress-bar bg-success progress-bar-striped"
                                        style="width: <?php echo $todayRegs * 10; ?>%">
                                    </div>
                                </div>

                                <small class="fw-semibold">Yesterday (<?php echo $yesterdayRegs; ?>)</small>
                                <div class="progress">
                                    <div class="progress-bar bg-warning progress-bar-striped"
                                        style="width: <?php echo $yesterdayRegs * 10; ?>%">
                                    </div>
                                </div>

                                <div class="mt-2">
                                    <small class="fs-6 fw-semibold">Growth:
                                        <span class="<?php echo $growth >= 0 ? 'text-success' : 'text-danger'; ?>">
                                            <?php echo round($growth, 2); ?>%
                                        </span>
                                    </small>
                                </div>

                                <small class="text-muted mt-2 fw-semibold fs-6">
                                    <?php echo $todayRegs > $yesterdayRegs ? '📈 Increasing registration' : '📉 Drop in registration'; ?>
                                </small>
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

        <?php elseif ($page === 'manage_tutor'): ?>
            <h5 class="fw-bold text-primary mb-3">Manage Tutors</h5>

            <form method="GET" action="AdminDashboardController.php" class="row g-2 mb-3">
               <input type="hidden" name="page" value="manage_tutor">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control" placeholder="search by name, subject..." value="<?php echo $_GET['search'] ?? ''; ?>">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="0">Requested</option>
                        <option value="1">Verified</option>
                        <option value="2">Rejected</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort" class="form-control">
                        <option value="">Sort</option>
                        <option value="rating">Highest Rating</option>
                        <option value="exp">Experience</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-success w-100">Search</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                       <?php if($tutors->num_rows > 0): ?>
                    <?php while($t = $tutors->fetch_assoc()): ?>

                        <?php
                        // Ensure safe profile image path
                        $photo = '/EduGuide-php/assets/default-user.png';
                        if (!empty($t['profile_image'])) {
                            $profilePath = $_SERVER['DOCUMENT_ROOT'] . '/EduGuide-php/assets/profile/' . $t['profile_image'];
                            if (file_exists($profilePath)) {
                                $photo = '/EduGuide-php/assets/profile/' . $t['profile_image'];
                            }
                        }
                        ?>

                        <tr class="tutor-row"
                            data-id="<?php echo $row['id']; ?>"
                            data-name="<?php echo htmlspecialchars($row['name'], ENT_QUOTES); ?>"
                            data-email="<?php echo htmlspecialchars($row['email'], ENT_QUOTES); ?>"
                            data-phone="<?php echo htmlspecialchars($row['phone'], ENT_QUOTES); ?>"
                            data-address="<?php echo htmlspecialchars($row['address'], ENT_QUOTES); ?>"
                            data-verified="<?php echo $row['verified']; ?>"
                            data-photo="<?php echo htmlspecialchars($photoPath, ENT_QUOTES); ?>"
                            onclick="openModal(this)">
                            <td><?= htmlspecialchars($t['name']) ?></td>
                            <td><?= htmlspecialchars($t['email']) ?></td>
                            <td>
                                <?php if($t['is_verified']): ?>
                                    <span class="badge bg-success">Verified</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Requested</span>
                                <?php endif; ?>
                            </td>
                        </tr>

                    <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">No tutor found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Tutor Modal -->
        <div class="modal fade" id="tutorModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content p-3">
                    <div class="modal-header">
                        <h5 class="modal-title">Tutor Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="modalContent"></div>
                    <div class="modal-footer" id="modalFooter"></div>
                </div>
            </div>
        </div>

        <?php elseif ($page === 'manage_student'): ?>
            <h5 class="fw-bold text-primary mb-3">Manage Students</h5>
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($students->num_rows > 0): ?>
                        <?php while ($s = $students->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $s['id']; ?></td>
                                <td><?php echo htmlspecialchars($s['name']); ?></td>
                                <td><?php echo htmlspecialchars($s['email']); ?></td>
                                <td>
                                    <button class="btn btn-danger btn-sm"
                                        onclick="deleteStudent(<?php echo $s['id']; ?>)">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">No students found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        <?php elseif ($page === 'booking'): ?>
            <h5 class="fw-bold text-primary mb-3">Booking Details</h5>

            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (isset($bookings) && $bookings && $bookings->num_rows > 0): ?>
                    
                    <?php while($b = $bookings->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $b['id']; ?></td>
                            <td><?php echo htmlspecialchars($b['student_name']); ?></td>
                            <td><?php echo $b['created_at']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted">No bookings found</td>
                    </tr>

                <?php endif; ?>
                </tbody>
            </table>

        <?php elseif ($page === 'dropdown'): ?>
    <h5 class="fw-bold text-primary mb-3">Manage Dropdowns</h5>

    <!-- Add Dropdown -->
    <form method="POST" action="AdminDashboardController.php?page=dropdown" class="row g-2 mb-3">
        <div class="col-md-4">
            <select name="type" class="form-control">
                <option value="type">Type</option>
                <option value="subject">Subject</option>
                <option value="class">Class</option>
            </select>
        </div>
        <div class="col-md-6">
            <input type="text" name="value" class="form-control" placeholder="Enter value">
        </div>
        <div class="col-md-2">
            <button class="btn btn-success w-100">Add</button>
        </div>
    </form>

    <!-- Dropdown List -->
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($dropdowns->num_rows > 0): ?>
                    <?php while ($d = $dropdowns->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $d['type']; ?></td>
                            <td><?php echo htmlspecialchars($d['value']); ?></td>
                            <td>
                                <button class="btn btn-danger btn-sm"
                                    onclick="deleteDropdown(<?php echo $d['id']; ?>)">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted">No data found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <!-- ✅ ADD THIS HERE (IMPORTANT POSITION) -->
        <div class="card p-3 mb-3">
            <h5 class="fw-bold text-primary">Manage Subjects</h5>

            <table class="table table-hover mt-2">
                <thead class="table-dark">
                    <tr>
                        <th>Subject</th>
                        <th>Used By Tutors</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($subjects->num_rows > 0): ?>
                        <?php while ($s = $subjects->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($s['name']); ?></td>

                                <td>
                                    <span class="badge bg-info">
                                        <?php echo $s['tutor_count']; ?>
                                    </span>
                                </td>

                                <td>
                                    <button class="btn btn-danger btn-sm"
                                        onclick="deleteStudent(<?php echo $s['id']; ?>)">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                No subjects found
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php elseif ($page === 'complaint'): ?>
            <h5 class="fw-bold text-primary mb-3">Check Complaints</h5>

            <div class="card p-3">
                <h5 class="mb-3 fw-bold">Complaints</h5>

                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Student</th>
                            <th>Tutor</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($complaints->num_rows > 0): ?>
                            <?php while ($c = $complaints->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $c['id']; ?></td>
                                    <td><?php echo htmlspecialchars($c['student_name']); ?></td>
                                    <td><?php echo htmlspecialchars($c['tutor_name']); ?></td>
                                    <td><?php echo htmlspecialchars($c['message']); ?></td>
                                    <td>
                                        <?php if ($c['status'] == 1): ?>
                                            <span class="badge bg-success">Resolved</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($c['status'] == 0): ?>
                                            <button class="btn btn-sm btn-success" onclick="resolveComplaint(<?php echo $c['id']; ?>)">Resolve</button>
                                        <?php endif; ?>
                                        <button class="btn btn-sm btn-danger" onclick="deleteComplaint(<?php echo $c['id']; ?>)">Delete</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">No complaints found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="/EduGuide-php/assets/bootstrap/bootstrap.bundle.min.js"></script>
<script>
    // =================== SIDEBAR TOGGLE ===================
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
            navSpans.forEach(s => s.style.display = 'none');
            brandTitle.style.display = 'none';
            subTitle.style.display = 'none';
            logoutSpan.style.display = 'none';
        } else {
            sidebar.style.width = '300px';
            navSpans.forEach(s => s.style.display = 'inline');
            brandTitle.style.display = 'block';
            subTitle.style.display = 'block';
            logoutSpan.style.display = 'inline';
        }
    });

    // =================== OPEN TUTOR MODAL ===================
    function openModal(row) {
    const tutor = {
        id: row.dataset.id,
        name: row.dataset.name,
        email: row.dataset.email,
        phone: row.dataset.phone,
        subjects: row.dataset.subjects,
        experience: row.dataset.experience,
        rating: row.dataset.rating,
        address: row.dataset.address,
        is_verified: parseInt(row.dataset.verified),
        photo: row.dataset.photo
    };

    const content = `
        <div class="row">
            
            <!-- LEFT SIDE – DETAILS -->
            <div class="col-md-8">
                <h5 class="fw-bold">${tutor.name}</h5>
                <p><strong>Email:</strong> ${tutor.email}</p>
                <p><strong>Phone:</strong> ${tutor.phone}</p>
                <p><strong>Subjects:</strong> ${tutor.subjects}</p>
                <p><strong>Experience:</strong> ${tutor.experience} years</p>
                <p><strong>Rating:</strong> ${tutor.rating}</p>
                <p><strong>Address:</strong> ${tutor.address}</p>
                <p><strong>Status:</strong> 
                    ${tutor.is_verified ? 
                        '<span class="badge bg-success">Verified</span>' : 
                        '<span class="badge bg-warning text-dark">Requested</span>'}
                </p>
            </div>

            <!-- RIGHT SIDE – PROFILE PHOTO -->
            <div class="col-md-4 text-center">
                <img src="${tutor.photo}" 
                    class="img-fluid rounded shadow"
                    style="width: 160px; height: 160px; object-fit: cover;"
                    onerror="this.src='/EduGuide-php/assets/default-user.png'">
            </div>

        </div>
    `;

    document.getElementById('modalContent').innerHTML = content;

    const footer = document.getElementById('modalFooter');
    if (tutor.is_verified === 0) {
        footer.innerHTML = `
            <button class="btn btn-success" onclick="verifyTutor(${tutor.id})">Accept</button>
            <button class="btn btn-danger" onclick="rejectTutor(${tutor.id})">Reject</button>
        `;
    } else {
        footer.innerHTML = `<button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>`;
    }

    const modal = new bootstrap.Modal(document.getElementById('tutorModal'));
    modal.show();
}

    function verifyTutor(id) {
    fetch("/EduGuide-php/controllers/AdminDashboardController.php?page=manage_tutor", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "action=verify&id=" + id
    })
    .then(res => res.text())
    .then(data => {
        if (data.trim() === "success") {
            alert("Tutor Verified Successfully");
            location.reload();
        } else {
            alert("Error verifying tutor: " + data);
        }
    })
    .catch(err => console.error(err));
}

function rejectTutor(id) {
    fetch("/EduGuide-php/controllers/AdminDashboardController.php?page=manage_tutor", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "action=reject&id=" + id
    })
    .then(res => res.text())
    .then(data => {
        if (data.trim() === "success") {
            alert("Tutor Rejected Successfully");
            location.reload();
        } else {
            alert("Error rejecting tutor: " + data);
        }
    })
    .catch(err => console.error(err));
}
function resolveComplaint(id) {
    fetch("/EduGuide-php/controllers/AdminDashboardController.php?page=complaint", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "action=resolve&id=" + id
    })
    .then(res => res.text())
    .then(data => {
        if (data.trim() === "success") {
            alert("Complaint resolved successfully");
            location.reload();
        } else {
            alert("Error resolving complaint: " + data);
        }
    });
}

function deleteComplaint(id) {
    if (!confirm("Are you sure you want to delete this complaint?")) return;

    fetch("/EduGuide-php/controllers/AdminDashboardController.php?page=complaint", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "action=delete&id=" + id
    })
    .then(res => res.text())
    .then(data => {
        if (data.trim() === "success") {
            alert("Complaint deleted successfully");
            location.reload();
        } else {
            alert("Error deleting complaint: " + data);
        }
    });
}

    function deleteStudent(id) {
        if (!confirm("Are you sure you want to delete this student?")) return;

        fetch("/EduGuide-php/controllers/AdminDashboardController.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "action=delete_student&id=" + id
        })
        .then(res => res.text())
        .then(data => {
            console.log("Delete response:", data);

            if (data.trim() === "success") {
                alert("Student deleted successfully");
                location.reload();
            } else {
                alert("Delete failed: " + data);
            }
        })
        .catch(err => {
            console.error(err);
            alert("Server error");
        });
    }

    function deleteDropdown(id) {
        fetch("/EduGuide-php/controllers/AdminDashboardController.php?page=dropdown", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "action=delete_dropdown&id=" + id
        })
        .then(res => res.text())
        .then(data => {
            if (data.trim() === "success") {
                alert("Deleted");
                location.reload();
            }
        });
    }
</script>
</body>
</html>

