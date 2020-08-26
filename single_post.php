<?php include('config.php'); ?>
<?php $posts = getPosts(); ?>
<?php
if (isset($_GET['post-slug'])) {
    $post = getPost($_GET['post-slug']);
}

$topics = getAllTopics();
?>
<?php include('includes/head_section.php'); ?>
    <title> <?php echo $post['title'] ?> | Tomas Blog</title>
    </head>
    <body>
<div class="container">
    <?php include(ROOT_PATH . '/includes/navbar.php'); ?>

    <div class="content">
        <div class="post-wrapper">
            <div class="full-post-div">
                <?php if ($post['published'] == false): ?>
                    <h2 class="post-title">Sorry... This post has not been published</h2>
                <?php else: ?>
                    <h2 class="post-title"><?php echo $post['title']; ?></h2>
                    <div class="post-body-div">
                        <?php echo html_entity_decode($post['body']); ?><br>
                        <img src="<?php echo BASE_URL . '/static/images/' . $post['image']; ?>" class="post_image"
                             alt="">
                    </div>
                <?php endif ?>
            </div>
        </div>
        <div class="post-sidebar">
            <div class="card">
                <div class="card-header">
                    <h2>Topics</h2>
                </div>
                <div class="card-content">
                    <?php foreach ($topics as $topic): ?>
                        <a
                                href="<?php echo BASE_URL . 'filtered_posts.php?topic=' . $topic['id'] ?>">
                            <?php echo $topic['name']; ?>
                        </a>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include(ROOT_PATH . '/includes/footer.php'); ?>