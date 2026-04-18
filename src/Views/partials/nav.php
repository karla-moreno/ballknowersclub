<?php

	use App\Auth\Auth;
	use App\Helpers\Greeting;

?>
<nav class="container" style="padding-top: var(--space-4);">
	<?php if (Auth::check()): ?>
		<span style="display: block;">
			<?= Greeting::forSession() ?>, <?= htmlspecialchars(Auth::user()['name']); ?>
		</span>
		<div class="hstack" style="font-size: 13px;">
			<a href="/" style="text-transform: uppercase;">Home</a>
			<a href="/draft/index.php" style="text-transform: uppercase;">Draft</a>
			<a href="/logout.php" style="text-transform: uppercase;">Logout</a>
		</div>
	<?php else: ?>
		<a href="/login.php">Login</a>
	<?php endif; ?>
</nav>
