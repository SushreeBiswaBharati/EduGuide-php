<?php 
session_start(); 
?>

<nav class="navbar navbar-expand-lg navbar-light shadow-lg py-1 py-lg-2">
    <div class="container">

        <!-- LOGO -->
        <img src="assets/images/logo.png" alt="EduGuide Logo" height="70" width="70" class="d-inline-block">
        <a class="navbar-brand fw-bold fs-4 text-primary" href="index.php">
            <span class="d-block d-lg-inline">Edu<span class="text-dark">Guide - </span></span>
            Guidance Beyond Textbook
        </a>

        <!-- Mobile toggle button -->
        <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- NAV LINKS -->
        <div class="collapse navbar-collapse" id="mainNavbar">

            <!-- Center links -->
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php#about">About Us</a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link" href="index.php#how-it-works">How It Works</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php#contact">Reach Us</a>
                </li>
            </ul>

            <!-- Right side buttons -->
            <ul class="navbar-nav mb-2 mb-lg-0 align-items-lg-center gap-2">
                <li class="nav-item">
                    <a class="btn btn-outline-primary px-4" href="controllers/AuthController.php">
                        Login
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <button class="btn btn-primary px-4 dropdown-toggle"
                        data-bs-toggle="dropdown">
                        Register
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="controllers/StudentController.php">
                                🎓 Student Registration
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="controllers/TutorController.php">
                                📚 Tutor Registration
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

        </div>
    </div>
</nav>