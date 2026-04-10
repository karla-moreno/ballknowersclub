<?php

	declare(strict_types=1);

	require_once __DIR__ . '/../vendor/autoload.php';

	use App\Auth\Auth;

	Auth::start();

	$error = null;

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$email = trim($_POST['email'] ?? '');
		$password = $_POST['password'] ?? '';

		if (Auth::login($email, $password)) {
			header('Location: /');
			exit;
		}

		$error = 'Invalid email or password.';
	}
	$title = 'Login';
	ob_start();
?>

	<article class="card">
		<header>
			<h1>Login</h1>
			<p class="text-light">Get skinned, brother</p>
		</header>

		<div class="mt-4">
			<form method="POST">
				<label>
					Email
					<input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
				</label>
				<br>
				<label>
					Password
					<input type="password" name="password">
				</label>

				<?php if ($error): ?>
					<div class="mt-4 bg-danger" style="padding: var(--space-4)">
						<p><?= htmlspecialchars($error) ?></p>
					</div>
				<?php endif; ?>

				<footer class="hstack justify-center mt-4">
					<button type="submit">Login</button>
					<a class="outline" href="/">Return home</a>
				</footer>
			</form>
		</div>

	</article>

<?php
	$content = ob_get_clean();
	require __DIR__ . '/../src/Views/layouts/guest.php';