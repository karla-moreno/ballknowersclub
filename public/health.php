<?php

declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';

use App\Database\Database;

header('Content-Type: application/json');

$health = [
  'status' => 'ok',
];
$status_code = 200;

try {
  Database::connection()->query('SELECT 1');
  $health['database'] = 'ok';
} catch (PDOException $e) {
  $health['database'] = 'error';
  $health['status'] = 'degraded';
  $status_code = 503;
}

http_response_code($status_code);
echo json_encode($health);
