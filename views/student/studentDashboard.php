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
                    <small>🏡</small> <span>Dashboard</span>
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
                                                <button class="btn btn-primary btn-sm" onclick="openBookingModal(<?php echo $t['id']; ?>)">Book Now</button>
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

                    <h5>My Bookings</h5>

                <?php elseif ($page === 'reviews'): ?>

                    <h5>My Reviews</h5>

                <?php elseif ($page === 'complaint'): ?>

                    <h5>Raise Complaint</h5>

                <?php endif; ?>  
            </div>
        </div>
    </div>

    <!-- Booking modal -->
    <div id="bookingModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5);">
        
        <div style="background:#fff; width:400px; margin:100px auto; padding:20px; border-radius:10px;">
            
            <h5 class="text-center fw-bold mb-3">Book Tutor</h5>

            <form method="POST">

                <input type="hidden" name="tutor_id" id="tutor_id">

                <label>Requirement</label>
                <textarea name="requirement" class="form-control mb-2" required></textarea>

                <label>Duration (Months)</label>
                <input type="number" name="duration" class="form-control mb-2" required>

                <button type="submit" name="book_tutor" class="btn btn-success w-100">
                    Send Request
                </button>

                <button type="button" class="btn btn-secondary w-100 mt-2" onclick="closeBookingModal()">
                    Cancel
                </button>

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

     
        function openBookingModal(tutorId){
            document.getElementById('bookingModal').style.display = 'block';
            document.getElementById('tutor_id').value = tutorId;
        }

        function closeBookingModal(){
            document.getElementById('bookingModal').style.display = 'none';
        }
    </script>
</body>
</html>