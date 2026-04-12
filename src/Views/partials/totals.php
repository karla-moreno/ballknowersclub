<?php
	declare(strict_types=1);
	require_once __DIR__ . '/../../../vendor/autoload.php';

	use App\Enums\NBATeam;

?>
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
