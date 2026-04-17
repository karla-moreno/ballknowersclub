<?php

	declare(strict_types=1);

	namespace App\Models;

	use PDO;
	use App\Database\Database;

	class User {
		public function __construct(
			private string $name,
			private string $username,
			private ?string $password = null,
			private ?int $id = null,
		) {}

		private static function db(): PDO {
			return Database::connection();
		}

		public function getName(): string {
			return $this->name;
		}

		public function getUsername(): string {
			return $this->username;
		}

		public function getId(): ?int {
			return $this->id;
		}

		public function setPassword(string $plaintext): void {
			$this->password = password_hash($plaintext, PASSWORD_BCRYPT);
		}

		public function save(): void {
			$stmt = self::db()->prepare("
				INSERT INTO users (name, username, password, role) VALUES (:name, :username, :password, :role)
			");
			$stmt->execute([
				':name' => $this->name,
				':username' => $this->username,
				':password' => $this->password,
				':role' => 'user'
			]);

			$this->id = (int)self::db()->lastInsertId();
		}

		public function delete(): void {
			if ($this->id === null) {
				throw new \RuntimeException("Cannot delete a user without an ID.");
			}

			$stmt = self::db()->prepare("DELETE FROM users WHERE id = :id");
			$stmt->execute([':id' => $this->id]);
		}

		public static function findById(int $id): ?self {
			$stmt = self::db()->prepare("SELECT * FROM users WHERE id = :id");
			$stmt->execute([':id' => $id]);
			$row = $stmt->fetch();

			if (!$row) return null;

			$user = new self($row['name'], $row['username'], $row['password'], $row['id']);
			return $user;
		}

		public static function all(): array {
			return self::db()->query("SELECT * FROM users")->fetchAll();
		}

		public static function createTable(): void {
			self::db()->exec("
				CREATE TABLE IF NOT EXISTS users (
			    id INTEGER PRIMARY KEY AUTOINCREMENT,
			    name TEXT NOT NULL,
			    username TEXT NOT NULL UNIQUE,
			    password TEXT NOT NULL,
       		role TEXT NOT NULL DEFAULT 'user'
				)
			");
		}
	}