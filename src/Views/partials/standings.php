<?php
	declare(strict_types=1);
	require_once __DIR__ . '/../../../vendor/autoload.php';

	use App\Enums\NBATeam;
	use App\Enums\Season;
	use App\Services\NbaApiService;
	use function App\Helpers\getLogo;

	$team_records = NbaApiService::allTeamRecordsWithNames(Season::S25_26);
	dump($team_records);
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
						<th>Selected</th>
						<th>Record</th>
						<th>Skins</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($team_records as $record): ?>
						<tr>
							<td>
								<div class="flex items-center">
									<img
										src="<?= getLogo($record['team_id']); ?>"
										alt="<?= $record['name']; ?> logo"
										height="35"/>
									<span><?= $record['name']; ?></span>
								</div>
							</td>
							<td><span class="badge success">WINS</span></td>
							<td>
								<code><?= $record['wins'] . '-' . $record['losses'];; ?></code>
							</td>
							<td><code>61</code></td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</article>
	</div>
</section>
