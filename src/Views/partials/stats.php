<?php
  declare(strict_types=1);
  require_once __DIR__ . '/../../../vendor/autoload.php';

  use App\Enums\Season;
  use App\Services\SeasonService;
  use App\Helpers\BallKnower;
  use App\Database\Database;
  use App\Auth\Auth;
  use App\Services\RankingService;

  $db = Database::connection();

  $season_service = new SeasonService();
  $season = Season::S25_26;
  $date = new DateTimeImmutable();
  $current_username = htmlspecialchars(Auth::user()['username']);
  try {
    $stat_stmt = $db->prepare("
      SELECT
        pick.username,
        COUNT(pick.id) as all_picks,
        SUM(
          CASE
            WHEN pick.skin_select = 'wins' THEN team_record.wins
            WHEN pick.skin_select = 'losses' THEN team_record.losses
          END
        ) as total_skins
      FROM draft_picks pick
      JOIN teams team ON pick.team_id = team.team_id
      JOIN team_records team_record ON pick.team_id = team_record.team_id
      WHERE pick.username = :username
      AND pick.season = :season
      GROUP BY pick.username
		");

    $stat_stmt->execute([
      ':username' => $current_username,
      ':season' => $season->value,
    ]);
    $stat = $stat_stmt->fetch();
  } catch (PDOException $e) {
    $stat = false;
  }

  $daysLeft = $season_service->getDaysUntilEnd($season, $date);
  //  dump($daysLeft);
?>
<?php if ($stat): ?>
  <?php
  $total_picks = $stat['all_picks'];
  $total_skins_possible = $stat['all_picks'] * 82;
  $total_skins_collected = $stat['total_skins'];
  $percent_collected = round(($total_skins_collected / $total_skins_possible * 100), 2);
  $percent_collected_color = $percent_collected >= 75 ? 'success' : ($percent_collected <= 45 ? 'danger' : 'warning');

  $ranking_service = new RankingService();
  $ranks = $ranking_service->getRankings($season->value);
  $user_rank = $ranking_service->getUserRank($current_username, $season->value);
  ?>
  <section class="section">
    <div class="row">
      <div class="col-12">
        <h2 style="margin-bottom: 0;">Stats</h2>
      </div>
      <article class="card col-4">
        <header class="hstack justify-between items-center">
          <h4>Skins collected</h4>
          <span class="badge <?= $percent_collected_color; ?>">
					  <?= $percent_collected . '%'; ?>
				  </span>
        </header>
        <h2>
          <?= $total_skins_collected; ?>
        </h2>
        <p class="text-light">
          <?= 'of ' . $total_skins_possible . ' total possible (' . $total_picks . ' teams)'; ?>
        </p>
        <progress value="<?= $total_skins_collected; ?>"
                  max="<?= $total_skins_possible; ?>"></progress>
      </article>

      <article class="card col-4">
        <header class="hstack justify-between items-center">
          <h4>Ranking</h4>
          <span class="badge success"><?= $user_rank['rank']; ?></span>
        </header>
        <h2>You are #<?= $user_rank['rank']; ?></h2>
        <p class="text-light">
          <?= BallKnower::status($user_rank['rank']); ?>
        </p>
        <meter
          min="1"
          value="<?= count($ranks) - $user_rank['rank'] + 1; ?>"
          max="<?= count($ranks); ?>"></meter>
      </article>

      <article class="card col-4">
        <header class="hstack justify-between items-center">
          <h4>Season completion</h4>
          <span
            class="badge outline">
					<?= $season_service->getCompletion
          ($season, $date)->percent; ?>%
				</span>
        </header>
        <h2>
          <?= ($daysLeft > 0) ? $daysLeft . ' days' : 'Season ended'; ?>
        </h2>
        <p class="text-light">
          <?= $daysLeft > 0 ?
            'Until season ends' :
            abs($daysLeft) . ' days ago';
          ?>
        </p>
        <meter
          value="<?= $season_service->getCompletion($season, $date)
            ->raw; ?>"
          min="0" max="1" low="0" high="1"
          optimum="1"></meter>
      </article>
    </div>
  </section>
<?php endif; ?>