<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';

$id = (string) ($_GET['id'] ?? '');
$request = load_request($id);

header('Content-Type: application/json; charset=utf-8');
header('Content-Disposition: attachment; filename="marketingauto-' . preg_replace('/[^A-Za-z0-9_.-]/', '-', $id) . '.json"');
echo json_encode($request, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

