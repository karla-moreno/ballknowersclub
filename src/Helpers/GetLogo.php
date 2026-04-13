<?php
	declare(strict_types=1);

	namespace App\Helpers;

	function getLogo(int $teamId): string {
		$teamId = strval($teamId);
		return "https://cdn.nba.com/logos/nba/{$teamId}/primary/L/logo.svg";
	}