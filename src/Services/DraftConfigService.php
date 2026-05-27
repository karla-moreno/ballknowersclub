<?php
  declare(strict_types=1);

  namespace App\Services;

  require_once __DIR__ . '/../../vendor/autoload.php';

  use App\Database\Database;
  use Dotenv\Dotenv;
  use PDO;

  //  $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
  //  $dotenv->load();

  class DraftConfigService {
    public function __construct() {}

    private static function db(): PDO {
      return Database::connection();
    }

    public static function createDraftConfigTable(): void {
      $db = self::db();
      $db->exec("
				CREATE TABLE IF NOT EXISTS draft_config (
			    id INTEGER PRIMARY KEY AUTOINCREMENT,
			    season VARCHAR(10) NOT NULL,
			    drafters TEXT NOT NULL,
			    draft_order TEXT NOT NULL,
			    UNIQUE(season)
				)
			");
    }

    public function updateDrafters(string $season, string $username):
    void {
      self::createDraftConfigTable();
      $db = self::db();
      $db->prepare("
        INSERT INTO draft_config (season, drafters, draft_order)
        VALUES (:season, '[]', '[]')
        ON CONFLICT (season) DO NOTHING;")->execute([':season' => $season]);

      $stmt = $db->prepare("
        UPDATE draft_config 
        SET drafters = json_insert(drafters, '$[#]', :username)
        WHERE season = :season
      ");
      $stmt->execute([
        ':username' => $username,
        ':season' => $season,
      ]);
    }

    public function getDrafters(string $season): array {
      $db = self::db();
      $stmt = $db->prepare("SELECT drafters FROM draft_config WHERE season = ?");
      $stmt->execute([$season]);
      $result = $stmt->fetchColumn();

      if ($result !== false) {
        return json_decode((string)$result);
      } else {

        error_log('No drafters found for season ' . $season);
        return [];
      }
    }

    public function getDraftOrder(string $season): array {
      $db = self::db();
      $stmt = $db->prepare("SELECT draft_order FROM draft_config WHERE season = ?");
      $stmt->execute([$season]);
      if (!$stmt->fetchColumn()) {
        error_log('No drafters found for season ' . $season);
        return [];
      }
      return json_decode($stmt->fetchColumn()) ?? [];
    }

    public function saveDraftOrder(string $season, array $draft_order):
    void {
      $db = self::db();
      $stmt = $db->prepare("
        UPDATE draft_config
        SET draft_order = :draft_order
        WHERE season = :season");
      $stmt->execute([
        ':season' => $season,
        ':draft_order' => json_encode($draft_order),
      ]);
    }
  }