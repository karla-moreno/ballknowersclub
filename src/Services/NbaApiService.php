<?php
	declare(strict_types=1);
	require_once __DIR__ . '/../../vendor/autoload.php';

	$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
	$dotenv->load();

	function balldontlie_get(string $endpoint)
	{
		$BASE_URL = "https://api.balldontlie.io";
		$API_KEY = $_ENV['API_KEY'];

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
