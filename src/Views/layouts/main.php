<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title><?= htmlspecialchars($title ?? 'App') ?></title>
		<link rel="stylesheet" href="https://unpkg.com/@knadh/oat/oat.min.css">
		<script src="https://unpkg.com/@knadh/oat/oat.min.js" defer></script>
		<link rel="stylesheet" href="/css/app.css">
	</head>
	<body>
		<?php require __DIR__ . '/../partials/nav.php'; ?>

		<main>
			<div class="container">
				<?= $content ?>
			</div>
		</main>

	</body>
</html>