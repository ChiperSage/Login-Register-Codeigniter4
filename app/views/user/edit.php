<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
</head>
<body>
    <h1>Edit User</h1>
    <?php if (session()->getFlashdata('errors')): ?>
        <div>
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <p><?= esc($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form action="<?= base_url('/user/update/'.$user['user_id']) ?>" method="post">
        <?= csrf_field() ?>
        <div>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?= esc($user['username']) ?>">
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?= esc($user['email']) ?>">
        </div>
        <div>
            <label for="password">Password (leave blank if not changing):</label>
            <input type="password" name="password" id="password">
        </div>
        <div>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password">
        </div>
        <button type="submit">Update</button>
    </form>
    <a href="<?= base_url('/user') ?>">Back to User List</a>
</body>
</html>
