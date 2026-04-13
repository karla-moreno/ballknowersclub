<?php
	declare(strict_types=1);
	require_once __DIR__ . '/../vendor/autoload.php';

	use App\Enums\Season;
	use App\Services\SeasonService;
	use Symfony\Component\VarDumper\VarDumper;
	use App\Auth\Auth;

	use App\Services\NbaApiService;

	$title = 'Index';
	ob_start();

	$season_service = new SeasonService();
	$season = Season::S25_26;
	$date = new DateTimeImmutable();

	dump(NbaApiService::getRawSeasonStandingsFromTempData());
?>

	<h1>Welcome to Skins <?= Season::S25_26->label(); ?></h1>

<?php if (Auth::check()): ?>
	<div class="">
		<?php include __DIR__ . '/../src/Views/partials/stats.php'; ?>
		<hr/>
		<?php include __DIR__ . '/../src/Views/partials/totals.php'; ?>
		<hr/>
		<?php include __DIR__ . '/../src/Views/partials/standings.php'; ?>
		<hr/>
		<?php include __DIR__ . '/../src/Views/partials/draft.php'; ?>
	</div>
<?php endif; ?>

<?php
	$content = ob_get_clean();
	require __DIR__ . '/../src/Views/layouts/main.php';
