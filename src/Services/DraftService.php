<?php
  declare(strict_types=1);

  namespace App\Services;

  require_once __DIR__ . '/../../vendor/autoload.php';

  use App\Database\Database;
  use App\Enums\Season;
  use Dotenv\Dotenv;
  use PDO;

  $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
  $dotenv->load();

  class DraftService {
    public function __construct() {}

    private static function db(): PDO {
      return Database::connection();
    }

    public function seedDraft(): void {
      NbaApiService::createTeamRecordsTable();
      $data = NbaApiService::getRawSeasonStandingsFromTempData();
      $users = ['okc_glazer', 'deadmau5', 'zombiekilla'];
      $select = ['wins', 'losses'];
      $rows = $data['resultSets'][0]['rowSet'];
      shuffle($rows);
      $pick_limit = intval(ceil(count($rows) / count($users)));
      $pick_counts = array_fill_keys($users, 0);
      dump(count($rows));
      $db = Database::connection();
      $db->beginTransaction();
      $db->exec("DELETE FROM draft_picks");
      $db->exec("DELETE FROM sqlite_sequence WHERE name='draft_picks'");
      $stmt = $db->prepare("INSERT INTO draft_picks (team_id, season, username, skin_select) VALUES (:team_id, :season, :username, :skin_select)");

      foreach ($rows as $row) {
        do {
          $user = $users[array_rand($users)];
        } while (self::hasUserReachedPickLimit($user, $pick_counts, $pick_limit));

        $pick_counts[$user] = $pick_counts[$user] + 1;

        $stmt->execute([
          ':team_id' => $row[2],
          ':season' => Season::S25_26->value,
          ':username' => $user,
          ':skin_select' => $select[array_rand($select)],
        ]);
      }
      dump($pick_limit);
      dump($pick_counts);
      $db->commit();
    }

    public function getDraftPicks(string $season): array {
      $db = Database::connection();
      $stmt = $db->prepare("SELECT pick.*, team.name as team_name
	    FROM draft_picks pick
	    JOIN teams team ON pick.team_id = team.team_id
	    WHERE pick.season = :season
	    ORDER BY pick.id ASC");
      $stmt->execute([':season' => $season]);
      return $stmt->fetchAll();
    }

    public static function createDraftTable(): void {
      $db = Database::connection();
      $db->exec("
				CREATE TABLE IF NOT EXISTS draft_picks (
			    id INTEGER PRIMARY KEY AUTOINCREMENT,
			    team_id BIGINT UNSIGNED NOT NULL,
			    username TEXT NOT NULL, 
			    season VARCHAR(10) NOT NULL,
			    skin_select VARCHAR(10) NOT NULL,
			    UNIQUE(team_id, season),
			    FOREIGN KEY (team_id) REFERENCES teams(team_id),
				  FOREIGN KEY (username) REFERENCES users(username)
				)
			");
    }

    public static function getLatestPick(string $season) {
      $db = self::db();
      $last_pick_stmt = $db->prepare("SELECT username FROM draft_picks WHERE season = ? ORDER BY id DESC LIMIT 1");
      $last_pick_stmt->execute([$season]);
      return $last_pick_stmt->fetch();
    }

    public function saveDraftPick(int $team_id, string $season, string $username, string $skin_select):
    void {
      $db = Database::connection();
      $stmt = $db->prepare("INSERT INTO draft_picks (team_id, season, username, skin_select) VALUES (:team_id, :season, :username, :skin_select)");
      $stmt->execute([
        ':team_id' => $team_id,
        ':season' => $season,
        ':username' => $username,
        ':skin_select' => $skin_select
      ]);
    }

    private function hasUserReachedPickLimit(string $user, array $pick_counts, float $pick_limit):
    bool {
      return ($pick_counts[$user]) >= $pick_limit;
    }
  }