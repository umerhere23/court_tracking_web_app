class User {
    public static function findByUsername($conn, $username) {
        $stmt = $conn->prepare('SELECT * FROM usres WHERE username = ?');
        $stmt->execute([$username]);
        return $stmt->fetch();
    }
}