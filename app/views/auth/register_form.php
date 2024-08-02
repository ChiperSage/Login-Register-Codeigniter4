<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('errors')['username'] ?? '' ?>
            <?= session()->getFlashdata('errors')['password'] ?? '' ?>
            <?= session()->getFlashdata('errors')['confirm_password'] ?? '' ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('/register') ?>" method="post">
        <?= csrf_field() ?>
        <div>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?= old('username') ?>">
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="text" name="email" id="email" value="">
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password">
        </div>
        <div>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password">
        </div>
        <button type="submit">Register</button>
    </form>
</body>
</html>
