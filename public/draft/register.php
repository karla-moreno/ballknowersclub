<?php
  declare(strict_types=1);

  require_once __DIR__ . '/../../vendor/autoload.php';

  use App\Auth\Auth;
  use App\Services\DraftConfigService;

  Auth::start();
  Auth::requireRole('admin', 'user');

  $season = $_POST['season'] ?? null;
  $username = Auth::user()['username'];

  if (!$season) {
    http_response_code(400);
    echo 'Season is required.';
    exit;
  }
  $DraftConfigService = new DraftConfigService();

  if (in_array($username, $DraftConfigService->getDrafters($season), true)) {
    http_response_code(409);
    echo 'Already registered.';
    exit;
  }

  if (!$DraftConfigService->updateDrafters($season, $username)) {
    http_response_code(409);
    echo 'Registration is full.';
    exit;
  }

  header('Location: /');
  exit;