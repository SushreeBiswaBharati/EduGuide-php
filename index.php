<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduGuide</title>
    <link rel="stylesheet" href="/EduGuide-php/assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/EduGuide-php/assets/css/style.css?v=1.1">
    
</head>
<body>

    <?php include_once 'views/partials/navbar.php'; ?>

    <section id="hero">
        <div class="container mb-2">
            <div class="row align-items-center g-4">
    
                <!-- Left: Text -->
                <div class="col-lg-6">
    
                    <span class="badge-trust">🎓 Trusted by Students across Odisha</span>
    
                    <h1>Find the Right Tutor <br> for Home Tuition</h1>
                    <h5 class="text-muted fw-semibold mt-4">Personalized Learning Starts with the Right Guidance</h5>
                    <p class="mt-4">
                        Connect with verified local tutors for personalized home tuition
                        at your doorstep. Learn at your own pace, in your own space.
                    </p>
    
                    <!-- Trust Indicators -->
                    <div class="d-flex flex-wrap gap-3 my-4">
                        <span class="trust-item">✅ Free Registration</span>
                        <span class="trust-item">✅ Verified Tutors Only</span>
                        <span class="trust-item">✅ All Boards Covered</span>
                    </div>
    
                    <!-- Buttons -->
                    <div class="d-flex flex-wrap gap-3">
                        <a href="/EduGuide-php/controllers/StudentController.php" class="btn btn-primary btn-lg px-4">
                            Find a Tutor
                        </a>
                        <a href="/EduGuide-php/controllers/TutorController.php" class="btn btn-outline-primary btn-lg px-4">
                            Register as Tutor
                        </a>
                    </div>
    
                </div>
    
                <!-- Right: Image -->
                <div class="col-lg-6 text-center">
                    <img src="assets/images/hero1.png" class="hero-img shadow" alt="Student with Tutor">
                </div>
    
            </div>
        </div>
    </section>
    <!-- SECTION — HOW IT WORKS -->
    <section id="how-it-works">
        <div class="container">

            <div class="text-center mb-3">
                <h2 class="section-title">How It Works</h2>
                <p class="section-sub">Start learning in just 3 simple steps</p>
            </div>

            <div class="row g-4">

                <div class="col-md-4">
                    <div class="card about-card shadow-sm h-100 p-4 text-center">
                        <div class="about-icon">🔍</div>
                        <h5 class="fw-bold mt-3">Search Tutors</h5>
                        <p class="text-muted small">
                            Find tutors based on subject, location, and experience.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card about-card shadow-sm h-100 p-4 text-center">
                        <div class="about-icon">📅</div>
                        <h5 class="fw-bold mt-3">Book a Session</h5>
                        <p class="text-muted small">
                            Choose a suitable time and send a booking request.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card about-card shadow-sm h-100 p-4 text-center">
                        <div class="about-icon">🎓</div>
                        <h5 class="fw-bold mt-3">Start Learning</h5>
                        <p class="text-muted small">
                            Learn with personalized one-on-one guidance.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>
    
    <!-- SECTION 2 — ABOUT US -->
    <section id="about">
        <div class="container">
    
            <div class="text-center mb-"3>
                <h2 class="section-title">About EduGuide</h2>
                <p class="section-sub">We make home tuition simple, safe and effective for every student</p>
            </div>
    
            <div class="row g-4">
    
                <div class="col-md-4">
                    <div class="card about-card shadow-sm h-100 p-4 text-center">
                        <div class="about-icon">✅</div>
                        <h5 class="fw-bold">Verified Tutors</h5>
                        <p class="text-muted small">
                            Every tutor is manually reviewed and approved by our admin team
                            before appearing on the platform. No fake profiles.
                        </p>
                    </div>
                </div>
    
                <div class="col-md-4">
                    <div class="card about-card shadow-sm h-100 p-4 text-center">
                        <div class="about-icon">📚</div>
                        <h5 class="fw-bold">All Boards Covered</h5>
                        <p class="text-muted small">
                            Whether you are studying under CBSE, ICSE, CHSE or
                            State Board — we have the right tutor for your syllabus.
                        </p>
                    </div>
                </div>
    
                <div class="col-md-4">
                    <div class="card about-card shadow-sm h-100 p-4 text-center">
                        <div class="about-icon">🏠</div>
                        <h5 class="fw-bold">Learn at Home</h5>
                        <p class="text-muted small">
                            No travel. No time waste. Your tutor comes to you.
                            Get one-on-one personal attention in a comfortable environment.
                        </p>
                    </div>
                </div>
    
                <div class="col-md-4">
                    <div class="card about-card shadow-sm h-100 p-4 text-center">
                        <div class="about-icon">🎯</div>
                        <h5 class="fw-bold">Target Exam Focus</h5>
                        <p class="text-muted small">
                            Preparing for Navodaya, Adarsh, Scholarship or School Exams?
                            Find tutors who specialize in your specific goal.
                        </p>
                    </div>
                </div>
    
                <div class="col-md-4">
                    <div class="card about-card shadow-sm h-100 p-4 text-center">
                        <div class="about-icon">⭐</div>
                        <h5 class="fw-bold">Honest Reviews</h5>
                        <p class="text-muted small">
                            Read real ratings and reviews from students before choosing
                            your tutor. Make informed and confident decisions.
                        </p>
                    </div>
                </div>
    
                <div class="col-md-4">
                    <div class="card about-card shadow-sm h-100 p-4 text-center">
                        <div class="about-icon">💸</div>
                        <h5 class="fw-bold">Free to Register</h5>
                        <p class="text-muted small">
                            Students and tutors both register completely free.
                            No hidden charges for creating your profile on EduGuide.
                        </p>
                    </div>
                </div>
    
            </div>
        </div>
    </section>
    
    <!-- SECTION 3 — REACH US -->
    <section id="contact">
        <div class="container">
    
            <div class="text-center mb-3">
                <h2 class="section-title">Reach Us</h2>
                <p class="section-sub">Have a question? We are here to help you</p>
            </div>
    
            <div class="row g-4 justify-content-center">
    
                <div class="col-md-4">
                    <div class="card about-card shadow-sm h-100 p-4 text-center">
                        <div class="contact-icon">📧</div>
                        <h6 class="fw-bold">Email Us</h6>
                        <p class="text-muted small mb-1">For any queries or support</p>
                        <a href="mailto:eduguide@gmail.com" class="text-primary fw-semibold">
                            eduguide@gmail.com
                        </a>
                    </div>
                </div>
    
                <div class="col-md-4">
                    <div class="card about-card shadow-sm h-100 p-4 text-center">
                        <div class="contact-icon">📞</div>
                        <h6 class="fw-bold">Call Us</h6>
                        <p class="text-muted small mb-1">Mon – Sat, 9am to 6pm</p>
                        <a href="tel:+919876543210" class="text-primary fw-semibold">
                            +91 98765 43210
                        </a>
                    </div>
                </div>
    
                <div class="col-md-4">
                    <div class="card about-card shadow-sm h-100 p-4 text-center">
                        <div class="contact-icon">📍</div>
                        <h6 class="fw-bold">Find Us</h6>
                        <p class="text-muted small mb-1">Our base location</p>
                        <span class="text-primary fw-semibold">Bhubaneswar, Odisha, India</span>
                    </div>
                </div>
    
            </div>
        </div>
    </section>

    <?php include_once 'views/partials/footer.php'; ?>
    <script src="/EduGuide-php/assets/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>