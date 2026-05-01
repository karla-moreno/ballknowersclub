<?php

  declare(strict_types=1);

  require_once __DIR__ . '/../vendor/autoload.php';

  use App\Auth\Auth;

  $error = null;

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (Auth::login($username, $password)) {
      header('Location: /');
      exit;
    }

    $error = 'Invalid username or password.';
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
          Username
          <input type="text" name="username"
                 value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
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