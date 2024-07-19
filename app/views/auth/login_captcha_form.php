<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <p><?= esc($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <h1>Login</h1>
    <form action="<?= base_url('/auth/authenticateWithCaptcha') ?>" method="post">
        <?= csrf_field() ?>
        <div>
            <label for="identity">Username or Email:</label>
            <input type="text" name="identity" id="identity" value="<?= old('identity') ?>">
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
