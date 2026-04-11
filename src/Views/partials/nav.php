<?php

	use App\Auth\Auth;
	use App\Helpers\Greeting;

?>
<nav class="container" style="padding-top: var(--space-4);">
	<?php if (Auth::check()): ?>
		<span><?= Greeting::forSession() ?>, <?= htmlspecialchars(Auth::user()['name']) ?></span>
		<a href="/logout.php">Logout</a>
	<?php else: ?>
		<a href="/login.php">Login</a>
	<?php endif; ?>
</nav>
