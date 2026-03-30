<?php
if (!isset($emailError)) {
    header("Location: ../../controllers/AuthController.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EduGuide</title>
    <link rel="stylesheet" href="/EduGuide-php/assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/EduGuide-php/assets/css/formStyle.css ? v=1.1">
</head>
<body>
    <section class="form-bg min-vh-100 d-flex align-items-center">
        <div class="container py-3">
            <div class="row align-items-center">

                <!-- Illustration -->
                <div class="col-md-6 text-center mb-4 mb-md-0">
                    <img src="/EduGuide-php/assets/images/login-img.png"
                        alt="Login Illustration"
                        class="login-illustration img-fluid">
                </div>

                <!-- Login Form -->
                <div class="col-md-6">
                    <div class="card shadow-lg">
                        <div class="card-body p-4">

                            <h2 class="text-center mb-3">Welcome Back</h2>
                            <p class="text-center text-danger fw-bold mb-4">
                                Log in to continue your learning journey
                            </p>

                            <!-- Login error message -->
                            <?php if (!empty($loginError)): ?>
                                <div class="alert alert-danger text-center">
                                    <?php echo $loginError; ?>
                                </div>
                            <?php endif; ?>

                            <form id="loginForm" method="POST" action="" onsubmit="return validateLogin(event)">

                                <!-- Email -->
                                <div class="mb-3">
                                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email"
                                        name="email"
                                        placeholder="Enter your registered email"
                                        class="form-control"
                                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                    <small id="emailError" class="error"><?php echo $emailError; ?></small>
                                </div>

                                <!-- Password -->
                                <div class="mb-3">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password"
                                        name="password"
                                        placeholder="Enter your password"
                                        class="form-control">
                                    <small id="passwordError" class="error"><?php echo $passwordError; ?></small>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">
                                        Login to EduGuide
                                    </button>
                                    <div class="mb-3">
                                        <a href="/EduGuide-php/controllers/forgetPasswordController.php" 
                                        class="text-decoration-none small text-primary">
                                            Forgot Password?
                                        </a>
                                    </div>
                                </div>
                            </form>

                            <!-- Registration links -->
                            <hr class="my-3">
                            <div class="d-flex justify-content-center gap-3 flex-wrap">
                                <a href="/EduGuide-php/controllers/StudentController.php"
                                   class="text-decoration-none text-primary fw-semibold small">
                                   🎓 Register as Student
                                </a>
                                <span class="text-muted small">|</span>
                                <a href="/EduGuide-php/controllers/TutorController.php"
                                   class="text-decoration-none text-success fw-semibold small">
                                   📚 Register as Tutor
                                </a>
                            </div>

                            <!-- Back to home -->
                            <div class="text-center mt-2">
                                <a href="/EduGuide-php/index.php"
                                   class="text-decoration-none text-secondary small">
                                   ← Back to Home
                                </a>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <script src="/EduGuide-php/assets/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="/EduGuide-php/assets/js/validation.js"></script>
</body>
</html>