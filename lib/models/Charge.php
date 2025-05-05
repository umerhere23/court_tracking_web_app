<?php
require_once __DIR__ . '/../includes/Database.php';

class Charge
{
    public static function create($case_ID, $data)
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            INSERT INTO charge (case_ID, Description, Status)
            VALUES (:case_ID, :description, :status)
        ");

        $stmt->execute([
            ':case_ID'     => $case_ID,
            ':description' => $data['description'] ?? '',
            ':status'      => $data['status'] ?? ''
        ]);
    }
}
