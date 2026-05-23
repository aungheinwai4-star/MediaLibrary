<?php
$pageTitle = 'Login';
$hideSearch = true;
$authPage = 'login';
$section = null;
require BASE_PATH . '/view/layout/header.php';
?>

<div class="section page auth-page">
    <div class="wrapper">
        <div class="auth-card">
            <h1 class="auth-title">Welcome back</h1>
            <p class="auth-subtitle">Sign in to manage your personal media library.</p>

            <?php if (!empty($error)): ?>
                <p class="message auth-message"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form class="auth-form" method="POST" action="<?= BASE_URL ?>/Public/index.php?page=login-submit">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="you@example.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Your password" required>
                </div>

                <button type="submit" class="btn btn-auth-submit">Sign in</button>
            </form>

            <p class="auth-switch">
                New here?
                <a href="<?= BASE_URL ?>/Public/index.php?page=register">Create an account</a>
            </p>
        </div>
    </div>
</div>

<?php require BASE_PATH . '/view/layout/footer.php'; ?>
