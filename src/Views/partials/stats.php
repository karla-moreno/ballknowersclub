<?php
  declare(strict_types=1);
  require_once __DIR__ . '/../../../vendor/autoload.php';
  global $season;
  global $date;

  use App\Enums\Season;
  use App\Services\DraftConfigService;
  use App\Services\SeasonService;
  use App\Helpers\BallKnower;
  use App\Database\Database;
  use App\Auth\Auth;
  use App\Services\RankingService;

  $db = Database::connection();

  $SeasonService = new SeasonService();
  $DraftConfigService = new DraftConfigService();
  if (Auth::user()) {
    $current_username = htmlspecialchars(Auth::user()['username']);
  } else {
    $current_username = null;
  }

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

  $days_left = $SeasonService->getDaysUntilEnd($season, $date);
  $days_left_until_season_start = $SeasonService->getDaysUntilStart($season, $date);
  $registered_drafters = $DraftConfigService->getDrafters($season->value);
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
      <?php if (Auth::check()): ?>
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
					<?= $SeasonService->getCompletion
          ($season, $date)->percent; ?>%
				</span>
          </header>
          <h2>
            <?= ($days_left > 0) ? $days_left . ' days' : 'Season ended'; ?>
          </h2>
          <p class="text-light">
            <?= $days_left > 0 ?
              'Until season ends' :
              abs($days_left) . ' days ago';
            ?>
          </p>
          <meter
            value="<?= $SeasonService->getCompletion($season, $date)
              ->raw; ?>"
            min="0" max="1" low="0" high="1"
            optimum="1"></meter>
        </article>
      <?php endif; ?>
    </div>
  </section>
  <hr/>
<?php else: ?>
  <section class="section">
    <div class="row">
      <article class="card col-6" style="display: flex; flex-direction:
      column;">
        <header class="hstack justify-between items-center">
          <h4>Draft has not begun</h4>
          <span class="badge warning">
					  <?= $SeasonService->getSeasonDraftDay($season, true); ?>
				  </span>
        </header>
        <h2>
          <?= $SeasonService->getDaysDraftUntilStart($season, $date) ?> days
        </h2>
        <p class="text-light">
          Until draft begins
        </p>
        <progress style="margin-top: auto;" value=".5"
                  max="1"></progress>
      </article>

      <article class="card col-6" style="display: flex; flex-direction:
      column;">
        <header class="hstack justify-between items-center">
          <h4>Season begins</h4>
          <span
            class="badge outline">
					<?= $SeasonService->getSeasonStart($season, true); ?>
				</span>
        </header>
        <h2>
          <?= ($days_left_until_season_start > 0) ? $days_left_until_season_start . ' days' : 'Season underway'; ?>
        </h2>
        <p class="text-light">
          <?= $days_left > 0 ?
            'Until season starts' :
            abs($days_left) . ' days ago';
          ?>
        </p>
        <meter
          style="margin-top: auto;"
          value="<?= $SeasonService->getCompletion($season, $date)
            ->raw; ?>"
          min="0" max="1" low="0" high="1"
          optimum="1"></meter>
      </article>
    </div>
  </section>
  <hr/>
  <section class="section">
    <div class="row">
      <div class="card col-6">
        <header>
          <h4><?= 5 - count($registered_drafters); ?>-<?= 6 - count
            ($registered_drafters); ?> drafters needed</h4>
        </header>
        <?php if ($current_username && !in_array($current_username,
            $registered_drafters)): ?>
          <form method="POST" action="/draft/register.php">
            <input type="hidden" name="season" value="<?= $season->value; ?>">
            <input type="hidden" name="username"
                   value="<?= $current_username; ?>">
            <button
              type="submit" <?= count($registered_drafters) === 6 ? 'disabled' : '' ?>>
              Register for draft
            </button>
          </form>
        <?php endif; ?>
        <p class="text-light" style="margin-top: 1em;">
          Current drafters
        </p>
        <?php if (count($registered_drafters) === 0): ?>
          <i>No drafters registered</i>
        <?php else: ?>
          <ul style="margin-top: 1em;">
            <?php foreach ($registered_drafters as $drafter): ?>
              <li><?= $drafter; ?></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
      <article class="card col-6">
        <header class="hstack justify-between items-center"
                style="margin-bottom: 1em;">
          <h4>Draft order</h4>
          <!--          <span class="badge outline">-->
          <!--            TBD-->
          <!--          </span>-->
        </header>
        <?php include __DIR__ . '/lottery.php'; ?>
      </article>
    </div>
  </section>
<?php endif; ?>