<?php
$pageTitle = 'Register';
$hideSearch = true;
$authPage = 'register';
$section = null;
require BASE_PATH . '/view/layout/header.php';
?>

<div class="section page auth-page">
    <div class="wrapper">
        <div class="auth-card">
            <h1 class="auth-title">Create your account</h1>
            <p class="auth-subtitle">Join the library to save and organize your collection.</p>

            <?php if (!empty($error)): ?>
                <p class="message auth-message"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form class="auth-form" method="POST" action="<?= BASE_URL ?>/Public/index.php?page=register-submit">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" placeholder="Your name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="you@example.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Choose a strong password" required>
                </div>

                <button type="submit" class="btn btn-auth-submit">Create account</button>
            </form>

            <p class="auth-switch">
                Already have an account?
                <a href="<?= BASE_URL ?>/Public/index.php?page=login">Sign in</a>
            </p>
        </div>
    </div>
</div>

<?php require BASE_PATH . '/view/layout/footer.php'; ?>
