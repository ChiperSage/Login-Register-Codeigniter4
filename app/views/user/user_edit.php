<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>
<body>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('errors')['username'] ?? '' ?>
            <?= session()->getFlashdata('errors')['password'] ?? '' ?>
            <?= session()->getFlashdata('errors')['email'] ?? '' ?>
        </div>
    <?php endif; ?>

    <h1>Edit User</h1>
    <form action="<?= base_url('/users/update/' . $user['id']) ?>" method="post">
        <?= csrf_field() ?>
        <div>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?= old('username', $user['username']) ?>">
        </div>
        <div>
            <label for="password">Password (leave blank to keep current password):</label>
            <input type="password" name="password" id="password">
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?= old('email', $user['email']) ?>">
        </div>
        <button type="submit">Update User</button>
    </form>
</body>
</html>
