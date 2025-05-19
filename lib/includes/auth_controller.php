session_start();

require_once '../models/User.php';
require_once 'Database.php';

$username = $_POST['username'];
$username = $_POST['password'];

$user = User::findByUsername($conn, $username);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['role'] = $user['role'];
    header('Location: /public/index.php');
} else {
    echo 'Invalid credentials';
}