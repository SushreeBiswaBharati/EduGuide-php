<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration – EduGuide</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/formStyle.css">

</head>
<body>
<section class="form-bg">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg">
                <div class="card-body p-4">

                    <h2 class="text-center mb-1">Student Registration</h2>
                    <p class="text-center text-danger fw-bold mb-4">Create your academic profile</p>

                    <!-- Form success message -->
                    <?php if (!empty($formSuccess)): ?>
                        <div class="alert alert-success text-center">
                            <?php echo $formSuccess; ?>
                            <a href="<?php echo BASE_URL; ?>views/auth/login.php">Click here to Login</a>
                        </div>
                    <?php endif; ?>

                    <form id="regForm" method="POST" action="" onsubmit="return validateRegister(event)">

                        <!-- Name -->
                        <div class="mb-3">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control"
                                placeholder="Enter your full name"
                                value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                            <small id="nameError" class="error">  <?php echo $nameError;   ?></small>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control"
                                placeholder="Enter your email"
                                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            <small id="emailError"   class="error">  <?php echo $emailError;   ?></small>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>                           
                            <div class="input-group">
                                <input type="password" id="password" name="password" class="form-control"
                                    placeholder="Min 6 characters">                    
                                <button type="button" class="btn btn-light border" onclick="togglePassword('password', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <small id="passwordError" class="error"><?php echo $passwordError; ?></small>
                        </div>

                        <!-- Confirm password -->
                        <div class="mb-3">
                            <label class="form-label">Confirm Password <span class="text-danger">*</span></label>                           
                            <div class="input-group">
                                <input type="password" id="cpassword" name="cpassword" class="form-control"
                                    placeholder="Re-enter your password">                                
                                <button type="button" class="btn btn-light border" onclick="togglePassword('cpassword', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <small id="cpasswordError" class="error"><?php echo $cpasswordError; ?></small>
                        </div>

                        <!-- Gender -->
                        <div class="mb-3">
                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                            <?php $selectedGender = isset($_POST['gender']) ? $_POST['gender'] : ''; ?>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" value="Male"
                                    <?php echo $selectedGender === 'Male' ? 'checked' : ''; ?>>
                                <label class="form-check-label">Male</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" value="Female"
                                    <?php echo $selectedGender === 'Female' ? 'checked' : ''; ?>>
                                <label class="form-check-label">Female</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" value="Other"
                                    <?php echo $selectedGender === 'Other' ? 'checked' : ''; ?>>
                                <label class="form-check-label">Other</label>
                            </div>
                            <br><small id="genderError" class="error"><?php echo $genderError; ?></small>
                        </div>

                        <!-- Class -->
                        <div class="mb-3">
                            <label class="form-label">Class <span class="text-danger">*</span></label>
                            <select name="class_name" class="form-select">
                                <option value="">-- Select Class --</option>
                                <?php while ($class = $classes_result->fetch_assoc()): ?>
                                    <option value="<?php echo $class['id']; ?>"
                                        <?php echo (isset($_POST['class_name']) && $_POST['class_name'] == $class['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($class['name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <small id="classError" class="error"><?php echo $classError; ?></small>
                        </div>

                        <!-- Board -->
                        <div class="mb-3">
                            <label class="form-label">Education Board <span class="text-danger">*</span></label>
                            <select name="board" class="form-select">
                                <option value="">-- Select Board --</option>
                                <?php while ($board = $boards_result->fetch_assoc()): ?>
                                    <option value="<?php echo $board['id']; ?>"
                                        <?php echo (isset($_POST['board']) && $_POST['board'] == $board['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($board['name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <small id="boardError" class="error"><?php echo $boardError; ?></small>
                        </div>

                        <!-- Target exam -->
                        <div class="mb-3">
                            <label class="form-label">Target Exam</label>
                            <select name="target_exam" class="form-select">
                                <option value="">-- Select Exam (Optional) --</option>
                                <?php while ($exam = $exams_result->fetch_assoc()): ?>
                                    <option value="<?php echo $exam['id']; ?>"
                                        <?php echo (isset($_POST['target_exam']) && $_POST['target_exam'] == $exam['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($exam['name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <!-- School -->
                        <div class="mb-3">
                            <label class="form-label">School Name</label>
                            <input type="text" name="school_name" class="form-control"
                                placeholder="Enter your school name"
                                value="<?php echo isset($_POST['school_name']) ? htmlspecialchars($_POST['school_name']) : ''; ?>">
                        </div>

                        <!-- Parent name -->
                        <div class="mb-3">
                            <label class="form-label">Parent Name</label>
                            <input type="text" name="parent_name" class="form-control"
                                placeholder="Enter parent's name"
                                value="<?php echo isset($_POST['parent_name']) ? htmlspecialchars($_POST['parent_name']) : ''; ?>">
                        </div>

                        <!-- Parent phone -->
                        <div class="mb-3">
                            <label class="form-label">Parent Phone</label>
                            <input type="text" name="parent_phone" class="form-control"
                                placeholder="10 digit number"
                                value="<?php echo isset($_POST['parent_phone']) ? htmlspecialchars($_POST['parent_phone']) : ''; ?>">
                            <small id="parentPhoneError" class="error"><?php echo $parentPhoneError; ?></small>
                        </div>

                        <!-- Address -->
                        <div class="mb-4">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="3"
                                placeholder="Enter your home address"><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                        </div>

                        <!-- Submit -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Create Student Account
                            </button>
                        </div>

                    </form>

                    <hr>
                    <div class="text-center small">
                        Already have an account?
                        <a href="/EduGuide-php/views/auth/login.php">Login here</a>
                    </div>
                    <div class="text-center small mt-2">
                        Are you a tutor?
                        <a href="/EduGuide-php/controllers/TutorController.php">Register as Tutor</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</section>
<script src="<?php echo BASE_URL; ?>assets/bootstrap/bootstrap.bundle.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/validation.js"></script>

</body>
</html>