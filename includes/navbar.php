<div class="navbar">
    <div class="logo_div">
        <a href="http://www.tomas.lt:8008/"><h1>Tomas blog</h1></a>
    </div>
    <ul>
        <nav id="navbar_top" class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="http://www.tomas.lt:8008/"></a>
        <div data-pushbar-id="mypushbar1" data-pushbar-direction="left">
            <div class="card-content">
                <?php foreach ($topics as $topic): ?>
                    <a
                            href="<?php echo BASE_URL . 'filtered_posts.php?topic=' . $topic['id'] ?>">
                        <p><?php echo $topic['name'] . "<br>";?></p>
                    </a>
                <?php endforeach ?>
            </div>
            <button class="btn" data-pushbar-close><span>Close</span> </button>
        </div>

        <div data-pushbar-id="mypushbar2" data-pushbar-direction="bottom">
            <div class="card-content">
                <p><a href ="login.php">Login</a> <a href="register.php">Register on website</a> <a href="contact.php">Contact us</a></p>
            </div>
            <button data-pushbar-close class="btn"><span>Close</span></button>
        </div>

        <div class="pushbar_main_content">

            <li><button data-pushbar-target="mypushbar1" class="btn bg-dark">
                    <span>Website categories</span>
                </button></li>

            <li><button data-pushbar-target="mypushbar2" class="btn bg-dark">
                <span>Other menu</span>
                </button></li>
    </ul>
</div>
