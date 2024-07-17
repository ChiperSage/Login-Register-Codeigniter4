<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('/login/authenticateWithCaptcha') ?>" method="post">
        <?= csrf_field() ?>
        <div>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?= old('username') ?>">
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password">
        </div>
        <div>
            <label for="remember_me">Remember Me:</label>
            <input type="checkbox" name="remember_me" id="remember_me">
        </div>
        <div class="g-recaptcha" data-sitekey="YOUR_SITE_KEY"></div>
        <button type="submit">Login</button>
    </form>
</body>
</html>
