<?php
require_once __DIR__ . '/../includes/Database.php';

class CourtEvent
{
    public static function create($case_id, $data)
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            INSERT INTO court_event (case_ID, Location, Description, Date)
            VALUES (:case_id, :location, :description, :date)
        ");

        $stmt->execute([
            ':case_id'     => $case_id,
            ':location'    => $data['location'] ?? '',
            ':description' => $data['description'] ?? '',
            ':date'        => $data['date'] ?? null
        ]);
    }

    public static function getEventsByCaseID($caseID)
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("SELECT * FROM court_event WHERE case_ID = :caseID");
        $stmt->execute([':caseID' => $caseID]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}