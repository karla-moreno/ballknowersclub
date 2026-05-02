<?php
  require_once __DIR__ . '/../../src/Services/DraftService.php';

  use App\Auth\Auth;
  use App\Services\DraftService;
  use App\Database\Database;
  use App\Enums\Season;

  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
  }

  Auth::require();
  $session_user = Auth::user();

  if (!$session_user) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
  }

  $username = $session_user['username'];

  $draft_order = ['test', 'deadmau5', 'zombiekilla'];
  $draft_season = Season::S25_26;
  $draft_season_value = $draft_season->value;
  $DraftService = new DraftService();
  $last_pick = $DraftService::getLatestPick($draft_season_value);

  if (!$last_pick) {
    $current_drafter = $draft_order[0];
  } else {
    $last_index = array_search($last_pick['username'], $draft_order);
    $current_drafter = $draft_order[($last_index + 1) % count($draft_order)];
  }

  if ($username !== $current_drafter) {
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => "It's not your turn."]);
    exit;
  }

  $data = json_decode(file_get_contents('php://input'), true);

  $picked_teams = $DraftService::getPickedTeams($draft_season_value);

  $team_id = $data['teamId'];
  $selection = $data['selection'];
  $season = $data['season'];

  if (in_array($team_id, $picked_teams)) {
    http_response_code(409);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'debug' => $picked_teams, 'message' => 'This team has already been drafted.']);
    exit;
  }

  $team_name = $data['teamName'];

  if (!$team_id || !in_array($selection, ['wins', 'losses'], true)) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    exit;
  }

  try {
    $DraftService->saveDraftPick($team_id, $season, $username, $selection);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode([
      'success' => true,
      'debug' => $last_pick,
      'message' => "{$username} uses pick to select {$team_name}'s {$selection}",
    ]);
  } catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
      'success' => false,
      'message' => 'Failed to save pick: ' . $e->getMessage(),
    ]);
  }
?>