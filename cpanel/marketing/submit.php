<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('index.php');
}

verify_csrf();

if (($_POST['confirm_rules'] ?? '') !== '1' || ($_POST['confirm_no_secrets'] ?? '') !== '1') {
    http_response_code(400);
    exit('Required confirmations are missing.');
}

$clientId = trim((string) ($_POST['client_id'] ?? ''));
$territory = trim((string) ($_POST['territory'] ?? ''));
$keywords = array_values(array_filter(array_map('trim', explode(',', (string) ($_POST['keywords'] ?? '')))));
$notes = trim((string) ($_POST['notes'] ?? ''));
$sourceLines = normalize_lines((string) ($_POST['sources'] ?? ''));

if ($clientId === '' || count($sourceLines) === 0) {
    http_response_code(400);
    exit('Client and source list are required.');
}

$sources = [];
$forms = [];

foreach ($sourceLines as $line) {
    $source = classify_source($line);
    $source['territory'] = $territory;
    $source['keywords'] = $keywords;
    $sources[] = $source;

    $score = initial_score($source, $keywords);
    $forms[] = [
        'autopost_form_id' => bin2hex(random_bytes(8)),
        'source_id' => $source['source_id'],
        'platform' => $source['platform'],
        'source_url' => $source['normalized_url'],
        'content_id' => '',
        'score' => $score,
        'guardian_status' => $source['access_mode'] === 'blocked' ? 'BLOQUE' : 'VALIDATION_HUMAINE_OBLIGATOIRE',
        'fact_check_status' => 'needs_review',
        'draft_message' => '',
        'edited_message' => '',
        'approval_decision' => '',
        'approver' => '',
        'approved_at' => '',
        'next_action' => 'review',
        'metadata' => [
            'source_type' => $source['source_type'],
            'access_mode' => $source['access_mode'],
            'compliance_notes' => $source['compliance_notes'],
        ],
    ];
}

$id = create_request([
    'client_id' => $clientId,
    'territory' => $territory,
    'keywords' => $keywords,
    'notes' => $notes,
    'sources' => $sources,
    'forms' => $forms,
]);

redirect_to('review.php?id=' . rawurlencode($id));

