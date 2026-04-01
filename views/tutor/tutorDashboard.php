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
    <div class="flex-grow-1 main-content p-4 rounded-4 shadow-sm" style="overflow-y:auto;">
        <?php if ($page === 'dashboard'): ?>
            <!-- Dashboard -->
            <div class="mb-4 greet-bar rounded-4 p-4 text-white">
                <span><?php echo $today;?></span>
                <h4 class="fw-bold mb-1">
                    Welcome Back <?php echo htmlspecialchars($tutor['name'] ?? 'Tutor') ?>! 👨‍🏫
                </h4>
                <p class="fst-italic mb-3">"Teaching is the greatest act of optimism."</p>
                <span class="badge bg-white bg-opacity-75 text-primary px-3 py-2">✅ Verified Tutor</span>
            </div>
            <!--  -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-6">
                    <div class="card text-center px-2 py-3 bg-warning bg-opacity-50">
                        <div class="fw-semibold">Total Requests</div>
                        <div class="fw-bold fs-4"><?php echo $totalRequests; ?></div>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="card text-center px-2 py-3 bg-success bg-opacity-50">
                        <div class="fw-semibold">Confirmed Requests</div>
                        <div class="fw-bold fs-4"><?php echo $confirmedCount; ?></div>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="card text-center px-2 py-3 bg-danger bg-opacity-50">
                        <div class="fw-semibold">Pending Requests</div>
                        <div class="fw-bold fs-4"><?php echo $pendingCount; ?></div>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="card text-center px-2 py-3 bg-primary bg-opacity-50">
                        <div class="fw-semibold">Completed Session</div>
                        <div class="fw-bold fs-4"><?php echo $completedCount; ?></div>
                    </div>
                </div>
            </div>

        <?php if($page === 'profile') ?>    
            <!-- Profile -->
            <h5 class="fw-bold text-primary mb-4">My Profile</h5>
            <?php if ($profileSuccess): ?>
                <div class="alert alert-success"><?php echo $profileSuccess; ?></div>
            <?php endif; ?>

            <?php if ($profileError): ?>
                <div class="alert alert-danger"><?php echo $profileError; ?></div>
            <?php endif; ?>
    </div>
</div>

<script>
    // ── PANEL SWITCHING ───────────────────────────────────
    function showPanel(name, el) {
        document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.nav-link').forEach(a => a.classList.remove('active'));
        document.getElementById('panel-' + name).classList.add('active');
        if (el) el.classList.add('active');
        return false;
    }

    // ── EDIT PROFILE TOGGLE ───────────────────────────────
    function toggleEdit() {
        const view = document.getElementById('profile-view');
        const form = document.getElementById('edit-form');
        const isHidden = view.style.display === 'none';
        view.style.display = isHidden ? 'block' : 'none';
        form.style.display = isHidden ? 'none'  : 'block';
    }

    // ── SIDEBAR COLLAPSE ──────────────────────────────────
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

    // ── AUTO RESTORE PANEL ────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {

        // After accept/reject booking redirect
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('action') === 'requests') {
            const reqLink = document.querySelector("[onclick*=\"'requests'\"]");
            showPanel('requests', reqLink);
        }

        // After edit profile submit
        <?php if ($profileSuccess || $profileError): ?>
            const profileLink = document.querySelector("[onclick*=\"'profile'\"]");
            showPanel('profile', profileLink);
            <?php if ($profileError): ?>
                document.getElementById('profile-view').style.display = 'none';
                document.getElementById('edit-form').style.display    = 'block';
            <?php endif; ?>
        <?php endif; ?>

        // After complaint submit
        <?php if ($complaintSuccess || $complaintError): ?>
            const compLink = document.querySelector("[onclick*=\"'complaint'\"]");
            showPanel('complaint', compLink);
        <?php endif; ?>
    });
</script>
</body>
</html>