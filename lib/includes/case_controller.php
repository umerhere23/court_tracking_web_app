<?php
session_start();

require_once __DIR__ . '/../models/Defendant.php';
require_once __DIR__ . '/../models/Lawyer.php';
require_once __DIR__ . '/../models/CaseRecord.php';
require_once __DIR__ . '/../models/Charge.php';
require_once __DIR__ . '/../models/CourtEvent.php';

// Step-by-step wizard
if (str_starts_with($action, 'add/')) {
    $step = explode('/', $action)[1];

    switch ($step) {
        case 'defendant': require __DIR__ . '/../steps/add_defendant.php'; break;
        case 'charge':    require __DIR__ . '/../steps/add_charge.php'; break;
        case 'lawyer':    require __DIR__ . '/../steps/add_lawyer.php'; break;
        case 'event':     require __DIR__ . '/../steps/add_event.php'; break;
        case 'confirm':   require __DIR__ . '/../steps/add_confirm.php'; break;
        default:
            http_response_code(404);
            echo "Invalid step.";
    }
    return;
}