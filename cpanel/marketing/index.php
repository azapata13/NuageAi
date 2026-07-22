<?php
declare(strict_types=1);
require __DIR__ . '/lib.php';
?>
<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex,nofollow">
  <title><?= h(APP_NAME) ?></title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <main class="shell">
    <section class="panel intro">
      <p class="eyebrow">NUAGEai / DEV</p>
      <h1>MarketingAuto Intake</h1>
      <p>Colle une liste de sites ou importe un CSV d’entreprises avec leurs formulaires de contact. L’app prépare les fiches AutoPostForm pour approbation humaine avant toute automatisation.</p>
    </section>

    <form class="panel form" method="post" action="submit.php" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">

      <div class="grid two">
        <label>
          Client
          <input name="client_id" value="hort-americas-dev" required maxlength="80">
        </label>
        <label>
          Territoire
          <input name="territory" value="Canada" maxlength="80">
        </label>
      </div>

      <label>
        Mots-clés
        <input name="keywords" placeholder="greenhouse automation, irrigation monitoring, horticulture">
      </label>

      <label>
        Offre / message par défaut
        <input name="default_offer" value="MarketingAuto" placeholder="MarketingAuto, audit, consultation, service...">
      </label>

      <label>
        Liste de sites / URLs / communautés
        <textarea name="sources" rows="8" placeholder="https://www.reddit.com/r/greenhouses/&#10;https://www.youtube.com/@example&#10;https://example.com/blog&#10;https://www.facebook.com/groups/example"></textarea>
      </label>

      <label>
        CSV ContactForm Outreach
        <input type="file" name="campaign_csv" accept=".csv,text/csv">
        <span class="hint">Colonnes: business_name, website_url, contact_form_url, contact_name, language, offer, message_goal, notes</span>
      </label>

      <label>
        Notes internes
        <textarea name="notes" rows="4" placeholder="Objectif, exclusions, ton, contexte client, contraintes..."></textarea>
      </label>

      <div class="checks">
        <label><input type="checkbox" name="confirm_rules" value="1" required> Je confirme que les sources seront traitées selon les APIs officielles, le mode assisté ou une automatisation approuvée et conforme.</label>
        <label><input type="checkbox" name="confirm_outreach" value="1" required> Je confirme que les formulaires de contact seront soumis seulement après approbation humaine, quotas anti-spam, et sans contournement CAPTCHA/login.</label>
        <label><input type="checkbox" name="confirm_no_secrets" value="1" required> Je confirme qu’aucun secret, cookie, token ou mot de passe n’est inclus.</label>
      </div>

      <button type="submit">Créer les AutoPostForms</button>
    </form>
  </main>
</body>
</html>
