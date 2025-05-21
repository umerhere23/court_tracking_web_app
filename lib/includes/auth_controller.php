<?php
session_start();

require_once '../models/User.php';
require_once 'Database.php';

$username = $_POST['username'];
$password = $_POST['password'];

$user = User::findByUsername($conn, $username);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];

    header('Location: /dashboard');
    exit();
} else {
    echo 'Invalid credentials';
}