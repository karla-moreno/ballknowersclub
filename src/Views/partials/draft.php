<?php
	declare(strict_types=1);
	require_once __DIR__ . '/../../../vendor/autoload.php';

	use App\Enums\NBATeam;

?>

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
