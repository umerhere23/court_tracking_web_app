<?php

require_once __DIR__ . '/../includes/Database.php';

class CaseRecord
{
    public static function create($defendant_id)
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("INSERT INTO caserecord (defendant_ID) VALUES (:defendant_id)");
        $stmt->execute([':defendant_id' => $defendant_id]);

        return $db->lastInsertId();
    }

    public static function deleteCaseByID($caseID)
    {
        $db = Database::getInstance()->getConnection();

        // Validate caseID
        if (empty($caseID) || !is_numeric($caseID)) {
            throw new InvalidArgumentException("Invalid Case ID.");
        }

        // Delete the case itself
        $stmt = $db->prepare("DELETE FROM caserecord WHERE case_ID = :caseID");
        $stmt->execute([':caseID' => $caseID]);
    }

    public static function getAllCasesWithDetails()
    {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->query("
            SELECT cr.case_ID, d.name AS defendant_name, l.name AS lawyer_name
            FROM caserecord cr
            LEFT JOIN defendant d ON cr.defendant_ID = d.defendant_ID
            LEFT JOIN case_lawyer cl ON cr.case_ID = cl.case_ID
            LEFT JOIN lawyer l ON cl.lawyer_ID = l.lawyer_ID
            ORDER BY cr.case_ID DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function linkLawyer($caseID, $lawyerID)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO case_lawyer (case_ID, lawyer_ID) VALUES (?, ?)");
        $stmt->execute([$caseID, $lawyerID]);
    }

    public static function getStatistics() {
    $db = Database::getInstance();
    $pdo = $db->getConnection();

    // Total distinct cases from caserecord
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM caserecord");
    $stmt->execute();
    $total = $stmt->fetchColumn();

    // Active (status = 'open' or 'active') from charge
    $stmt = $pdo->prepare("SELECT COUNT(DISTINCT case_ID) FROM charge WHERE LOWER(status) IN ('open', 'active')");
    $stmt->execute();
    $active = $stmt->fetchColumn();

    // Pending from charge
    $stmt = $pdo->prepare("SELECT COUNT(DISTINCT case_ID) FROM charge WHERE LOWER(status) = 'pending'");
    $stmt->execute();
    $pending = $stmt->fetchColumn();

    // Closed from charge
    $stmt = $pdo->prepare("SELECT COUNT(DISTINCT case_ID) FROM charge WHERE LOWER(status) = 'closed'");
    $stmt->execute();
    $closed = $stmt->fetchColumn();

    return [
        'total' => $total,
        'active' => $active,
        'pending' => $pending,
        'closed' => $closed
    ];
    }


}