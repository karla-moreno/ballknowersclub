<?php
	declare(strict_types=1);
	require_once __DIR__ . '/../vendor/autoload.php';
	require_once '../src/Services/NbaApiService.php';

	use App\Enums\NBATeam;
	use Symfony\Component\VarDumper\VarDumper;
	use App\Models\User;
	use App\Auth\Auth;
	use App\Helpers\BallKnower;
	use App\Services\NbaApiService;

	$title = 'Index';
	ob_start();

	$user = new User('Alice', 'zombiekilla', 'alice@umbrellacorp.net');
	$user_name = $user->getName();
	$user_email = htmlspecialchars($user->getEmail(), ENT_QUOTES, 'UTF-8');
	dump(Auth::user());
	dump($_SESSION);
	dump(NbaApiService::getTeams());
?>

	<h1>Welcome to Skins 2026-2027</h1>
<?php if (Auth::check()): ?>
	<div class="">
		<section class="section">
			<div class="row">
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
						<span class="badge outline">Ends April 12th</span>
					</header>
					<h2>4.6%</h2>
					<p class="text-light">Season completion</p>
					<meter value="0.98" min="0" max="1" low="0" high="1" optimum="1"></meter>
				</article>
			</div>
		</section>
		<section class="section">
			<article class="card">
				<header>
					<h3>Your teams</h3>
				</header>

				<div class="table">
					<table>
						<thead>
						<tr>
							<th>Team</th>
							<th>Selected</th>
							<th>Wins</th>
							<th>Losses</th>
							<th>Skins earned</th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td>
								<div class="flex items-center">
									<img src="<?php echo NBATeam::Spurs->logo(); ?>" alt="San Antonio Spurs logo"
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
									<img src="<?php echo NBATeam::Pistons->logo(); ?>" alt="San Antonio Spurs logo"
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
									<img src="<?php echo NBATeam::Kings->logo(); ?>" alt="Sacramento Kings logo"
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
		</section>
	</div>
<?php endif; ?>
<?php
	$content = ob_get_clean();
	require __DIR__ . '/../src/Views/layouts/main.php';

	dump($user);
