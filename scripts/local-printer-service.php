<?php

// Standalone Direct Thermal Print Bridge for Windows Kiosk PC
// Allows the cloud-hosted web app (Render) to print directly to USB POS-58 with ZERO popup dialogs!

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Accept");
header("Access-Control-Allow-Private-Network: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header("Content-Type: application/json");
    echo json_encode(['status' => 'online', 'printer' => 'POS-58', 'port' => 18181]);
    exit;
}

$raw = file_get_contents('php://input');
$input = json_decode($raw, true);

$displayNumber = (string) ($input['display_number'] ?? '');
$meta = (string) ($input['meta'] ?? '');

if (empty($displayNumber)) {
    http_response_code(400);
    header("Content-Type: application/json");
    echo json_encode(['ok' => false, 'error' => 'Missing display_number']);
    exit;
}

require_once __DIR__ . '/../app/Services/PrinterService.php';

$service = new \App\Services\PrinterService();
$result = $service->printTicket($displayNumber, $meta, '127.0.0.1', 'POS-58');

header("Content-Type: application/json");
echo json_encode($result);
