<?php
	declare(strict_types=1);

	namespace App\Services;

	require_once __DIR__ . '/../../vendor/autoload.php';

	use App\Database\Database;
	use Dotenv\Dotenv;

	$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
	$dotenv->load();

	class NbaApiService {
		public function __construct() {}

		public static function getTeams(): array {
			$BASE_URL = "https://api.balldontlie.io";
			$API_KEY = $_ENV['API_KEY'];
			$endpoint = "/nba/v1/teams";

			$options = [
				"http" => [
					"method" => "GET",
					"header" => implode("\r\n", [
						"Authorization: Bearer " . $API_KEY,
						"Content-Type: application/json",
						"Accept: application/json"
					])
				]
			];
			$url = $BASE_URL . $endpoint;
			$context = stream_context_create($options);
			$response = file_get_contents($url, false, $context);
			return json_decode($response, true);
		}

		public static function getRawSeasonStandingsFromApi(): array {
			$BASE_URL = "https://stats.nba.com/stats/leaguestandingsv3?GroupBy=conf&LeagueID=00&Season=2025-26&SeasonType=Regular%20Season&Section=overall";
			$options = [
				"http" => [
					"method" => "GET",
					"header" => implode("\r\n", [
						"Host: stats.nba.com",
						"User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15",
						"Accept: */*",
						"Accept-Language: en-US,en;q=0.9",
						"Connection: keep-alive",
						"Referer: https://www.nba.com/",
						"Origin: https://www.nba.com",
						"Sec-Fetch-Dest: empty",
						"Sec-Fetch-Mode: cors",
						"Sec-Fetch-Site: same-site"
					])
				]
			];
			$context = stream_context_create($options);
			$response = file_get_contents($BASE_URL, false, $context);
			if ($response === FALSE) {
				return ["ERROR"];
			} else {
				$tempFile = __DIR__ . '/../../temp/nba-temp-initial-raw.json';
				file_put_contents($tempFile, $response);
				return json_decode($response, true);
			}
		}

		public static function getRawSeasonStandingsFromTempData(): array {
			$PATH = __DIR__ . '/../../temp/nba-temp-initial-raw.json';
			$response = file_get_contents($PATH, true, null);
			return json_decode($response, true);
		}

		public static function createTeamsTable(): void {
			$db = Database::connection();
			$db->exec("
				CREATE TABLE IF NOT EXISTS teams (
			    id INTEGER PRIMARY KEY AUTOINCREMENT,
			    team_id BIGINT UNSIGNED NOT NULL UNIQUE,
			    name TEXT NOT NULL,
			    slug TEXT NOT NULL UNIQUE
				)
			");
		}

		public static function createTeamRecordsTable(): void {
			$db = Database::connection();
			$db->exec("
				CREATE TABLE IF NOT EXISTS team_records (
			    id INTEGER PRIMARY KEY AUTOINCREMENT,
			    team_id BIGINT UNSIGNED NOT NULL,
			    season VARCHAR(10) NOT NULL,
			    wins INTEGER NOT NULL,
			    losses INTEGER NOT NULL,
			    UNIQUE(team_id, season),
			    FOREIGN KEY (team_id) REFERENCES teams(team_id)
				)
			");
		}

		public function saveTeamById(string $teamName): void {}

		// TeamID 2
		// TeamSlug 5
		// WINS 13
		// LOSSES 14
		// Record 17
	}