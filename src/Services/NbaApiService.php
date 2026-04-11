<?php
	declare(strict_types=1);

	namespace App\Services;
	
	require_once __DIR__ . '/../../vendor/autoload.php';

	use Dotenv\Dotenv;

	$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
	$dotenv->load();

	class NbaApiService {
		public function __construct() {}

		public static function getTeams(): array
		{
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

		public function saveTeam(string $teamName): void {}
	}