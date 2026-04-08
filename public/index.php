<?php
	declare(strict_types=1);
	require_once __DIR__ . '/../vendor/autoload.php';

	use Symfony\Component\VarDumper\VarDumper;
	use App\Models\User;

	$data = [
		'php' => PHP_VERSION,
		'env' => 'development',
		'loaded' => true,
	];

	$user = new User('Alice', 'alice@umbrellacorp.net');
	$user_name = $user->getName();
	$user_email = htmlspecialchars($user->getEmail(), ENT_QUOTES, 'UTF-8');

	dump($user);
	dump($data);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Index</title>
	</head>
	<body>

		<h1>Welcome to PHP <?php echo $data['php']; ?></h1>
		<h2>
			You are <?php echo $user_name; ?>
			<span>
				<?php echo '&lt;' . $user_email . '&gt;'; ?>
			</span>
		</h2>

	</body>
</html>
