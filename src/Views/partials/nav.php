<?php

  use App\Auth\Auth;
  use App\Helpers\Greeting;

?>
<nav class="container" style="padding-top: var(--space-4);">
  <?php if (Auth::check()): ?>
    <span style="display: block;">
			<?= Greeting::forSession() ?>, <?= htmlspecialchars(Auth::user()['username']); ?>
		</span>
    <div class="hstack" style="font-size: 13px; text-transform: uppercase;">
      <a href="/">Home</a>
      <a href="/draft/index.php">Draft</a>
      <a href="/logout.php">Logout</a>
    </div>
  <?php else: ?>
    <div class="hstack" style="font-size: 13px; text-transform: uppercase;">
      <a href="/login.php">Login</a>
      <a href="/register.php">Register</a>
    </div>
  <?php endif; ?>
</nav>
