<?php
session_start();
$servername = 'db';
$username = "admin";
$password = "yourpass";
$dbname = "kcs_db";
$port = '3306';
$conn = new PDO("mysql:host=$servername;dbname=$dbname;port=$port;", $username, $password);


function getPosts()
{
    global $conn;

    $sql = "SELECT * FROM posts WHERE published=true";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return getTopic($result);
}

function getPostTopic($post_id)
{
    global $conn;

    $sql = "SELECT * FROM topics WHERE id=
			(SELECT topic_id FROM post_topic WHERE post_id=:post_id) LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':post_id', $post_id);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getPublishedPostsByTopic($topic_id)
{
    global $conn;
    $sql = "SELECT *
    FROM   posts ps 
    WHERE  ps.id IN (SELECT pt.post_id 
                 FROM   post_topic pt 
                 WHERE  pt.topic_id =:topic_id
                 GROUP  BY pt.post_id 
                 HAVING Count(1) = 1)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':topic_id', $topic_id);
    $stmt->execute();
    $result = $stmt->fetchAll();

    return getTopic($result);
}

function getTopicNameById($id)
{
    global $conn;
    $sql = "SELECT name FROM topics WHERE id=:id limit 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $topic = $stmt->fetch();
    return $topic['name'];
}

function getPost($slug)
{
    global $conn;

    $sql = "SELECT * FROM posts WHERE slug=:slug AND published=true LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':slug', $slug);
    $stmt->execute();
    $result = $stmt->fetch();

    if ($result) {
        $result['topic'] = getPostTopic($result['id']);
    }
    countViews($result['slug'], $result['views']);

    return $result;
}

function countViews($slug, $views)
{
    global $conn;

    $views += 1;

    $sql = "UPDATE posts SET views = :views WHERE slug = :slug";
    $stmt = $conn->prepare($sql);
    $stmt->execute(
        [
            'views' => $views,
            'slug' => $slug,
        ]
    );
}

function getAllTopics()
{
    global $conn;
    $sql = "SELECT * FROM topics";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll();
}

function getTopic($posts)
{
    $final_posts = [];
    foreach ($posts as $post) {
        $post['topic'] = getPostTopic($post['id']);
        $final_posts[] = $post;
    }

    return $final_posts;
}

define('ROOT_PATH', realpath(dirname(__FILE__)));
define('BASE_URL', 'http://www.tomas.lt:8008/');
?>
