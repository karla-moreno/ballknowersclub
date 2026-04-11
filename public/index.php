<?php
	declare(strict_types=1);
	require_once __DIR__ . '/../vendor/autoload.php';

	use App\Enums\NBATeam;
	use App\Enums\Season;
	use App\Services\SeasonService;
	use Symfony\Component\VarDumper\VarDumper;
	use App\Auth\Auth;
	use App\Helpers\BallKnower;

	use App\Services\NbaApiService;

	$title = 'Index';
	ob_start();

	$season_service = new SeasonService();
	$season = Season::S25_26;
	$date = new DateTimeImmutable();

	dump(NbaApiService::getRawSeasonStandingsFromTempData());
?>

	<h1>Welcome to Skins <?php echo Season::S25_26->label(); ?></h1>
<?php if (Auth::check()): ?>
	<div class="">
		<section class="section">
			<div class="row">
				<div class="col-12">
					<h2 style="margin-bottom: 0;">Stats</h2>
				</div>
				<article class="card col-4">
					<header class="hstack justify-between items-center">
						<h4>Skins collected</h4>
						<span class="badge warning">43.41%</span>
					</header>
					<h2>178</h2>
					<p class="text-light">of X total possible (X teams)</p>
					<progress value="178" max="410"></progress>
				</article>

				<article class="card col-4">
					<header class="hstack justify-between items-center">
						<h4>Ranking</h4>
						<span class="badge success">1</span>
					</header>
					<h2>You are #1</h2>
					<p class="text-light"><?php echo BallKnower::status(1); ?></p>
					<meter min="0" value="100" max="100" optimum="1"></meter>
				</article>

				<article class="card col-4">
					<header class="hstack justify-between items-center">
						<h4>Season completion</h4>
						<span
							class="badge outline"><?php echo $season_service->getCompletion
							($season, $date)->percent; ?>%</span>
					</header>
					<h2><?php echo $season_service->getDaysUntilEnd($season, $date); ?>
						days</h2>
					<p class="text-light">Until season ends</p>
					<meter
						value="<?php echo $season_service->getCompletion($season, $date)
							->raw; ?>"
						min="0" max="1" low="0" high="1"
						optimum="1"></meter>
				</article>
			</div>
		</section>
		<hr/>
		<section class="section">
			<div class="row">
				<div class="col-12"><h2 style="margin-bottom: 0;">Totals</h2></div>
				<article class="card col-6">
					<header>
						<h3>statictest (you)</h3>
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
							<tr>
								<td>
									<div class="flex items-center">
										<img src="<?php echo NBATeam::Spurs->logo(); ?>"
												 alt="San Antonio Spurs logo"
												 height="35"/>
										<span>San Antonio Spurs</span>
									</div>
								</td>
								<td><span class="badge success">WINS</span></td>
								<td><code>61</code></td>
								<td><code>19</code></td>
								<td><code>61</code></td>
							</tr>
							<tr>
								<td>
									<div class="flex items-center">
										<img src="<?php echo NBATeam::Knicks->logo(); ?>"
												 alt="San Antonio Spurs logo"
												 height="35"/>
										<span>New York Knicks</span>
									</div>
								</td>
								<td><span class="badge success">WINS</span></td>
								<td><code>58</code></td>
								<td><code>22</code></td>
								<td><code>58</code></td>
							</tr>
							<tr>
								<td>
									<div class="flex items-center">
										<img src="<?php echo NBATeam::Kings->logo(); ?>"
												 alt="Sacramento Kings logo"
												 height="35"/>
										<span>Sacramento Kings</span>
									</div>
								</td>
								<td><span class="badge danger">LOSSES</span></td>
								<td><code>21</code></td>
								<td><code>59</code></td>
								<td><code>59</code></td>
							</tr>
							<tr>
								<td>
								</td>
								<td></td>
								<td></td>
								<td><strong>Total</strong></td>
								<td><code>178</code></td>
							</tr>
							</tbody>
						</table>
					</div>
				</article>
				<article class="card col-6">
					<header>
						<h3>statictest</h3>
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
							<tr>
								<td>
									<div class="flex items-center">
										<img src="<?php echo NBATeam::Lakers->logo(); ?>"
												 alt="San Antonio Spurs logo"
												 height="35" width="35"/>
										<span>Los Angeles Lakers</span>
									</div>
								</td>
								<td><span class="badge danger">LOSSES</span></td>
								<td><code>61</code></td>
								<td><code>19</code></td>
								<td><code>61</code></td>
							</tr>
							<tr>
								<td>
									<div class="flex items-center">
										<img src="<?php echo NBATeam::Pistons->logo(); ?>"
												 alt="San Antonio Spurs logo"
												 height="35"/>
										<span>Detroit Pistons</span>
									</div>
								</td>
								<td><span class="badge success">WINS</span></td>
								<td><code>58</code></td>
								<td><code>22</code></td>
								<td><code>58</code></td>
							</tr>
							<tr>
								<td>
									<div class="flex items-center">
										<img src="<?php echo NBATeam::Bulls->logo(); ?>"
												 alt="Sacramento Kings logo"
												 height="35"/>
										<span>Chicago Bulls</span>
									</div>
								</td>
								<td><span class="badge danger">LOSSES</span></td>
								<td><code>21</code></td>
								<td><code>59</code></td>
								<td><code>59</code></td>
							</tr>
							<tr>
								<td>
								</td>
								<td></td>
								<td></td>
								<td><strong>Total</strong></td>
								<td><code>178</code></td>
							</tr>
							</tbody>
						</table>
					</div>
				</article>
			</div>
		</section>
		<hr/>
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
							<tr>
								<td>
									<div class="flex items-center">
										<img src="<?php echo NBATeam::Spurs->logo(); ?>"
												 alt="San Antonio Spurs logo"
												 height="35"/>
										<span>San Antonio Spurs</span>
									</div>
								</td>
								<td><span class="badge success">WINS</span></td>
								<td><code>61-19</code></td>
								<td><code>61</code></td>
							</tr>
							<tr>
								<td>
									<div class="flex items-center">
										<img src="<?php echo NBATeam::Knicks->logo(); ?>"
												 alt="San Antonio Spurs logo"
												 height="35"/>
										<span>New York Knicks</span>
									</div>
								</td>
								<td><span class="badge success">WINS</span></td>
								<td><code>58-22</code></td>
								<td><code>58</code></td>
							</tr>
							<tr>
								<td>
									<div class="flex items-center">
										<img src="<?php echo NBATeam::Kings->logo(); ?>"
												 alt="Sacramento Kings logo"
												 height="35"/>
										<span>Sacramento Kings</span>
									</div>
								</td>
								<td><span class="badge danger">LOSSES</span></td>
								<td><code>21-59</code></td>
								<td><code>59</code></td>
							</tr>
							<tr>
								<td>
									<div class="flex items-center">
										<img src="<?php echo NBATeam::Lakers->logo(); ?>"
												 alt="San Antonio Spurs logo"
												 height="35" width="35"/>
										<span>Los Angeles Lakers</span>
									</div>
								</td>
								<td><span class="badge danger">LOSSES</span></td>
								<td><code>61-19</code></td>
								<td><code>61</code></td>
							</tr>
							<tr>
								<td>
									<div class="flex items-center">
										<img src="<?php echo NBATeam::Pistons->logo(); ?>"
												 alt="San Antonio Spurs logo"
												 height="35"/>
										<span>Detroit Pistons</span>
									</div>
								</td>
								<td><span class="badge success">WINS</span></td>
								<td><code>58-22</code></td>
								<td><code>58</code></td>
							</tr>
							<tr>
								<td>
									<div class="flex items-center">
										<img src="<?php echo NBATeam::Bulls->logo(); ?>"
												 alt="Sacramento Kings logo"
												 height="35"/>
										<span>Chicago Bulls</span>
									</div>
								</td>
								<td><span class="badge danger">LOSSES</span></td>
								<td><code>21-59</code></td>
								<td><code>59</code></td>
							</tr>
							</tbody>
						</table>
					</div>
				</article>
			</div>
		</section>
		<hr/>
		<section id="draft" class="section">
			<div class="row">
				<div class="col-12"><h2 style="margin-bottom: 0;">Draft</h2></div>
				<article class="card col-12">
					<ol style="font-size: 18px;">
						<li><strong>statictest (you)</strong> selects
							<div class="items-center" style="display: inline;">
								<img style="margin-bottom: -4px"
										 src="<?php echo NBATeam::Spurs->logo(); ?>" alt="San Antonio Spurs
								logo" height="20"/>
								<span><strong>San Antonio Spurs</strong></span>
							</div>
							and their <strong style="color: var(--success);">wins</strong>
						</li>
						<li><strong>statictest</strong> selects
							<div class="items-center" style="display: inline;">
								<img style="margin-bottom: -4px"
										 src="<?php echo NBATeam::Pistons->logo(); ?>" alt="San Antonio Spurs
								logo" height="20"/>
								<span><strong>Detroit Pistons</strong></span>
							</div>
							and their <strong style="color: var(--success);">wins</strong</li>
					</ol>
				</article>
			</div>
		</section>
	</div>
<?php endif; ?>
<?php
	$content = ob_get_clean();
	require __DIR__ . '/../src/Views/layouts/main.php';
