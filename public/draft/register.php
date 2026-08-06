<?php
  declare(strict_types=1);

  require_once __DIR__ . '/../../vendor/autoload.php';

  use App\Auth\Auth;
  use App\Services\DraftConfigService;

  Auth::start();
  Auth::requireRole('admin', 'user');

  $season = $_POST['season'];
  $username = $_POST['username'];

  if (!$season || !$username) {
    http_response_code(400);
    echo 'Season and username are required.';
    exit;
  }
  $DraftConfigService = new DraftConfigService();

  $DraftConfigService->updateDrafters($season, $username);

  header('Location: /');
  exit;