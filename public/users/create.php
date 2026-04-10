<?php
	declare(strict_types=1);

	require_once __DIR__ . '/../../vendor/autoload.php';

	use App\Models\User;

	$user = null;
	$errors = [];

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$name = trim($_POST['name'] ?? '');
		$email = trim($_POST['email'] ?? '');

		if (empty($name)) {
			$errors[] = 'Name is required.';
		}

		if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors[] = 'A valid email is required.';
		}

		if (empty($errors)) {
			$user = new User($name, $email);
		}
	}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Create User</title>
	</head>
	<body>

		<h1>Create User</h1>

		<?php if (!empty($errors)): ?>
			<ul>
				<?php foreach ($errors as $error): ?>
					<li><?= htmlspecialchars($error) ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ($user): ?>
			<p>Created: <?= htmlspecialchars((string)$user) ?></p>
		<?php endif; ?>

		<form method="POST">
			<label>
				Name
				<input type="text" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
			</label>
			<br>
			<label>
				Email
				<input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
			</label>
			<br>
			<button type="submit">Create</button>
		</form>

	</body>
</html>