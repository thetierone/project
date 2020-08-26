<?php
// Post variables
$post_id = 0;
$isEditingPost = false;
$published = 0;
$title = "";
$post_slug = "";
$body = "";
$featured_image = "";
$post_topic = "";

function getAllPosts()
{
    global $conn;
    if ($_SESSION['user']['role'] == "Admin") {
        $sql = "SELECT p.*, u.username as username FROM posts p LEFT JOIN users u on u.id = p.user_id";
    } elseif ($_SESSION['user']['role'] == "Author") {

        $user_id = $_SESSION['user']['id'];
        $sql = "SELECT p.*, u.username as username FROM posts p LEFT JOIN users u on u.id = p.user_id WHERE p.user_id=$user_id AND p.published = 1";
    }
    $stmt = $conn->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}
function getPostAuthorById($user_id)
{
    global $conn;
    $sql = "SELECT username FROM users WHERE id=$user_id";
    $stmt = $conn->query($sql);
    $result = $stmt->fetchAll();
    if ($result) {
        return $result['username'];
    } else {
        return null;
    }
}
if (isset($_POST['create_post'])) { createPost($_POST); }
if (isset($_GET['edit-post'])) {
    $isEditingPost = true;
    $post_id = $_GET['edit-post'];
    editPost($post_id);
}
if (isset($_POST['update_post'])) {
    updatePost($_POST);
}
if (isset($_GET['delete-post'])) {
    $post_id = $_GET['delete-post'];
    deletePost($post_id);
}

function createPost($request_values)
{
    global $conn, $errors, $title, $featured_image, $topic_id, $body, $published;
    $title = $request_values['title'];
    $body = htmlentities($request_values['body']);
    if (isset($request_values['topic_id'])) {
        $topic_id = $request_values['topic_id'];
    }
    $userId = $request_values['user_id'];
    if (isset($request_values['publish'])) {
        $published = $request_values['publish'];
    }
    $post_slug = makeSlug($title);
    if (empty($title)) { array_push($errors, "Post title is required"); }
    if (empty($body)) { array_push($errors, "Post body is required"); }
    if (empty($topic_id)) { array_push($errors, "Post topic is required"); }
    $featured_image = $_FILES['featured_image']['name'];
    if (empty($featured_image)) { array_push($errors, "Featured image is required"); }
    $target = "../static/images/" . basename($featured_image);
    if (!move_uploaded_file($_FILES['featured_image']['tmp_name'], $target)) {
        array_push($errors, "Failed to upload image. Please check file settings for your server");
    }
    $post_check_query = "SELECT * FROM posts WHERE slug='$post_slug' LIMIT 1";
    $result = $conn->prepare($post_check_query);
    $resultrow = $result->fetchColumn();

    if ($resultrow > 0) { // if post exists
        array_push($errors, "A post already exists with that title.");
    }
    if (count($errors) == 0) {
        $query = "INSERT INTO posts (user_id, title, slug, image, body, published, created_at, updated_at) VALUES('$userId', '$title', '$post_slug', '$featured_image', '$body', $published, now(), now())";
        if($conn->query($query)){ // if post created successfully
            $inserted_post_id = $conn->lastInsertId();
            $sql = "INSERT INTO post_topic (post_id, topic_id) VALUES($inserted_post_id, $topic_id)";
            $conn->query($sql);

            $_SESSION['message'] = "Post created successfully";
            header('location: posts.php');
            exit(0);
        }
    }
}
function editPost($id)
{
    global $conn, $title, $post_slug, $body, $published, $isEditingPost, $post_id;
    $sql = "SELECT * FROM posts WHERE id=$id LIMIT 1";
    $stmt = $conn->query($sql);
    return $stmt->fetch();
}

function updatePost($request_values)
{
    global $conn, $errors, $post_id, $title, $featured_image, $topic_id, $body, $published;

    $title = $request_values['title'];
    $body = $request_values['body'];
    $post_id = $request_values['post_id'];
    $published = isset($request_values['publish']) ? 1 : 0;
    if (isset($request_values['topic_id'])) {
        $topic_id = $request_values['topic_id'];
    }
    $post_slug = makeSlug($title);

    if (empty($title)) { array_push($errors, "Post title is required"); }
    if (empty($body)) { array_push($errors, "Post body is required"); }

        $featured_image = $_FILES['featured_image']['name'];
        $target = "../static/images/" . basename($featured_image);
        if (!move_uploaded_file($_FILES['featured_image']['tmp_name'], $target)) {
            array_push($errors, "Failed to upload image. Please check file settings for your server");
        }

    if (count($errors) == 0) {
        $query = "UPDATE posts SET title='$title', slug='$post_slug', views=0, image='$featured_image', body='$body', published=$published, updated_at=now() WHERE id=$post_id";
        if($conn->query($query)){
            if (isset($topic_id)) {
                $inserted_post_id = $conn->lastInsertId();;
                $sql = "INSERT INTO post_topic (post_id, topic_id) VALUES($inserted_post_id, $topic_id)";
                $conn->query($sql);
                $_SESSION['message'] = "Post updated successfully";
                header('location: posts.php');
                exit(0);
            }
        }
    }
}
function deletePost($post_id)
{
    global $conn;
    $sql = "DELETE FROM posts WHERE id=$post_id";
    if ($conn->query($sql)) {
        $_SESSION['message'] = "Post successfully deleted";
        header("location: posts.php");
        exit(0);
    }
}
if (isset($_GET['publish']) || isset($_GET['unpublish'])) {
    $param = $_GET['param'];
    $message = "";
    if (isset($_GET['publish'])) {
        $message = "Post published successfully";
        $post_id = $_GET['publish'];
        $param = $_GET['param'];
    } else if (isset($_GET['unpublish'])) {
        $message = "Post successfully unpublished";
        $post_id = $_GET['unpublish'];
        $param = $_GET['param'];
    }

    togglePublishPost($post_id, $param, $message);
}
function togglePublishPost($post_id, $param, $message)
{
    global $conn;
    $sql = "UPDATE posts SET published = !$param WHERE id=$post_id";

    try {
        $stmt = $conn->query($sql);
        $stmt->execute();
        $_SESSION['message'] = $message;
        header("location: posts.php");
        exit(0);
    } catch (Exception $exception) {
    }
}
?>
