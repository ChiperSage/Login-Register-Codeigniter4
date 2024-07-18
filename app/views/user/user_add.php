<!DOCTYPE html>
<html>
<head>
    <title>Add User</title>
</head>
<body>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('errors')['username'] ?? '' ?>
            <?= session()->getFlashdata('errors')['password'] ?? '' ?>
            <?= session()->getFlashdata('errors')['email'] ?? '' ?>
        </div>
    <?php endif; ?>

    <h1>Add User</h1>
    <form action="<?= base_url('/users/store') ?>" method="post">
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
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?= old('email') ?>">
        </div>
        <button type="submit">Add User</button>
    </form>
</body>
</html>
