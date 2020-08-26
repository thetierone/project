<?php include(ROOT_PATH . '/includes/messages.php') ?>
<?php if (isset($_SESSION['user']['role'])) { ?>
    <div class="logged_in_info">
        <span>Welcome <?php echo $_SESSION['user']['username'] ?></span>
        | <?php if ($_SESSION['user']['role'] === 'Admin'): ?>
        <span><a href="admin/dashboard.php">Admin dashboard</a></span>
        | <?php endif; ?>
        | <?php if ($_SESSION['user']['role'] === 'Author'): ?>
            <span><a href="admin/posts.php">Manage posts</a></span>
            | <?php endif; ?>
        <span><a href="logout.php">logout</a></span>
    </div>
<?php } else { ?>
    <div class="banner">
        <div class="welcome_msg">
            <h1>Welcome to my blog!</h1>
            <p>
                Here you can find posts<br>
                On various topics<br>
                That will brighten your day<br>
                <span>~ Tomas</span>
            </p>
            <a href="register.php" class="btn">Register</a>
        </div>

        <div class="login_div">
            <form action="<?php echo BASE_URL . 'index.php'; ?>" method="post">
                <h2>Login</h2>
                <div style="width: 60%; margin: 0px auto;">
                    <?php include(ROOT_PATH . '/includes/errors.php') ?>
                </div>
                <input type="text" name="username" value="<?php echo $username; ?>" placeholder="Username">
                <input type="password" name="password" placeholder="Password">
                <button class="btn" type="submit" name="login_btn">Sign in</button>
            </form>
        </div>
    </div>
<?php } ?>