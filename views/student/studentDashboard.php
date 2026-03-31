<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="/EduGuide-php/assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/EduGuide-php/assets/css/dashboard.css ? v=1.1">
</head>
<body>
    <div class="d-flex p-3 gap-3 vh-100">

        <!-- Sidebar-->
        <div class="sidebar d-flex flex-column justify-content-between p-0 rounded-4" id="sidebar">
             <?php
                if (isset($view)) {
                    include __DIR__ . '/' . $view;
                }
            ?>
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

                <a href="/EduGuide-php/controllers/studentDashboardController.php?page=dashboard" class="nav-link active" onclick="return showPanel('dashboard', this)">
                    <small>🏡</small> <span>Dashboard</span>
                </a>

                <a href="/EduGuide-php/controllers/studentDashboardController.php?page=profile" class="nav-link" onclick="return showPanel('profile', this)">
                    <small>👤</small> <span>My Profile</span>
                </a>

                <a href="/EduGuide-php/controllers/studentDashboardController.php?page=browse" class="nav-link" onclick="return showPanel('browse', this)">
                    <small>🔍</small> <span>Browse Tutors</span>
                </a>

                <a href="/EduGuide-php/controllers/studentDashboardController.php?page=bookings" class="nav-link" onclick="return showPanel('bookings', this)">
                    <small>📅</small> <span>My Bookings</span>
                </a>

                <a href="/EduGuide-php/controllers/studentDashboardController.php?page=reviews" class="nav-link" onclick="return showPanel('reviews', this)">
                    <small>⭐</small> <span>My Reviews</span>
                </a>

                <a href="/EduGuide-php/controllers/studentDashboardController.php?page=complaint" class="nav-link" onclick="return showPanel('complaint', this)">
                    <small>📢</small> <span>Raise Complaint</span>
                </a>

            </nav>

            <!-- Logout -->
           <div class="pb-3 px-2 mb-3">
                <a href="#" class="logout nav-link fw-semibold btn btn-sm shadow w-100 rounded"
                style="white-space:nowrap; overflow:hidden; display:flex; align-items:center; justify-content:center; gap:6px;"><small style="font-size: larger;">👉</small><span class="logout-span">Logout</span>
                </a>
            </div>
        </div>

        <!--Main Content-->
        <div class="flex-grow-1 main-content p-4 rounded-4 shadow-sm">
            <div class="panel active" id="panel-dashboard">

            
            <div class="mb-4 greet-bar rounded-4 p-4 text-white">
                <span><?php echo $today;?></span>
                <h4 class="fw-bold mb-1">
                    welcome Back <?php echo htmlspecialchars($student['name'] ?? 'Student') ?>!</h4>
                <p class="fst-italic mb-2">"Your future is created by what you do today."</p>
                <span class="badge bg-white bg-opacity-75 text-primary px-3 py-2">Student</span>
            </div>

            <!--Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-6">
                    <div class="card  shadow-sm text-center px-2 py-3 bg-warning bg-opacity-50">
                        <div class="fw-bold mb-1">Total Bookings</div>
                        <div class="text-primary fw-bold fs-4"><?php echo $totalBookings; ?></div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card shadow-sm text-center px-2 py-3 bg-success bg-opacity-50">
                        <div class="fw-bold mb-1">Confirmed</div>
                        <div class="text-success fw-bold fs-4"><?php echo $confirmedBookings; ?></div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card shadow-sm text-center px-2 py-3 bg-danger bg-opacity-50">
                        <div class="fw-bold mb-1">Pending</div>
                        <div class="text-danger fw-bold fs-4"><?php echo $pendingBookings; ?></div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card shadow-sm text-center px-2 py-3 bg-primary bg-opacity-50">
                        <div class="fw-bold mb-1">Completed</div>
                        <div class="text-black fw-bold fs-4"><?php echo $completedBookings; ?></div>
                    </div>
                </div>
            </div>
            <div class="row g-3">
                
            </div>
            <div class="panel" id="panel-profile">
                <h5 class="fw-bold text-primary mb-4">My Profile</h5>
                <!-- Profile Details -->
                <div id="profile-view">
                    <div class="card shadow-sm p-4 mb-3">
                        <div class="row"><div class="col-md-6">
                            <p class="mb-2">
                                span.text-muted
                            </p>
                        </div></div>
                    </div>
                </div>
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
                sidebar.style.width = '70px';
                sidebar.classList.add('collapsed');      
                navSpans.forEach(s => s.style.display = 'none');
                brandTitle.style.display = 'none';
                subTitle.style.display   = 'none';
                logoutSpan.style.display = 'none';

            } else {
                sidebar.style.width = '250px';
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