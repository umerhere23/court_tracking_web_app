<?php
require_once __DIR__ . '/../includes/Database.php';

class Defendant
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public static function create($data) {
        $db = Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            INSERT INTO defendant (Name, Date_of_Birth, Address, Ethnicity, Phone_Number, Email)
            VALUES (:name, :dob, :address, :ethnicity, :phone, :email)
        ");

        $stmt->execute([
            ':name'      => $data['name'],
            ':dob'       => $data['dob'],
            ':address'   => $data['address'] ?? '',
            ':ethnicity' => $data['ethnicity'] ?? '',
            ':phone'     => $data['phone'] ?? '',
            ':email'     => $data['email'] ?? ''
        ]);
    }

    public function all(): array
    // returns all entries from database for dynamic drop down menus
    {
        $stmt = $this->db->query("SELECT defendant_ID, Name FROM defendant ORDER BY Name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search_fielded(string $field, string $term): array
    {
        $base_sql = "
            SELECT DISTINCT
                d.defendant_ID,
                d.Name AS Defendant_Name,
                d.Email AS Defendant_Email,
                cr.case_ID,
                ch.Description AS Charge_Description,
                ch.Status AS Charge_Status,
                l.Name AS Lawyer_Name,
                ce.Description AS Event_Description,
                ce.Location AS Event_Location,
                ce.Date AS Event_Date
            FROM defendant d
            LEFT JOIN caserecord cr ON cr.defendant_ID = d.defendant_ID
            LEFT JOIN charge ch ON ch.case_ID = cr.case_ID
            LEFT JOIN case_lawyer cl ON cl.case_ID = cr.case_ID
            LEFT JOIN lawyer l ON l.lawyer_ID = cl.lawyer_ID
            LEFT JOIN court_event ce ON ce.case_ID = cr.case_ID
        ";

        $field_map = [
            'name' => 'd.Name',
            'email' => 'd.Email',
            'charge' => 'ch.Description',
            'status' => 'ch.Status',
            'lawyer' => 'l.Name',
            'event' => 'ce.Description',
        ];

        if (!isset($field_map[$field])) {
            return [];
        }

        $sql = $base_sql . " WHERE " . $field_map[$field] . " LIKE :term";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':term' => '%' . $term . '%']);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
