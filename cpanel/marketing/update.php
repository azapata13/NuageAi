<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('index.php');
}

verify_csrf();

$requestId = (string) ($_POST['request_id'] ?? '');
$formId = (string) ($_POST['form_id'] ?? '');
$request = load_request($requestId);
$decision = (string) ($_POST['approval_decision'] ?? '');
$allowed = ['', 'approve_for_manual_post', 'approve_for_crm_task', 'request_changes', 'reject', 'block_and_escalate'];

if (!in_array($decision, $allowed, true)) {
    http_response_code(400);
    exit('Invalid decision.');
}

foreach ($request['forms'] as &$form) {
    if (($form['autopost_form_id'] ?? '') === $formId) {
        $form['edited_message'] = trim((string) ($_POST['edited_message'] ?? ''));
        $form['approval_decision'] = $decision;
        $form['approver'] = trim((string) ($_POST['approver'] ?? ''));
        $form['approved_at'] = starts_with_text($decision, 'approve_') ? gmdate('c') : '';
        switch ($decision) {
            case 'approve_for_manual_post':
                $form['next_action'] = 'manual_post';
                break;
            case 'approve_for_crm_task':
                $form['next_action'] = 'crm_task';
                break;
            case 'request_changes':
                $form['next_action'] = 'revise';
                break;
            case 'block_and_escalate':
                $form['next_action'] = 'incident';
                break;
            case 'reject':
                $form['next_action'] = 'none';
                break;
            default:
                $form['next_action'] = 'review';
        }
        break;
    }
}
unset($form);

save_request($request);
redirect_to('review.php?id=' . rawurlencode($requestId));
