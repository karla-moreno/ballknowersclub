<?php
  require_once __DIR__ . '/../../src/Services/DraftService.php';

  use App\Auth\Auth;
  use App\Services\DraftService;

  Auth::require();
  $session_user = Auth::user();

  if (!$session_user) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
  }

  $username = $session_user['username'];

  $data = json_decode(file_get_contents('php://input'), true);

  $teamId = $data['teamId'];
  $selection = $data['selection'];
  $season = $data['season'];

  $teamName = $data['teamName'];

  if (!$teamId || !in_array($selection, ['wins', 'losses'], true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    exit;
  }

  try {
    $draftService = new DraftService();
    $draftService->saveDraftPick($teamId, $season, $username, $selection);

    echo json_encode([
      'success' => true,
      'message' => "{$username} uses pick to select {$teamName}'s {$selection}",
    ]);
  } catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
      'success' => false,
      'message' => 'Failed to save pick: ' . $e->getMessage(),
    ]);
  }
?>