<?php
require_once __DIR__ . '/../includes/Database.php';

class CaseRecord
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public static function create($data) {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            INSERT INTO caserecord (defendant_ID)
            VALUES (:defendant_ID)
        ");

        $stmt->execute([
            ':defendant_ID'      => (int)$data['defendant_ID'] ?? '',

        ]);
    }
}
