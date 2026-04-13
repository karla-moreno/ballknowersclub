<?php
	declare(strict_types=1);

	namespace App\Services;

	require_once __DIR__ . '/../../vendor/autoload.php';

	use App\Database\Database;
	use App\Enums\Season;
	use Dotenv\Dotenv;

	$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
	$dotenv->load();

	class DraftService {
		public function __construct() {}

		public function seedDraft(): void {
			NbaApiService::createTeamRecordsTable();
			$data = NbaApiService::getRawSeasonStandingsFromTempData();
			$users = ['woosah', 'okc_glazer', 'Anonymous', 'deadmau5', 'zombiekilla'];
			$select = ['wins', 'losses'];
			$rows = $data['resultSets'][0]['rowSet'];
			$db = Database::connection();
			$db->beginTransaction();
			$db->exec("DELETE FROM draft_picks");
			$stmt = $db->prepare("INSERT INTO draft_picks (team_id, season, username, skin_select) VALUES (:team_id, :season, :username, :skin_select)");
			foreach ($rows as $row) {
				$stmt->execute([
					               ':team_id' => $row[2],
					               ':season' => Season::S25_26->value,
					               ':username' => $users[array_rand($users)],
					               ':skin_select' => $select[array_rand($select)],
				               ]);
			}
			$db->commit();
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

		public function saveDraftPick(int $teamId, string $season, string $username, string $skin_select):
		void {
			$db = Database::connection();
			$stmt = $db->prepare("INSERT INTO draft_picks (team_id, season, username, skin_select) VALUES (:team_id, :season, :username, :skin_select)");
			$stmt->execute([
				               ':team_id' => $teamId,
				               ':season' => $season,
				               ':username' => $username,
				               ':skin_select' => $skin_select
			               ]);
		}
	}