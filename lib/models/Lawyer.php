<?php
require_once __DIR__ . '/../includes/Database.php';

class Lawyer
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public static function create($data) {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            INSERT INTO lawyer (Name, Email, Phone_Number, Firm)
            VALUES (:Name, :Email, :Phone_Number, :Firm)
        ");

        $stmt->execute([
            ':Name'      => $data['Name'] ?? '',
            ':Email'      => $data['Email'] ?? '',
            ':Phone_Number'      => $data['Phone_Number'] ?? '',
            ':Firm'      => $data['Firm'] ?? '',
        ]);
    }

    public function all(): array
    {
        $stmt = $this->db->query("SELECT lawyer_ID, Name FROM lawyer ORDER BY Name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
