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
      <p>Colle une liste de sites, communautés ou URLs. L’app classe les sources, applique les règles de conformité, puis prépare des fiches AutoPostForm pour revue humaine.</p>
    </section>

    <form class="panel form" method="post" action="submit.php">
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
        Liste de sites / URLs / communautés
        <textarea name="sources" rows="11" required placeholder="https://www.reddit.com/r/greenhouses/&#10;https://www.youtube.com/@example&#10;https://example.com/blog&#10;https://www.facebook.com/groups/example"></textarea>
      </label>

      <label>
        Notes internes
        <textarea name="notes" rows="4" placeholder="Objectif, exclusions, ton, contexte client, contraintes..."></textarea>
      </label>

      <div class="checks">
        <label><input type="checkbox" name="confirm_rules" value="1" required> Je confirme que les sources seront traitées selon les APIs officielles ou en mode assisté si nécessaire.</label>
        <label><input type="checkbox" name="confirm_no_secrets" value="1" required> Je confirme qu’aucun secret, cookie, token ou mot de passe n’est inclus.</label>
      </div>

      <button type="submit">Créer les AutoPostForms</button>
    </form>
  </main>
</body>
</html>

