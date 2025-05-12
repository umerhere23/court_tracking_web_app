<?php
require_once __DIR__ . '/../includes/Database.php';

class Lawyer
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public static function create($data)
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            INSERT INTO lawyer (Name, Email, Phone_Number, Firm)
            VALUES (:name, :email, :phone, :firm)
        ");

        $stmt->execute([
            ':name'  => $data['name'],
            ':email' => $data['email'] ?? '',
            ':phone' => $data['phone'] ?? '',
            ':firm'  => $data['firm'] ?? ''
        ]);

        return $db->lastInsertId();
    }

    public function all(): array
    // returns all entries from database for dynamic drop down menus
    {
        $stmt = $this->db->query("SELECT lawyer_ID, Name FROM lawyer ORDER BY Name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    
}