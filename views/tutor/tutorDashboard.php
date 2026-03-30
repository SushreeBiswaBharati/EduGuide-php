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
                <a href="#" class="nav-link active" onclick="return showPanel('dashboard', this)" ><small>🏡</small> <span>Dashboard</span></a>
                <a href="#" class="nav-link" onclick="return showPanel('profile', this)"><small>👤</small> <span>My Profile</span></a>
                <a href="#" class="nav-link" onclick="return showPanel('requests', this)"><small>📥</small> <span>Booking Requests</span></a>
                <a href="#" class="nav-link" onclick="return showPanel('schedule', this)"><small>📅</small> <span>My Shedule</span></a>
                <a href="#" class="nav-link" onclick="return showPanel('reviews', this)"><small>⭐</small> <span>My Reviews</span></a>
                <a href="#" class="nav-link" onclick="return showPanel('complaint', this)"><small>📢</small> <span>Raise Complaint</span></a>
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

            <div class="mb-4 greet-bar rounded-4 p-4 text-white">
                <span><?php echo $today ; ?></span>
                <h4 class="fw-bold mb-1">Welcome Back, <?= htmlspecialchars($tutor['name'] ?? 'Tutor')?> !👨‍🏫</h4>
                <p class="fst-italic mb-2">"Teaching is the greatest act of optimism."</p>
                <span class="badge bg-white bg-opacity-75 text-primary px-3 py-2">✅ Verified Tutor</span>
            </div>

            <!--Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-6">
                    <div class="card  shadow-sm text-center px-2 py-3 bg-warning bg-opacity-50">
                        <div class="fw-bold mb-1">Total Requests</div>
                        <div class="text-primary fw-bold fs-4"><?php echo $totalRequests; ?></div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card shadow-sm text-center px-2 py-3 bg-success bg-opacity-50">
                        <div class="fw-bold mb-1">Confirmed</div>
                        <div class="text-success fw-bold fs-4"><?php echo $confirmedCount; ?></div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card shadow-sm text-center px-2 py-3 bg-danger bg-opacity-50">
                        <div class="fw-bold mb-1">Pending</div>
                        <div class="text-danger fw-bold fs-4"><?php echo $pendingCount; ?></div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card shadow-sm text-center px-2 py-3 bg-primary bg-opacity-50">
                        <div class="fw-bold mb-1">Completed</div>
                        <div class="text-black fw-bold fs-4"><?php echo $completedCount; ?></div>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm p-4">
                <h6 class="fw-bold text-primary mb-3">Quick Info</h6>
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-2">
                            <span class="text-muted">Name:</span>
                            <strong><?php echo htmlspecialchars($tutor['name'] ?? '') ?></strong>
                        </p>
                        <p class="mb-2">
                            <span class="text-muted">Email :</span>
                            <?php echo htmlspecialchars($tutor['email'] ?? '') ?>
                        </p>
                        <p class="mb-2">
                            <span class="text-muted">Subject :</span> 
                            <?php echo htmlspecialchars($tutor['subject_name'] ?? 'N/A') ?>
                        </p>
                        <p class="mb-2">
                            <span class="text-muted">Experience :</span> 
                            <?php echo htmlspecialchars($tutor['experience'] ?? '0') ?> years
                        </p>
                        <p class="mb-2">
                            <span class="text-muted">Rating :</span>
                            ⭐ <?php echo $avgRating ?> / 5 (<?php echo $totalReviews ?> reviews)
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- my profile -->
         <div class="panel" id="panel-profile">
            <h5 class="fw-bold text-primary mb-3">👤 My Profile</h5>
            <div id="profile-view">
                <div class="card shadow-sm p-4 mb-3">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <span class="text-muted fw-semibold">Full Name:</span>
                                <?php echo htmlspecialchars($tutor['name'] ?? '') ?>
                            </p>
                            <p class="mb-2">
                                <span class="text-muted fw-semibold">Email:</span>
                                <?php echo htmlspecialchars($tutor['email'] ?? '') ?>
                            </p>
                            <p class="mb-2">
                                <span class="text-muted fw-semibold">Gender:</span>
                                <?php echo htmlspecialchars($tutor['Gender'] ?? '') ?>
                            </p>
                            <p class="mb-2">
                                <span class="text-muted fw-semibold">Qualification:</span>
                                <?php echo htmlspecialchars($tutor['qualification'] ?? '') ?>
                            </p>
                            <p class="mb-2">
                                <span class="text-muted fw-semibold">Experience:</span>
                                <?php echo htmlspecialchars($tutor['experience'] ?? '') ?> years
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <span class="text-muted fw-semibold">Subject:</span>
                                <?php echo htmlspecialchars($tutor['subject_name'] ?? '') ?>
                            </p>
                            <p class="mb-2">
                                <span class="text-muted fw-semibold">Board:</span>
                                <?php echo htmlspecialchars($tutor['board_name'] ?? '') ?>
                            </p>
                            <p class="mb-2">
                                <span class="text-muted fw-semibold">Phone:</span>
                                <?php echo htmlspecialchars($tutor['phone'] ?? '') ?>
                            </p>
                            <p class="mb-2">
                                <span class="text-muted fw-semibold">Address:</span>
                                <?php echo htmlspecialchars($tutor['address'] ?? '') ?>
                            </p>
                            <p class="mb-2">
                                <span class="text-muted fw-semibold">Availability:</span>
                                <?php $tutor['availability'] === 'Yes' ? 'bg-success' : 'bg-secondary' ?>
                                <?php echo $tutor['availability'] === 'Yes' ? 'Available' : 'not Available' ?>
                            </p>
                            <p class="mb-2">
                                <span class="text-muted fw-semibold">Registered On :</span>
                                <?php echo $registeredDate ?></p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-primary btn-sm px-4" onclick="toggleEdit()">✏️ Edit Profile</button>
                    </div>
                </div>
            </div>
            <!-- Edit section -->
            <div id="edit-form">
                <?php if($profileSuccess): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($profileSuccess) ?>
                    </div>
                <?php endif; ?>
                <?php if($profileError): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($profileError) ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card shadow-sm p-4">
                <h6 class="fw-bold mb-3">Edit Profile</h6>
                <form action="" method="POST">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="edit_name" class="form-control" value="<?php htmlspacialchars($tutor['name']) ?? '' ?> required">
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <script>
        function showPanel(name, el){
            document.querySelectorAll('.panel').forEach(p=> p.classList.remove('active'));
            document.querySelectorAll('.nav-link').forEach(a=> a.classList.remove('active'));
            document.getElementById('panel-' +name).classList.add('active');
            if(el) el.classList.add('active');
            return false;
        }

        function toggleEdit(){
            const view = document.getElementById('profile-view');
            const form = document.getElementById('edit-form');
            const isHidden = view.style.display === 'none';
            view.style.display = ishidden ? 'block' : 'none';
            form.style.display = isHidden ? 'none' : 'block';
        }

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

        document.addEventListener('DOMContentLoaded', function (){
            <?php if($profileSuccess || $profileError): ?>
                const profileLink = document.querySelector("[onclick*=\" 'profile'\"]");
                showPanel('pprofile', profileLink);
                <?php if($profileError): ?>
                    document.getelemtById('profile-view').style.display = 'none';
                    document.getElementById('edit-form').style.display = 'block';
                <?php endif; ?>
            <?php endif; ?>

            <?php if($complaintSuccess || $complaintError): ?>
                const compLink = document.querySelector("[onclick*=\"'complaiint'\"]");
                showPanel('complaint', compLink);
            <?php endif; ?>
        });
    </script>
</body>
</html>