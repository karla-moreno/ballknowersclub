<?php
	require_once __DIR__ . '/../../src/Database/Database.php';

	use App\Database\Database;

	$db = Database::connection();
	//	$pick = $db->query("SELECT * FROM draft_picks ORDER BY id DESC LIMIT 1")->fetch();
	$pick = $db->query("SELECT pick.*, team.name as team_name
    FROM draft_picks pick
    JOIN teams team ON pick.team_id = team.team_id
    ORDER BY pick.id DESC LIMIT 1")->fetch();

	echo json_encode($pick ?: []);