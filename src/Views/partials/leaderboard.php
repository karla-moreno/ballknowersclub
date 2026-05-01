<?php
  declare(strict_types=1);
  require_once __DIR__ . '/../../../vendor/autoload.php';

  use App\Database\Database;
  use function App\Helpers\GetLogo;

  $db = Database::connection();
  try {
    $draftPicksWithRecords = $db->query("
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
    $grouped = array_reduce($draftPicksWithRecords,
      function ($carry, $pick) {
        $carry[$pick['username']][] = $pick;
        return $carry;
      }, []);
  } catch (PDOException $e) {
    $grouped = false;
  }
?>
<?php if ($grouped):
  usort($grouped, function ($a, $b) {
    return array_sum(array_column($b, 'skins')) - array_sum(array_column($a, 'skins'));
  });
  $leader = array_sum(array_column($grouped[0], 'skins')); ?>
  <section class="section">
    <div class="row">
      <div class="col-12">
        <h2>Leaderboard</h2>
      </div>
      <div class="card col-12">
        <div class="table">
          <table>
            <thead>
            <tr>
              <th>Drafter</th>
              <th>MVT</th>
              <th>Skins</th>
              <th>Behind</th>
            </tr>
            </thead>
            <tbody>
            <?php
              foreach ($grouped as $group):
                $skins = array_sum(array_column($group, 'skins'));
                $behind = $leader - $skins;
                $mvt = null;
                foreach ($group as $item) {
                  if ($mvt === null || $item['skins'] > $mvt['skins']) {
                    $mvt = $item;
                  }
                }
                ?>
                <tr>
                  <td><?= $group[0]['username']; ?></td>
                  <td>
                    <div class="flex items-center">
                      <img
                        src="<?= getLogo($mvt['team_id']); ?>"
                        alt="<?= $mvt['team_name']; ?> logo"
                        height="35"
                      />
                      <span><?= $mvt['team_name']; ?></span>
                    </div>
                  </td>
                  <td><?= $skins; ?></td>
                  <td><?= $behind; ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
  <hr/>
<?php endif; ?>