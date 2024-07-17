<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('/login/authenticate') ?>" method="post">
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
        <button type="submit">Login</button>
    </form>
</body>
</html>
