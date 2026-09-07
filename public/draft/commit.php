<?php
  require_once __DIR__ . '/../../src/Services/DraftService.php';

  use App\Auth\Auth;
  use App\Services\DraftConfigService;
  use App\Services\DraftService;
  use App\Services\NbaApiService;
  use App\Enums\Season;
  use App\Logging\Logger;

  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    Logger::instance()->error('Rejected non-POST request to commit.php');
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
  }

  Auth::require();
  $session_user = Auth::user();

  if (!$session_user) {
    http_response_code(401);
    header('Content-Type: application/json');
    Logger::instance()->error('Rejected unauthorized request to commit.php');
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
  }

  $username = $session_user['username'];
  $DraftConfigService = new DraftConfigService();
  $season = Season::S26_27;
  $season_value = $season->value;
  $DraftService = new DraftService();
  $NbaApiService = new NbaApiService();
  $last_pick = $DraftService::getLastDrafter($season_value);
  $all_team_ids = $NbaApiService::allTeamIds();

  try {
    $draft_order = $DraftConfigService->getDraftOrder($season->value);
  } catch (Exception $e) {
    $draft_order = [];
  }

  if (!$last_pick) {
    $current_drafter = $draft_order[0];
  } else {
    $last_index = array_search($last_pick['username'], $draft_order);
    $current_drafter = $draft_order[($last_index + 1) % count($draft_order)];
  }

  if ($username !== $current_drafter) {
    http_response_code(403);
    header('Content-Type: application/json');
    Logger::instance()->warning("Rejected out of turn request.", [
      'username' => $username,
      'current_drafter' => $current_drafter,
      'season' => $season_value,
      'last_pick' => $last_pick,
      'draft_order' => $draft_order,
    ]);
    echo json_encode(['success' => false, 'message' => "It's not your turn."]);
    exit;
  }

  $data = json_decode(file_get_contents('php://input'), true);

  $picked_teams = $DraftService::getPickedTeams($season_value);

  $team_id = $data['teamId'];
  $selection = $data['selection'];
  $season = $data['season'];

  if (!in_array($team_id, $all_team_ids)) {
    http_response_code(400);
    header('Content-Type: application/json');
    Logger::instance()->warning("Invalid team ID.", [
      'team_id' => $team_id,
      'season' => $season,
      'username' => $username,
      'selection' => $selection,
    ]);
    echo json_encode(['success' => false, 'message' => 'Invalid team ID.']);
    exit;
  }

  if (in_array($team_id, $picked_teams)) {
    http_response_code(409);
    header('Content-Type: application/json');
    Logger::instance()->warning("Team already drafted.", [
      'team_id' => $team_id,
      'season' => $season,
      'username' => $username,
      'selection' => $selection,
    ]);
    echo json_encode(['success' => false, 'debug' => $picked_teams, 'message' => 'This team has already been drafted.']);
    exit;
  }

  $team_name = $data['teamName'];

  if (!$team_id || !in_array($selection, ['wins', 'losses'], true)) {
    http_response_code(400);
    header('Content-Type: application/json');
    Logger::instance()->warning("Invalid input.", [
      'team_id' => $team_id,
      'season' => $season,
      'username' => $username,
      'selection' => $selection,
      'team_name' => $team_name,
    ]);
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    exit;
  }

  try {
    $DraftService->saveDraftPick($team_id, $season, $username, $selection);
    http_response_code(200);
    header('Content-Type: application/json');
    Logger::instance()->info("Pick saved.", [
      'team_id' => $team_id,
      'season' => $season,
      'username' => $username,
      'selection' => $selection,
    ]);
    echo json_encode([
      'success' => true,
      'debug' => $last_pick,
      'message' => "$username uses pick to select $team_name's $selection",
    ]);
  } catch (Exception $e) {
    $error_id = bin2hex(random_bytes(8));
    http_response_code(500);
    header('Content-Type: application/json');
    Logger::instance()->error("Failed to save pick.", [
      'id' => $error_id,
      'message' => $e->getMessage(),
      'file' => $e->getFile(),
      'line' => $e->getLine(),
    ]);
    echo json_encode([
      'success' => false,
      'message' => "Failed to save pick. Error ID: $error_id",
    ]);
  }
