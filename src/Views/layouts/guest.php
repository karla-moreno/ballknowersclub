<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'App') ?></title>
    <link rel="stylesheet" href="/css/oat.min.css"/>
    <link rel="stylesheet" href="/css/app.css"/>
    <script src="/scripts/oat.min.js" defer></script>
    <meta property="og:image"
          content="https://www.ballknowersonly.xyz/images/BKO.png"/>
    <meta name="og:title" content="Ball knowers only"/>
    <meta name="og:description" content="BKO"/>
  </head>
  <body>
    <main>
      <div class="container">
        <?= $content ?>
      </div>
    </main>
  </body>
</html>