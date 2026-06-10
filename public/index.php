<?php
  declare(strict_types=1);
  require_once __DIR__ . '/../vendor/autoload.php';

  use App\Enums\Season;
  use App\Services\SeasonService;
  use App\Auth\Auth;
  use App\Services\DraftConfigService;

  $title = 'Index';
  ob_start();

  $season_service = new SeasonService();
  $season = Season::S26_27;
  $date = new DateTimeImmutable();
  $DraftConfig = new DraftConfigService();
  $draft_config_for_season = $DraftConfig->getDraftConfig($season->value);
  //  dump($draft_config_for_season);
?>

  <h1>Welcome to Skins <?= $season->label(); ?></h1>

  <div class="">
    <?php include __DIR__ . '/../src/Views/partials/stats.php'; ?>
    <?php include __DIR__ . '/../src/Views/partials/leaderboard.php'; ?>
    <?php include __DIR__ . '/../src/Views/partials/totals.php'; ?>
    <?php include __DIR__ . '/../src/Views/partials/standings.php'; ?>
    <?php include __DIR__ . '/../src/Views/partials/draft.php'; ?>
  </div>

<?php
  $content = ob_get_clean();
  require __DIR__ . '/../src/Views/layouts/main.php';
