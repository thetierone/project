<?php include('../config.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/admin_functions.php'); ?>
<?php
$admins = getAdminUsers();
$roles = ['Admin', 'Author'];
?>
<?php include(ROOT_PATH . '/admin/includes/head_section.php'); ?>
<title>Admin | Manage users</title>
</head>
<body>
<?php if ($_SESSION['user']['role'] === 'Admin'): ?>
    <?php include(ROOT_PATH . '/admin/includes/navbar.php') ?>
    <div class="container content">
        <?php include(ROOT_PATH . '/admin/includes/menu.php') ?>
        <div class="action">
            <h1 class="page-title">Create/Edit Admin User</h1>

            <form method="post" action="<?php echo BASE_URL . 'admin/users.php'; ?>">

                <?php include(ROOT_PATH . '/includes/errors.php') ?>

                <?php if ($isEditingUser === true): ?>
                    <input type="hidden" name="admin_id" value="<?php echo $admin_id; ?>">
                <?php endif ?>
                <?php if (isset($_GET['edit-admin'])) {
                    $useris = getAdminById($_GET['edit-admin']);
                } ?>
                <input type="text" name="username"
                       value="<?php echo isset($useris['username']) ? $useris['username'] : '' ?>"
                       placeholder="Username">
                <input type="email" name="email" value="<?php echo isset($useris['email']) ? $useris['email'] : '' ?>"
                       placeholder="Email">
                <input type="password" name="password" placeholder="Password">
                <input type="password" name="passwordConfirmation" placeholder="Password confirmation">
                <select name="role">
                    <option value="" selected disabled>Assign role</option>
                    <?php foreach ($roles as $key => $role): ?>
                        <option value="<?php echo $role; ?>"><?php echo $role; ?></option>
                    <?php endforeach ?>
                </select>

                <?php if ($isEditingUser === true): ?>
                    <button type="submit" class="btn" name="update_admin">UPDATE</button>
                <?php else: ?>
                    <button type="submit" class="btn" name="create_admin">Save User</button>
                <?php endif ?>
            </form>
        </div>
        <div class="table-div">
            <?php include(ROOT_PATH . '/includes/messages.php') ?>

            <?php if (empty($admins)): ?>
                <h1>No admins in the database.</h1>
            <?php else: ?>
                <table class="table">
                    <thead>
                    <th>ID</th>
                    <th>Admin</th>
                    <th>Role</th>
                    <th colspan="2">Action</th>
                    </thead>
                    <tbody>
                    <?php foreach ($admins as $key => $admin): ?>
                        <tr>
                            <td><?php echo $key + 1; ?></td>
                            <td>
                                <?php echo $admin['username']; ?>, &nbsp;
                                <?php echo $admin['email']; ?>
                            </td>
                            <td><?php echo $admin['role']; ?></td>
                            <td>
                                <a class="fa fa-pencil btn edit"
                                   href="users.php?edit-admin=<?php echo $admin['id'] ?>">
                                </a>
                            </td>
                            <td>
                                <a class="fa fa-trash btn delete"
                                   href="users.php?delete-admin=<?php echo $admin['id'] ?>">
                                </a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            <?php endif ?>
        </div>
    </div>
<?php endif; ?></body>
</html>