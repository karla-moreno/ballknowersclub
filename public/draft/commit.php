<?php
  require_once __DIR__ . '/../../src/Services/DraftService.php';

  use App\Services\DraftService;

  // TODO: protect against client side hax

  //	echo json_encode([
  //	  'success' => true,
  //		'message' => "Success!",
  //	]);

  $data = json_decode(file_get_contents('php://input'), true);

  //	$pickNumber = $data['number'];
  $teamId = $data['teamId'];
  $selection = $data['selection'];
  $user = $data['username'];
  $season = $data['season'];

  $teamName = $data['teamName'];

  try {
    $draftService = new DraftService();
    $draftService->saveDraftPick($teamId, $season, $user, $selection);

    echo json_encode([
      'success' => true,
      'message' => "{$user} uses pick to select {$teamName}'s {$selection}",
    ]);
  } catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
      'success' => false,
      'message' => 'Failed to save pick: ' . $e->getMessage(),
    ]);
  }
?>