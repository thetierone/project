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
            <h1>Choose the car you like:</h1>
            <p>
                <a href="http://www.tomas.lt:8008/filtered_posts.php?topic=13"><span>Audi</span></a> <a href="http://www.tomas.lt:8008/filtered_posts.php?topic=18"><span>Toyota</span></a><br>
                <a href="http://www.tomas.lt:8008/filtered_posts.php?topic=14"><span>BMW</span></a> <a href="http://www.tomas.lt:8008/filtered_posts.php?topic=17"><span>Volkswagen</span></a><br>
                <a href="http://www.tomas.lt:8008/filtered_posts.php?topic=15"><span>Mercedes-Benz</span></a> <a href="http://www.tomas.lt:8008/filtered_posts.php?topic=16"><span>Volvo</span></a><br>
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