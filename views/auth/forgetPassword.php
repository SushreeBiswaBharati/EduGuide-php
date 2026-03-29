<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password – EduGuide</title>
    <link rel="stylesheet" href="/EduGuide-php/assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/EduGuide-php/assets/css/formStyle.css ? v=1.1">
</head>
<body>
<div class="form-bg d-flex justify-content-center align-items-center min-vh-100">
    <div class="col-md-5 col-lg-4">
        <div class="text-center mb-4">
            <h3 class="fw-bold ">EduGuide</h3>
            <p class="text-muted">
                <?= $step === 1 ? 'Enter your registered email to reset password' : 'Set your new password' ?>
            </p>
        </div>

        <div class="card shadow-sm border-0 rounded-4 p-4">
            <div class="d-flex justify-content-center gap-2 mb-4">
                <span class="badge rounded-pill px-3 py-2 <?= $step >= 1 ? 'bg-primary' : 'bg-secondary' ?>">
                    1 Verify Email
                </span>
                <span class="badge rounded-pill px-3 py-2 <?= $step >= 2 ? 'bg-primary' : 'bg-secondary' ?>">
                    2 New Password
                </span>
            </div>

            <?php if ($step === 1): ?>
                <form method="POST" action="">
                <input type="hidden" name="step" value="1">

                <?php if ($emailError): ?>
                    <div class="alert alert-danger py-2"><?= htmlspecialchars($emailError) ?></div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Registered Email</label>
                    <input
                        type="email"
                        name="email"
                        class="form-control <?= $emailError ? 'is-invalid' : '' ?>"
                        placeholder="Enter your email"
                        value="<?= htmlspecialchars($email) ?>"
                        required
                        autofocus
                    >
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-semibold">
                    Verify Email
                </button>
            </form>

            <?php elseif ($step === 2): ?>
            <form method="POST" action="" id="resetForm">
                <input type="hidden" name="step" value="2">

                <?php if ($resetError): ?>
                    <div class="alert alert-danger py-2"><?= htmlspecialchars($resetError) ?></div>
                <?php endif; ?>

                <p class="text-muted small mb-3">
                    Resetting password for: <strong><?= htmlspecialchars($_SESSION['reset_email'] ?? '') ?></strong>
                </p>

                <div class="mb-3">
                    <label class="form-label fw-semibold">New Password</label>
                    <div class="input-group">
                        <input
                            type="password"
                            name="new_password"
                            id="newPass"
                            class="form-control <?= $resetError ? 'is-invalid' : '' ?>"
                            placeholder="New password"
                            required
                        >
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePass('newPass', this)">👁</button>
                    </div>
                    <small class="text-muted">8-15 chars, uppercase, lowercase, number, special (@#$%^&)</small>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">Confirm Password</label>
                    <div class="input-group">
                        <input
                            type="password"
                            name="confirm_password"
                            id="confPass"
                            class="form-control"
                            placeholder="Confirm new password"
                            required
                        >
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePass('confPass', this)">👁</button>
                    </div>
                    <small id="matchMsg" class="text-muted"></small>
                </div>

                <button type="submit" class="btn btn-success w-100 fw-semibold">
                    Save New Password
                </button>
            </form>

            <?php endif; ?>
            <div class="text-center mt-3">
                <a href="/EduGuide-php/controllers/AuthController.php" class="text-decoration-none text-muted small">
                    ← Back to Login
                </a>
            </div>

        </div>
    </div>
</div>

<script>
    function togglePass(fieldId, btn) {
        const field = document.getElementById(fieldId);
        if (field.type === 'password') {
            field.type = 'text';
            btn.textContent = '🙈';
        } else {
            field.type = 'password';
            btn.textContent = '🐵';
        }
    }

    // Live confirm password match check
    const confPass = document.getElementById('confPass');
    const newPass  = document.getElementById('newPass');
    const matchMsg = document.getElementById('matchMsg');

    if (confPass && newPass && matchMsg) {
        confPass.addEventListener('input', function () {
            if (confPass.value === '') {
                matchMsg.textContent = '';
                confPass.classList.remove('is-valid','is-invalid');
            } else if (confPass.value === newPass.value) {
                matchMsg.textContent = '✅ Passwords match';
                matchMsg.className   = 'text-success small';
                confPass.classList.remove('is-invalid');
                confPass.classList.add('is-valid');
            } else {
                matchMsg.textContent = '❌ Passwords do not match';
                matchMsg.className   = 'text-danger small';
                confPass.classList.remove('is-valid');
                confPass.classList.add('is-invalid');
            }
        });
    }
</script>
</body>
</html>