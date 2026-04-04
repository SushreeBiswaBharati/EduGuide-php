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
    <div class="flex-grow-1 main-content p-4 rounded-4 shadow-sm" style="overflow-y:auto;">
        <?php if ($page === 'dashboard'): ?>
            <!-- Dashboard -->
            <div class="mb-4 greet-bar rounded-4 p-4 text-white">
                <span><?php echo date('l, F j, Y');?></span>
                <h4 class="fw-bold mb-1">
                    Welcome Back 👑
                </h4>
                <p class="fst-italic mb-3">"You do't just manage users — you shape the platform."</p>
            </div>
            <!--  -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-6">
                    <div class="card text-center p-3 shadow-sm">
                        <div class="fw-semibold">Total Tutors</div>
                        <div class="fw-bold fs-4"><?php echo $totalTutors; ?></div>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="card text-center p-3 shadow-sm">
                        <div class="fw-semibold">Total Students</div>
                        <div class="fw-bold fs-4"><?php echo $totalStudents; ?></div>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="card text-center p-3 shadow-sm">
                        <div class="fw-semibold">Bookings</div>
                        <div class="fw-bold fs-4"><?php echo $totalBookings; ?></div>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="card text-center p-3 shadow-sm">
                        <div class="fw-semibold">Complaints</div>
                        <div class="fw-bold fs-4"><?php echo $totalComplaints; ?></div>
                    </div>
                </div>

            </div>
            <div class="container-fluid">

    <!-- Welcome -->
    <div class="mb-4 p-3 bg-primary text-white rounded">
        <h4 class="mb-1">Welcome Admin 👑</h4>
        <small><?php echo date('l, F j, Y'); ?></small>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">

        <div class="col-md-3 col-6">
            <div class="card text-center p-3">
                <div>Total Students</div>
                <h5><?php echo $totalStudents; ?></h5>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card text-center p-3">
                <div>Total Tutors</div>
                <h5><?php echo $totalTutors; ?></h5>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card text-center p-3">
                <div>Total Bookings</div>
                <h5><?php echo $totalBookings; ?></h5>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card text-center p-3">
                <div>Complaints</div>
                <h5><?php echo $totalComplaints; ?></h5>
            </div>
        </div>

    </div>

    <!-- Charts -->
    <div class="row g-3 mb-4">

        <div class="col-md-6">
            <div class="card p-3">
                <h6>Bookings Growth</h6>
                <canvas id="bookingChart"></canvas>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-3">
                <h6>User Registrations</h6>
                <canvas id="userChart"></canvas>
            </div>
        </div>

    </div>

    <!-- Recent Activity -->
    <div class="card p-3 mb-4">
        <h6>Recent Activity</h6>
        <ul class="list-group list-group-flush">
            <?php while($a = $activities->fetch_assoc()): ?>
                <li class="list-group-item">
                    <?php echo $a['message']; ?>
                    <small class="text-muted float-end"><?php echo $a['time']; ?></small>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>

    <!-- Top Tutors -->
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

</div>

                <?php elseif ($page === 'tutor'): ?>
                    <h5>My Bookings Requests</h5>
                
                <?php elseif ($page === 'student'): ?>

                    <h5>My Bookings</h5>

                <?php elseif ($page === 'booking'): ?>

                    <h5>My Reviews</h5>
                <?php elseif ($page === 'dropdown'): ?>

                    <h5>My Reviews</h5>

                <?php elseif ($page === 'complaint'): ?>

                    <h5>Raise Complaint</h5>

                <?php endif; ?>
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
    </script>
</body>
</html>