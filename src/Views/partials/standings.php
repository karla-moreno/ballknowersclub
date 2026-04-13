<?php
	declare(strict_types=1);
	require_once __DIR__ . '/../../../vendor/autoload.php';

	use App\Enums\NBATeam;
	use App\Enums\Season;
	use App\Services\NbaApiService;

	$team_records = NbaApiService::allTeamRecordsWithNames(Season::S25_26);
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
										src="https://cdn.nba.com/logos/nba/<?php echo $record['team_id']; ?>/primary/L/logo.svg"
										alt="<?php echo $record['name']; ?> logo"
										height="35"/>
									<span><?php echo $record['name']; ?></span>
								</div>
							</td>
							<td><span class="badge success">WINS</span></td>
							<td>
								<code><?php echo $record['wins'] . '-' . $record['losses'];; ?></code>
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
