<?php
if (!isset($nameError)) {
    header("Location: /EduGuide-php/controllers/TutorController.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutor Registration – EduGuide</title>
    <link rel="stylesheet" href="/EduGuide-php/assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/EduGuide-php/assets/css/formStyle.css">
    <style>
        .subject-box {
            padding: 5px;
            max-height: 100px;
            overflow-y: auto;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 5px 15px;
        }
    </style>
</head>
<body>
<section class="form-bg min-vh-100 d-flex align-items-center py-5">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg">
                <div class="card-body p-4">

                    <h2 class="text-center mb-1">Tutor Registration</h2>
                    <p class="text-center text-danger fw-bold mb-4">Join EduGuide as a verified academic mentor</p>

                    <!-- Success message -->
                    <?php if (!empty($formSuccess)): ?>
                        <div class="alert alert-success text-center">
                            <?php echo $formSuccess; ?>
                            <br>
                            <a href="/EduGuide-php/controllers/AuthController.php">Click here to Login</a>
                        </div>
                    <?php endif; ?>

                    <form id="tutorForm" method="POST" action="/EduGuide-php/controllers/TutorController.php" onsubmit="return validateTutor(event)">

                        <!-- NAME -->
                        <div class="mb-3">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control"
                                placeholder="Enter your full name"
                                value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                            <small id="nameError" class="error"><?php echo $nameError; ?></small>
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

                        <!-- Email -->
                        <div class="mb-3">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control"
                                placeholder="Enter your email"
                                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            <small id="emailError" class="error"><?php echo $emailError; ?></small>
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="password" id="password" name="password" class="form-control"
                                    placeholder="Min 8 chars, 1 upper, 1 lower, 1 number, 1 special">
                                <button type="button" class="btn btn-light border"
                                    onclick="togglePassword('password', this)">
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
                                <button type="button" class="btn btn-light border"
                                    onclick="togglePassword('cpassword', this)">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <small id="cpasswordError" class="error"><?php echo $cpasswordError; ?></small>
                        </div>

                        <!-- Qualification -->
                        <div class="mb-3">
                            <label class="form-label">Qualification <span class="text-danger">*</span></label>
                            <input type="text" name="qualification" class="form-control"
                                placeholder="e.g. B.Sc, B.Ed, M.A"
                                value="<?php echo isset($_POST['qualification']) ? htmlspecialchars($_POST['qualification']) : ''; ?>">
                            <small id="qualificationError" class="error"><?php echo $qualificationError; ?></small>
                        </div>

                        <!-- Experience -->
                        <div class="mb-3">
                            <label class="form-label">Teaching Experience (years) <span class="text-danger">*</span></label>
                            <input type="number" name="experience" class="form-control"
                                placeholder="e.g. 3" min="0" max="50"
                                value="<?php echo isset($_POST['experience']) ? htmlspecialchars($_POST['experience']) : ''; ?>">
                            <small id="experienceError" class="error"><?php echo $experienceError; ?></small>
                        </div>

                        <!-- Subject -->
                        <div class="mb-3">
                            <label class="form-label">
                                Subjects <span class="text-danger">*</span>
                            </label>

                            <div class="subject-box border rounded p-2">

                                <?php while ($subject = $subjects_result->fetch_assoc()): ?>
                                    <div class="form-check">
                                        <input 
                                            class="form-check-input subject-checkbox" 
                                            type="checkbox" 
                                            name="subject_ids[]" 
                                            value="<?php echo $subject['id']; ?>"
                                        >
                                        <label class="form-check-label">
                                            <?php echo htmlspecialchars($subject['name']); ?>
                                        </label>
                                    </div>
                                <?php endwhile; ?>

                            </div>

                            <small class="text-danger"><?php echo $subjectError; ?></small>
                        </div>

                        <!-- Board -->
                        <div class="mb-3">
                            <label class="form-label">Education Board <span class="text-danger">*</span></label>
                            <select name="board_id" class="form-select">
                                <option value="">-- Select Board --</option>
                                <?php while ($board = $boards_result->fetch_assoc()): ?>
                                    <option value="<?php echo $board['id']; ?>"
                                        <?php echo (isset($_POST['board_id']) && $_POST['board_id'] == $board['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($board['name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <small id="boardError" class="error"><?php echo $boardError; ?></small>
                        </div>

                        <!-- Phone -->
                        <div class="mb-3">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control"
                                placeholder="10 digit mobile number"
                                value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                            <small id="phoneError" class="error"><?php echo $phoneError; ?></small>
                        </div>

                        <!-- Address -->
                        <div class="mb-3">
                            <label class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control" rows="3"
                                placeholder="Enter your home address"><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                            <small id="addressError" class="error"><?php echo $addressError; ?></small>
                        </div>

                        <!-- Availability -->
                        <div class="mb-4">
                            <label class="form-label">Available for Home Tuition? <span class="text-danger">*</span></label>
                            <select name="availability" class="form-select">
                                <option value="">-- Select Option --</option>
                                <option value="Yes" <?php echo (isset($_POST['availability']) && $_POST['availability'] == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                                <option value="No"  <?php echo (isset($_POST['availability']) && $_POST['availability'] == 'No')  ? 'selected' : ''; ?>>No</option>
                            </select>
                            <small id="availabilityError" class="error"><?php echo $availabilityError; ?></small>
                        </div>

                        <!-- Submit -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Submit Tutor Profile
                            </button>
                        </div>

                    </form>

                    <hr>
                    <div class="text-center small">
                        Already have an account?
                        <a href="/EduGuide-php/controllers/AuthController.php">Login here</a>
                    </div>
                    <div class="text-center small mt-2">
                        Are you a student?
                        <a href="/EduGuide-php/controllers/StudentController.php">Register as Student</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</section>

<script>
document.querySelectorAll('.subject-checkbox').forEach(cb => {
    cb.addEventListener('change', function () {
        const checked = document.querySelectorAll('.subject-checkbox:checked');
        if (checked.length > 2) {
            alert("You can select maximum 2 subjects");
            this.checked = false;
        }
    });
});
</script>
<script src="/EduGuide-php/assets/bootstrap/bootstrap.bundle.min.js"></script>
<script src="/EduGuide-php/assets/js/validation.js"></script>
</body>
</html>