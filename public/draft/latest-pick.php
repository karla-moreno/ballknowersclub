<?php
  require_once __DIR__ . '/../../src/Database/Database.php';
  require_once __DIR__ . '/../../src/Services/DraftService.php';

  use App\Database\Database;
  use App\Services\DraftService;
  use App\Enums\Season;

  $DraftService = new DraftService();
  $current_season = Season::S25_26;
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