<?php

	declare(strict_types=1);

	namespace App\Auth;

	use App\Models\User;
	use App\Database\Database;

	class Auth {
		public static function start(): void
		{
			if (session_status() === PHP_SESSION_NONE) {
				session_start();
			}
		}

		public static function login(string $username, string $password): bool
		{
			$db = Database::connection();
			$stmt = $db->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
			$stmt->execute([':username' => $username]);
			$row = $stmt->fetch();

			if (!$row || !password_verify($password, $row['password'])) {
				return false;
			}

			$_SESSION['user'] = [
				'id' => $row['id'],
				'name' => $row['name'],
				'username' => $row['username'],
				'role' => $row['role'],
			];

			return true;
		}

		public static function logout(): void
		{
			self::start();
			session_destroy();
		}

		public static function user(): ?array
		{
			self::start();
			return $_SESSION['user'] ?? null;
		}

		public static function check(): bool
		{
			return self::user() !== null;
		}

		public static function role(): ?string
		{
			return self::user()['role'] ?? null;
		}

		public static function require(): void
		{
			self::start();

			if (!self::check()) {
				exit;
			}
		}

		public static function requireRole(string $role): void
		{
			self::start();

			if (!self::check()) {
				exit;
			}

			if (self::role() !== $role) {
				http_response_code(403);
				exit('Forbidden');
			}
		}
	}