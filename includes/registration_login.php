<?php
$username = "";
$email = "";
$errors = [];

if (isset($_POST['reg_user'])) {

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password_1 = $_POST['password_1'];
    $password_2 = $_POST['password_2'];

    if (empty($username)) {
        array_push($errors, "Uhmm...We gonna need your username");
    }
    if (empty($email)) {
        array_push($errors, "Oops.. Email is missing");
    }
    if (empty($password_1)) {
        array_push($errors, "uh-oh you forgot the password");
    }
    if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match");
    }

    global $conn;
    $stmt = $conn->query("SELECT * FROM users WHERE username='$username' 
								OR email='$email' LIMIT 1");
    $result = $stmt->fetch();
    $user = $result;


    if ($user) {
        if ($user['username'] === $username) {
            array_push($errors, "Username already exists");
        }
        if ($user['email'] === $email) {
            array_push($errors, "Email already exists");
        }
    }

    if (count($errors) == 0) {
        $password = md5($password_1);//
        $sql = "INSERT INTO users (username, email, role, password, created_at, updated_at) 
					  VALUES(:username, :email, :role, :password, now(), now())";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'role' => 'Author'
        ]);
        $reg_user_id = $conn->lastInsertId();

        $_SESSION['user'] = getUserById($reg_user_id);

        // if user is admin, redirect to admin area
        if (in_array($_SESSION['user']['role'], ["Admin", "Author"])) {
            $_SESSION['message'] = "You are now logged in";
            // redirect to admin area
            header('location: ' . BASE_URL . 'admin/dashboard.php');
            exit(0);
        } else {
            $_SESSION['message'] = "You are now logged in";
            // redirect to public area
            header('location: index.php');
            exit(0);
        }
    }
}
if (isset($_POST['login_btn'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];


    if (empty($username)) {
        array_push($errors, "Username required");
    }
    if (empty($password)) {
        array_push($errors, "Password required");
    }
    if (empty($errors)) {

        $password = md5($password); //
        $sql = "SELECT * FROM users WHERE username='$username' and password='$password' LIMIT 1";
        $stmt = $conn->query($sql);
        $result = $stmt->fetch();

        if ($result) {

            $_SESSION['user'] = getUserById($result['id']);


            if (in_array($_SESSION['user']['role'], ["Admin", "Author"])) {
                $_SESSION['message'] = "You are now logged in";
                header('location: ' . BASE_URL . '/admin/dashboard.php');
                exit(0);
            } else {
                $_SESSION['message'] = "You are now logged in";
                header('location: index.php');
                exit(0);
            }
        } else {
            array_push($errors, 'Wrong credentials');
        }
    }
}
function getUserById($id)
{
    global $conn;
    $stmt = $conn->query("SELECT * FROM users WHERE id=$id LIMIT 1");
    $result = $stmt->fetch();
    return $result;
}
?>