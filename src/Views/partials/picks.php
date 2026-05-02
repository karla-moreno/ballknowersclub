<?php
  global $season;

  use App\Services\DraftService;
  use function App\Helpers\skinSelect;

  $DraftService = new DraftService();
  $picks = $DraftService->getDraftPicks($season->value);
?>

<div class="card" style="margin-bottom: 5em;">
  <table>
    <thead>
    <tr>
      <th>Pick</th>
      <th>Username</th>
      <th>Team</th>
      <th>Selection</th>
      <th>Season</th>
    </tr>
    </thead>
    <tbody id="draft-picks">
    <?php if (empty($picks)): ?>
      <tr id="no-picks">
        <td colspan="5" style="text-align: center;">
          <span>No picks yet.</span>
        </td>
      </tr>
    <?php else: ?>
      <?php foreach ($picks as $i => $pick): ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td><?= htmlspecialchars($pick['username']) ?></td>
          <td><?= htmlspecialchars($pick['team_name']) ?></td>
          <td>
            <span class="badge <?= skinSelect($pick['skin_select']); ?>">
              <?= htmlspecialchars($pick['skin_select']) ?>
            </span>
          </td>
          <td><?= htmlspecialchars($pick['season']) ?></td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
  </table>
  <script>
    let lastPickId = <?= !empty($picks) ? end($picks)['id'] : 'null' ?>;

    setInterval(async () => {
      try {
        const res = await fetch('/draft/latest-pick.php');
        const latestPick = await res.json();
        // 	if (data.id && data.id !== lastPickId) {
        // 		lastPickId = data.id;
        // 		console.log('New pick:', data);
        // 	}
        console.log('Polling — latestId:', latestPick.id, 'lastPickId:', lastPickId);

        if (latestPick.id && latestPick.id !== lastPickId) {
          lastPickId = latestPick.id;

          console.log(latestPick);
          if (document.getElementById('no-picks')) {
            document.getElementById('no-picks').remove();
          }
          const tbody = document.getElementById('draft-picks');
          const tr = document.createElement('tr');

          tr.innerHTML = `
      <td>${latestPick.id}</td>
      <td>${latestPick.username}</td>
      <td>${latestPick.team_name}</td>
      <td>
        <span class="badge ${latestPick.skin_select === 'wins' ? 'success' :
            'danger'}">
          ${latestPick.skin_select}
        </span>
      </td>
      <td>${latestPick.season}</td>
    `;
          tbody.appendChild(tr);
        }
      } catch (err) {
        console.error('Polling error:', err);
      }
    }, 2000);
  </script>
</div>