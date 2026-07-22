<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('index.php');
}

verify_csrf();

if (($_POST['confirm_rules'] ?? '') !== '1' || ($_POST['confirm_outreach'] ?? '') !== '1' || ($_POST['confirm_no_secrets'] ?? '') !== '1') {
    http_response_code(400);
    exit('Required confirmations are missing.');
}

$clientId = trim((string) ($_POST['client_id'] ?? ''));
$territory = trim((string) ($_POST['territory'] ?? ''));
$keywords = array_values(array_filter(array_map('trim', explode(',', (string) ($_POST['keywords'] ?? '')))));
$defaultOffer = trim((string) ($_POST['default_offer'] ?? 'MarketingAuto'));
$notes = trim((string) ($_POST['notes'] ?? ''));
$sourceLines = normalize_lines((string) ($_POST['sources'] ?? ''));
$campaignRows = parse_csv_upload($_FILES['campaign_csv'] ?? []);

if ($clientId === '' || (count($sourceLines) === 0 && count($campaignRows) === 0)) {
    http_response_code(400);
    exit('Client and at least one source or CSV row are required.');
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

foreach ($campaignRows as $row) {
    $form = contact_form_record($row, $defaultOffer);
    $source = classify_source((string) (($row['contact_form_url'] ?? '') ?: ($row['website_url'] ?? '')));
    $source['source_id'] = $form['source_id'];
    $source['platform'] = 'contact_form';
    $source['source_type'] = 'contact_form';
    $source['access_mode'] = 'approved_after_human_review';
    $source['status'] = 'needs_review';
    $source['territory'] = $territory;
    $source['keywords'] = $keywords;
    $source['metadata'] = [
        'business_name' => (string) ($row['business_name'] ?? ''),
        'contact_name' => (string) ($row['contact_name'] ?? ''),
        'workflow_type' => 'contact_form_outreach',
    ];
    $sources[] = $source;
    $forms[] = $form;
}

$id = create_request([
    'client_id' => $clientId,
    'territory' => $territory,
    'keywords' => $keywords,
    'default_offer' => $defaultOffer,
    'notes' => $notes,
    'campaign_rows_count' => count($campaignRows),
    'sources' => $sources,
    'forms' => $forms,
]);

redirect_to('review.php?id=' . rawurlencode($id));
