<?php
require_once '../../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = :username");
    $stmt->bindParam(":username", $username);
    $stmt->execute();

    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password_hash"])) {
        session_start();
        $_SESSION['admin_logged_in'] = true;
        header("Location: dashboard.php");
    } else {
        header("Location: adminLogin.php?error=1");
    }
}
