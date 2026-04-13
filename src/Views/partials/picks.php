<?php

	use App\Database\Database;

	$db = Database::connection();
	$picks = $db->query("
    SELECT pick.*, team.name as team_name
    FROM draft_picks pick
    JOIN teams team ON pick.team_id = team.team_id
    ORDER BY pick.id ASC
	")->fetchAll();

	dump($picks);
?>

<div>
	<?php if (empty($picks)): ?>
		<p>No picks yet.</p>
	<?php else: ?>
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
			<?php foreach ($picks as $i => $pick): ?>
				<tr>
					<td><?= $i + 1 ?></td>
					<td><?= htmlspecialchars($pick['username']) ?></td>
					<td><?= htmlspecialchars($pick['team_name']) ?></td>
					<td><?= htmlspecialchars($pick['skin_select']) ?></td>
					<td><?= htmlspecialchars($pick['season']) ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
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
				console.log('Polling — latestId:', latestPick.id, 'lastPickId:',
					lastPickId);

				if (latestPick.id && latestPick.id !== lastPickId) {
					lastPickId = latestPick.id;

					console.log(latestPick);
					const tbody = document.getElementById('draft-picks');
					const tr = document.createElement('tr');

					tr.innerHTML = `
					  <td>${latestPick.id}</td>
					  <td>${latestPick.username}</td>
					  <td>${latestPick.team_name}</td>
					  <td>${latestPick.skin_select}</td>
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