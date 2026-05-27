<?php
  require_once __DIR__ . '/../../src/Services/DraftService.php';

  use App\Auth\Auth;
  use App\Services\DraftConfigService;
  use App\Enums\Season;

  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
  }

  Auth::require();
  $session_user = Auth::user();
  if (!$session_user || $session_user['role'] !== 'admin') {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
    exit;
  }

  $season = Season::S26_27;
  $season_value = $season->value;
  $DraftConfigService = new DraftConfigService();

  $data = json_decode(file_get_contents('php://input'), true);

  $draft_order = $data;

  try {
    $DraftConfigService->saveDraftOrder($season_value, $draft_order);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode([
      'success' => true,
      'message' => "Draft order generated for $season_value",
    ]);
  } catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
      'success' => false,
      'message' => 'Failed to save draft order: ' . $e->getMessage(),
    ]);
  }
