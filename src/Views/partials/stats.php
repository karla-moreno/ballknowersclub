<?php
	declare(strict_types=1);
	require_once __DIR__ . '/../../../vendor/autoload.php';

	use App\Enums\Season;
	use App\Services\SeasonService;
	use App\Helpers\BallKnower;

	$season_service = new SeasonService();
	$season = Season::S25_26;
	$date = new DateTimeImmutable();
?>

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