<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';

$id = (string) ($_GET['id'] ?? '');
$request = load_request($id);
$forms = $request['forms'] ?? [];
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex,nofollow">
  <title>AutoPostForms / <?= h($request['client_id'] ?? '') ?></title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <main class="shell wide">
    <section class="panel header-row">
      <div>
        <p class="eyebrow">AutoPostForms</p>
        <h1><?= h($request['client_id'] ?? '') ?></h1>
        <p><?= h($request['territory'] ?? '') ?> / <?= h(implode(', ', $request['keywords'] ?? [])) ?></p>
      </div>
      <div class="actions">
        <a class="button secondary" href="index.php">Nouvelle liste</a>
        <a class="button" href="export.php?id=<?= h(rawurlencode($id)) ?>">Exporter JSON</a>
      </div>
    </section>

    <?php if (!empty($request['notes'])): ?>
      <section class="panel compact">
        <h2>Notes internes</h2>
        <p><?= nl2br(h($request['notes'])) ?></p>
      </section>
    <?php endif; ?>

    <section class="cards">
      <?php foreach ($forms as $index => $form): ?>
        <?php
          $score = (int) ($form['score'] ?? 0);
          $meta = $form['metadata'] ?? [];
          $notes = $meta['compliance_notes'] ?? [];
        ?>
        <article class="panel card">
          <div class="card-top">
            <div>
              <p class="eyebrow"><?= h((string) ($form['platform'] ?? 'other')) ?> / <?= h((string) ($meta['access_mode'] ?? 'manual_review')) ?></p>
              <h2><?= h((string) (($meta['business_name'] ?? '') ?: 'Source ' . ((int) $index + 1))) ?></h2>
            </div>
            <span class="score <?= h(score_badge_class($score)) ?>"><?= $score ?></span>
          </div>

          <a class="source-link" href="<?= h((string) ($form['source_url'] ?? '#')) ?>" target="_blank" rel="noopener noreferrer"><?= h((string) ($form['source_url'] ?? '')) ?></a>

          <?php if (!empty($form['draft_message'])): ?>
            <div class="draft-preview">
              <h3>Message proposé</h3>
              <p><?= nl2br(h((string) $form['draft_message'])) ?></p>
            </div>
          <?php endif; ?>

          <dl class="facts">
            <div><dt>Guardian</dt><dd><?= h((string) ($form['guardian_status'] ?? '')) ?></dd></div>
            <div><dt>Fact-check</dt><dd><?= h((string) ($form['fact_check_status'] ?? '')) ?></dd></div>
            <div><dt>Décision</dt><dd><?= h((string) ($form['approval_decision'] ?: 'pending')) ?></dd></div>
          </dl>

          <div class="notes">
            <?php foreach ($notes as $note): ?>
              <p><?= h((string) $note) ?></p>
            <?php endforeach; ?>
          </div>

          <form method="post" action="update.php" class="decision-form">
            <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
            <input type="hidden" name="request_id" value="<?= h($id) ?>">
            <input type="hidden" name="form_id" value="<?= h((string) ($form['autopost_form_id'] ?? '')) ?>">

            <label>
              Réponse finale / note de traitement
              <textarea name="edited_message" rows="4" placeholder="Écrire ou ajuster la réponse à utiliser manuellement..."><?= h((string) ($form['edited_message'] ?? '')) ?></textarea>
            </label>

            <div class="grid two">
              <label>
                Décision
                <select name="approval_decision">
                  <?php
                    $selected = (string) ($form['approval_decision'] ?? '');
                    $options = [
                        '' => 'Pending',
                        'approve_for_manual_post' => 'Approve manual post',
                        'approve_for_crm_task' => 'Approve CRM task',
                        'request_changes' => 'Request changes',
                        'reject' => 'Reject',
                        'block_and_escalate' => 'Block and escalate',
                    ];
                    foreach ($options as $value => $label):
                  ?>
                    <option value="<?= h($value) ?>" <?= $selected === $value ? 'selected' : '' ?>><?= h($label) ?></option>
                  <?php endforeach; ?>
                </select>
              </label>
              <label>
                Approbateur
                <input name="approver" value="<?= h((string) ($form['approver'] ?? '')) ?>" placeholder="Nom">
              </label>
            </div>

            <button type="submit">Sauvegarder la décision</button>
          </form>
        </article>
      <?php endforeach; ?>
    </section>
  </main>
</body>
</html>
