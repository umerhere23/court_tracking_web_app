<?php
session_start();

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/Database.php';

$username = $_POST['username'];
$password = $_POST['password'];

$user = User::findByUsername($conn, $username);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];

    header('Location: ' . BASE_URL . '/index.php/dashboard');
    exit();
} else {
    echo 'Invalid credentials';
}