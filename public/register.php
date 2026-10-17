<?php
  declare(strict_types=1);

  require_once __DIR__ . '/../vendor/autoload.php';

  use App\Models\User;
  use App\Auth\Auth;
  use App\Enums\NBATeam;
  use App\Services\NbaApiService;

  $user = null;
  $errors = [];
  $success = null;

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $favorite_team = $_POST['favorite_team'] ?? '';
    if (!preg_match('/^[A-Za-z0-9_-]{3,20}$/', $username)) {
      $errors[] = 'Username must be 3–20 letters, numbers, _ or -.';
    }


    if (empty($password) || strlen($password) < 8) {
      $errors[] = 'Password must be at least 8 characters.';
    }

    if (empty($errors)) {
      try {
        $password = $_POST['password'];
        $user = new User($username, (int)$favorite_team);
        $user->setPassword($password);
        $user->save();

        Auth::login($username, $password);

        header('Location: /');
        exit;
      } catch (PDOException $e) {
        if ($e->getCode() === '23000') {
          $errors[] = 'User already exists.';
          $user = null;
        } else {
          throw $e;
        }
      }
    }
  }

  if ($_SERVER['REQUEST_METHOD'] != "GET" && $_SERVER['REQUEST_METHOD'] != "POST") {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
  }

  $title = 'Register';
  $teams = (new NbaApiService)::allTeams();
  // TODO: create reset password page
  ob_start();
?>
  <h1>Register</h1>
<?php if ($success): ?>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      ot.toast(<?= json_encode($success) ?>, 'Success', {variant: 'success'});
    });
  </script>
<?php endif; ?>

<?php if (!empty($errors)): ?>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      <?php foreach ($errors as $error): ?>
      ot.toast(<?= json_encode($error) ?>, 'Error', {variant: 'danger'});
      <?php endforeach; ?>
    });
  </script>
<?php endif; ?>
  <div>
    <?php if ($success): ?>
      <p><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="POST">
      <label data-field>
        Username
        <input type="text" name="username" required
               pattern="[A-Za-z0-9_-]{3,20}"
               title="3–20 letters, numbers, _ or -"
               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
      </label>
      <label data-field>
        Password
        <input type="password" name="password" required>
      </label>

      <!-- TODO: link favorite team for 2fa-->
      <div data-field>
        <label>Favorite team</label>
        <select aria-label="Select an option" name="favorite_team" required>
          <option value="">Select an option</option>
          <?php foreach ($teams as $team): ?>
            <option value="<?= $team['team_id'] ?>">
              <?= htmlspecialchars($team['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <br>
      <button type="submit">Create</button>
    </form>
  </div>
<?php
  $content = ob_get_clean();
  require __DIR__ . '/../src/Views/layouts/guest.php';