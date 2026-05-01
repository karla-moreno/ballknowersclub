<?php
  declare(strict_types=1);
  require_once __DIR__ . '/../../../vendor/autoload.php';

  use App\Auth\Auth;
  use App\Database\Database;
  use function App\Helpers\GetLogo;
  use function App\Helpers\skinSelect;

  $db = Database::connection();
  //	$picks = $db->query("
  //    SELECT pick.*, team.name as team_name
  //    FROM draft_picks pick
  //    JOIN teams team ON pick.team_id = team.team_id
  //	")->fetchAll();
  $test = $db->query("
		SELECT pick.*, team.name as team_name, team_record.wins, team_record.losses,
			CASE 
				WHEN pick.skin_select = 'wins' THEN team_record.wins
				WHEN pick.skin_select = 'losses' THEN team_record.losses
			END as skins
		FROM draft_picks pick
		JOIN teams team ON pick.team_id = team.team_id
		JOIN team_records team_record ON pick.team_id = team_record.team_id
	")->fetchAll();
  $grouped = [];
  foreach ($test as $pick) {
    $grouped[$pick['username']][] = $pick;
  }
  ksort($grouped);

  if ($grouped):
    ?>

    <section class="section">
      <div class="row">
        <div class="col-12">
          <h2 style="margin-bottom: 0;">Totals</h2>
        </div>
        <?php foreach ($grouped as $username => $picks):
          $total = array_sum(array_column($picks, 'skins'));
          ?>
          <article class="card col-6">
            <header>
              <h3>
                <?= $username; ?>

                <?= Auth::check() && $username === htmlspecialchars(Auth::user()['username']) ?
                  '<span style="opacity: 0.5;">(you)</span>' :
                  '';
                ?>
              </h3>
            </header>

            <div class="table">
              <table>
                <thead>
                <tr>
                  <th>Team</th>
                  <th>Selected</th>
                  <th>Wins</th>
                  <th>Losses</th>
                  <th>Skins</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($picks as $pick): ?>
                  <tr>
                    <td>
                      <div class="flex items-center">
                        <img src="<?= getLogo($pick['team_id']); ?>"
                             alt="<?= $pick['team_name']; ?> logo"
                             height="35"/>
                        <span><?= $pick['team_name']; ?></span>
                      </div>
                    </td>
                    <td>
									<span
                    class="badge <?= skinSelect($pick['skin_select']); ?>"
                  >
										<?= $pick['skin_select']; ?>
									</span>
                    </td>
                    <td><?= $pick['wins']; ?></td>
                    <td><?= $pick['losses']; ?></td>
                    <td><?= $pick['skins']; ?></td>
                  </tr>
                <?php endforeach; ?>
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td><strong>Total</strong></td>
                  <td>
                    <code>
                      <strong><?= $total; ?></strong>
                    </code>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
    <hr/>
  <?php endif; ?>