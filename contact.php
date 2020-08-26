<?php include('config.php'); ?>
<?php include('includes/registration_login.php'); ?>
<?php include('includes/head_section.php'); ?>
<?php $posts = getPosts();
$topics = getAllTopics();?>
    <title>Tomas blog | Contact us</title>
    </head>
    <body>
<div class="container">
    <?php include(ROOT_PATH . '/includes/navbar.php'); ?>
    <div style="width: 40%; margin: 20px auto;">
        <form method="post" name="contact_form" action="contact-form-handler.php">
            <h2>Send us a message</h2>
            <input type="text" name="name" value="name" placeholder="Your name">
            <input type="email" name="email" value="email" placeholder="Email">
            <input type="text" name="message" value="message" placeholder="Message">
            <button type="submit" class="btn" name="send_mail">Send mail</button>
            <p><?php echo $_GET['msg'];?></p>
        </form>
    </div>
</div>
<?php include(ROOT_PATH . '/includes/footer.php'); ?>