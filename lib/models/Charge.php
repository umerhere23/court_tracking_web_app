<?php
require_once __DIR__ . '/../includes/Database.php';

class Charge
{
    public static function create($case_id, $data)
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            INSERT INTO charge (case_ID, Description, Status)
            VALUES (:case_id, :description, :status)
        ");

        $stmt->execute([
            ':case_id'     => $case_id,
            ':description' => $data['description'] ?? '',
            ':status'      => $data['status'] ?? ''
        ]);
    }
}
