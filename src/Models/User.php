<?php

  declare(strict_types=1);

  namespace App\Models;

  use PDO;
  use App\Database\Database;

  class User {
    public function __construct(
      private string $username,
      private int $favorite_team,
      private ?string $password = null,
      private ?int $id = null,
    ) {}

    private static function db(): PDO {
      return Database::connection();
    }

    public function getUsername(): string {
      return $this->username;
    }

    public function getId(): ?int {
      return $this->id;
    }

    public function __toString(): string {
      return $this->username;
    }

    public function setPassword(string $plaintext): void {
      $this->password = password_hash($plaintext, PASSWORD_BCRYPT);
    }

    public function setFavoriteTeam(int $team_id): void {
      $this->favorite_team = $team_id;
      $stmt = self::db()->prepare("
        UPDATE users SET favorite_team = :favorite_team WHERE id = :id
      ");
      $stmt->execute([
        ':favorite_team' => $team_id,
        ':id' => $this->id,
      ]);
    }

    public function verifyFavoriteTeam(int $guess): bool {
      return $this->favorite_team === $guess;
    }

    public function save(): void {
      $stmt = self::db()->prepare("
				INSERT INTO users (username, password, role, favorite_team) VALUES (:username, :password, :role, :favorite_team)
			");
      $stmt->execute([
        ':username' => $this->username,
        ':password' => $this->password,
        ':role' => 'guest',
        ':favorite_team' => $this->favorite_team
      ]);

      $this->id = (int)self::db()->lastInsertId();
    }

    public function update(string $username, string $role, int $favorite_team): void {
      if ($this->id === null) {
        throw new \RuntimeException("Cannot update a user without an ID.");
      }

      $stmt = self::db()->prepare("
        UPDATE users SET username = :username, role = :role, favorite_team = :favorite_team WHERE id = :id
      ");
      $stmt->execute([
        ':username' => $username,
        ':role' => $role,
        ':favorite_team' => $favorite_team,
        ':id' => $this->id,
      ]);

      $this->username = $username;
      $this->favorite_team = $favorite_team;
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

      $user = new self($row['username'], $row['password'], $row['id']);
      return $user;
    }

    public static function all(): array {
      return self::db()->query("SELECT * FROM users")->fetchAll();
    }

    public static function createTable(): void {
      self::db()->exec("
				CREATE TABLE IF NOT EXISTS users (
			    id INTEGER PRIMARY KEY AUTOINCREMENT,
			    username TEXT NOT NULL UNIQUE,
			    password TEXT NOT NULL,
			    favorite_team BIGINT UNSIGNED NOT NULL,
       		role TEXT NOT NULL DEFAULT 'guest'
				)
			");
    }
  }