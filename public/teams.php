<?php
  declare(strict_types=1);
  require_once __DIR__ . '/../vendor/autoload.php';

  use App\Services\NbaApiService;
  use function App\Helpers\getLogo;

  $title = 'Teams';
  $teams = NbaApiService::allTeams();
  ob_start();
?>

  <h1>All teams</h1>

<?php if (empty($teams)): ?>
  <p>No teams yet.</p>
<?php else: ?>
  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Logo</th>
    </tr>
    <?php foreach ($teams as $t): ?>
      <tr>
        <td><?= htmlspecialchars((string)$t['team_id']) ?></td>
        <td>
          <?= htmlspecialchars($t['name']) ?></td>
        <td>
          <img
            src="<?= getLogo($t['team_id']); ?>"
            alt="<?= $t['name']; ?> logo"
            height="60"/>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
<?php endif; ?>

<?php
  $content = ob_get_clean();
  require __DIR__ . '/../src/Views/layouts/main.php';