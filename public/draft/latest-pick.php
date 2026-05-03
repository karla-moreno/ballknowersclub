<?php
  require_once __DIR__ . '/../../src/Database/Database.php';
  require_once __DIR__ . '/../../src/Services/DraftService.php';

  use App\Database\Database;
  use App\Services\DraftService;
  use App\Enums\Season;

  if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
  }

  $DraftService = new DraftService();
  $season_param = $_GET['season'];
  $current_season = Season::tryFrom($season_param);

  if (!$current_season) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid season.']);
    exit;
  }

  try {
    $DraftService::createDraftTable();
  } catch (PDOException $e) {
    error_log('Table already exists.');
  }

  try {
    $latest_pick = $DraftService::getLatestPick($current_season->value);

    echo json_encode($latest_pick ?: []);
  } catch (Exception $e) {
    error_log($e->getMessage());
  }