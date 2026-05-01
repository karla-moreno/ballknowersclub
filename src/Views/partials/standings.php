<?php
  declare(strict_types=1);
  require_once __DIR__ . '/../../../vendor/autoload.php';

  use App\Models\NBATeam;
  use App\Enums\Season;
  use function App\Helpers\getLogo;
  use function App\Helpers\skinSelect;

  $standings = NbaTeam::allStandings(Season::S25_26);
  usort($standings, fn($a, $b) => $b['skins'] <=> $a['skins']);
?>

<section id="standings" class="section">
  <div class="row">
    <div class="col-12"><h2 style="margin-bottom: 0;">Standings</h2></div>
    <article class="card col-12">
      <div class="table">
        <table>
          <thead>
          <tr>
            <th>Team</th>
            <th>Drafter</th>
            <th>Selected</th>
            <th>Record</th>
            <th>Skins</th>
          </tr>
          </thead>
          <tbody>
          <?php foreach ($standings as $standing): ?>
            <tr>
              <td>
                <div class="flex items-center">
                  <img
                    src="<?= getLogo($standing['team_id']); ?>"
                    alt="<?= $standing['name']; ?> logo"
                    height="35"/>
                  <span><?= $standing['name']; ?></span>
                </div>
              </td>
              <td>
                <?php if ($standing['username'] && $standing['draft_pick_id']): ?>
                  <strong>
                    <?= $standing['username']; ?>
                  </strong>
                  with #<?= $standing['draft_pick_id']; ?>
                <?php else: ?>
                  <em>Undrafted</em>
                <?php endif ?>
              </td>
              <td>
                <?php if ($standing['skin_select'] !== null): ?>
                  <span
                    class="badge <?= skinSelect($standing['skin_select']); ?>">
                <?= $standing['skin_select']; ?>
                </span>
                <?php endif; ?>
              </td>
              <td>
                <code>
                  <?= $standing['wins'] . '-' . $standing['losses']; ?>
                </code>
              </td>
              <td>
                <?php if ($standing['skins']) : ?>
                  <code>
                    <?= $standing['skins']; ?>
                  </code>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </article>
  </div>
</section>
<hr/>
