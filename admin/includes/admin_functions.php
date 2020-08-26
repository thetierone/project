<?php
$admin_id = 0;
$isEditingUser = false;
$username = "";
$role = "";
$email = "";
$topic_id = 0;
$isEditingTopic = false;
$topic_name = "";
$errors = [];

if (isset($_POST['create_admin'])) {
    createAdmin($_POST);
}

if (isset($_GET['edit-admin'])) {
    $isEditingUser = true;
    $admin_id = $_GET['edit-admin'];
    editAdmin($admin_id);
}

if (isset($_POST['update_admin'])) {
    updateAdmin($_POST);
}

if (isset($_GET['delete-admin'])) {
    $admin_id = $_GET['delete-admin'];
    deleteAdmin($admin_id);
}

if (isset($_POST['create_topic'])) {
    createTopic($_POST);
}

if (isset($_GET['edit-topic'])) {
    $isEditingTopic = true;
    $topic_id = $_GET['edit-topic'];
    editTopic($topic_id);
}

if (isset($_POST['update_topic'])) {
    updateTopic($_POST);
}

if (isset($_GET['delete-topic'])) {
    $topic_id = $_GET['delete-topic'];
    deleteTopic($topic_id);
}

function createTopic($request_values)
{
    global $conn, $errors, $topic_name;
    $topic_name = $request_values['topic_name'];
    $topic_slug = makeSlug($topic_name);

    if (empty($topic_name)) {
        array_push($errors, "Topic name required");
    }

    $topic_check_query = "SELECT * FROM topics WHERE slug='$topic_slug' LIMIT 1";
    $result = $conn->prepare($topic_check_query);
    $resultrow = $result->fetchColumn();
    if ($resultrow > 0) {
        array_push($errors, "Topic already exists");
    }

    if (count($errors) == 0) {
        $query = "INSERT INTO topics (name, slug) 
				  VALUES('$topic_name', '$topic_slug')";
        $conn->query($query);

        $_SESSION['message'] = "Topic created successfully";
        header('location: topics.php');
        exit(0);
    }
}

function editTopic($topic_id)
{
    global $conn, $topic_name, $topic_id;
    $sql = "SELECT * FROM topics WHERE id=$topic_id LIMIT 1";
    $result = $conn->query($sql);
    $topic = $result->fetchAll();

    $topic_name = $topic['name'];
}

function updateTopic($request_values)
{
    global $conn, $errors, $topic_name, $topic_id;
    $topic_name = ($request_values['topic_name']);
    $topic_id = ($request_values['topic_id']);

    $topic_slug = makeSlug($topic_name);

    if (empty($topic_name)) {
        array_push($errors, "Topic name required");
    }

    if (count($errors) == 0) {
        $query = "UPDATE topics SET name='$topic_name', slug='$topic_slug' WHERE id=$topic_id";
        $conn->query($query);

        $_SESSION['message'] = "Topic updated successfully";
        header('location: topics.php');
        exit(0);
    }
}

function deleteTopic($topic_id)
{
    global $conn;
    $sql = "DELETE FROM topics WHERE id=$topic_id";
    if ($conn->query($sql)) {
        $_SESSION['message'] = "Topic successfully deleted";
        header("location: topics.php");
        exit(0);
    }
}

function getAdminUsers()
{
    global $conn;
    $sql = "SELECT * FROM users WHERE role IS NOT NULL";
    $stmt = $conn->query($sql);
    return $stmt->fetchAll();
}

function makeSlug(string $string)
{
    $string = strtolower($string);
    return preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
}

function createAdmin($request_values)
{
    global $conn, $errors, $role, $username, $email;
    $username = $request_values['username'];
    $email = $request_values['email'];
    $password = $request_values['password'];
    $role = $request_values['role'];

    $errors = errorHandling($request_values);

    $sql = "SELECT * FROM users WHERE username='$username' 
							OR email='$email' LIMIT 1";
    $stmt = $conn->query($sql);
    $user = $stmt->fetch();

    if ($user) {
        $errors = userExistsError($errors, $request_values, $user);
    }

    if (count($errors) == 0) {
        $password = md5($password);
        $query = "INSERT INTO users (username, email, role, password, created_at, updated_at) 
				  VALUES('$username', '$email', '$role', '$password', now(), now())";
        $conn->query($query);
        $_SESSION['message'] = "Admin user created successfully";
        header('location: users.php');
        exit(0);
    }
}

function editAdmin($admin_id)
{
    global $conn, $username, $admin_id, $email;
    $sql = "SELECT * FROM users WHERE id=$admin_id LIMIT 1";
    $stmt = $conn->query($sql);
    $admin = $stmt->fetchAll();

    $username = $admin['username'];
    $email = $admin['email'];
}

function updateAdmin($request_values)
{
    global $conn, $errors, $role, $username, $isEditingUser, $admin_id, $email;
    $admin_id = $request_values['admin_id'];
    $isEditingUser = false;
    $username = ($request_values['username']);
    $email = ($request_values['email']);
    $password = ($request_values['password']);

    if (isset($request_values['role'])) {
        $role = $request_values['role'];
    }

    if (count($errors) == 0) {

        $password = md5($password);

        $query = "UPDATE users SET username='$username', email='$email', role='$role', password='$password' WHERE id=$admin_id";
        $conn->query($query);

        $_SESSION['message'] = "Admin user updated successfully";
        header('location: users.php');
        exit(0);
    }
}

function errorHandling($params)
{
    $errors = [];

    if (empty($params['username'])) {
        $errors[] = "Uhmm...We gonna need the username";
    }
    if (empty($params['email'])) {
        $errors[] = "Oops.. Email is missing";
    }
    if (empty($params['role'])) {
        $errors[] = "Role is required for admin users";
    }
    if (empty($params['password'])) {
        $errors[] = "uh-oh you forgot the password";
    }
    if ($params['password'] != $params['passwordConfirmation']) {
        $errors[] = "The two passwords do not match";
    }

    return $errors;
}

function userExistsError($errors, $resquestedParams, $validateParams)
{
    if ($resquestedParams['username'] === $validateParams['username']) {
        $errors[] = "Username already exists";
    }
    if ($resquestedParams['email'] === $validateParams['email']) {
        $errors[] = "Email already exists";
    }

    return $errors;
}

function deleteAdmin($admin_id)
{
    global $conn;
    $sql = "DELETE FROM users WHERE id=$admin_id";
    if ($conn->query($sql)) {
        $_SESSION['message'] = "User successfully deleted";
        header("location: users.php");
        exit(0);
    }
}

function getAdminById($id)
{
    global $conn;
    $sql = "SELECT * FROM users WHERE id=$id limit 1";
    $stmt = $conn->query($sql);
    return $stmt->fetch();
}

function getUsersCount()
{
    global $conn;
    $sql = "SELECT COUNT(*) FROM users";
    $stmt = $conn->query($sql);
    return $stmt->fetchColumn();
}

function getPostsCount()
{
    global $conn;
    $sql = "SELECT COUNT(*) FROM posts WHERE published = 1";
    $stmt = $conn->query($sql);
    return $stmt->fetchColumn();
}

function getTopicsCount()
{
    global $conn;
    $sql = "SELECT COUNT(*) FROM topics";
    $stmt = $conn->query($sql);
    return $stmt->fetchColumn();
}

?>