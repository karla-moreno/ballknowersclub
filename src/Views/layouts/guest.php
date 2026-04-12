<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title><?= htmlspecialchars($title ?? 'App') ?></title>
		<link rel="stylesheet" href="/css/oat.min.css"/>
		<link rel="stylesheet" href="/css/app.css"/>
		<script src="/scripts/oat.min.js" defer></script>
	</head>
	<body>
		<main>
			<div class="container">
				<?= $content ?>
			</div>
		</main>
	</body>
</html>