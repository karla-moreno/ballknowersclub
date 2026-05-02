<?php
  declare(strict_types=1);
  require_once __DIR__ . '/../vendor/autoload.php';

  use App\Enums\Season;
  use App\Services\SeasonService;
  use App\Auth\Auth;

  $title = 'Index';
  ob_start();

  $season_service = new SeasonService();
  $season = Season::S25_26;
  $date = new DateTimeImmutable();
?>

  <h1>Welcome to Skins <?= $season->label(); ?></h1>

  <div class="">
    <?php if (Auth::check()): ?>
      <?php include __DIR__ . '/../src/Views/partials/stats.php'; ?>
      <hr/>
    <?php endif; ?>
    <?php include __DIR__ . '/../src/Views/partials/leaderboard.php'; ?>
    <?php include __DIR__ . '/../src/Views/partials/totals.php'; ?>
    <?php include __DIR__ . '/../src/Views/partials/standings.php'; ?>
    <?php include __DIR__ . '/../src/Views/partials/draft.php'; ?>
  </div>

<?php
  $content = ob_get_clean();
  require __DIR__ . '/../src/Views/layouts/main.php';
