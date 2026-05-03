<?php

  declare(strict_types=1);

  namespace App\Models;

  use App\Database\Database;
  use App\Enums\Season;
  use PDO;

  class NbaTeam {
    public function __construct(
      private string $name,
      private ?int $id = null,
    ) {}

    private static function db(): PDO {
      return Database::connection();
    }

    public function getName(): string {
      return $this->name;
    }

    public function getId(): ?int {
      return $this->id;
    }

    //		public function save(): void {
    //			$stmt = self::db()->prepare("
    //				INSERT INTO teams (name) VALUES (:name)
    //			");
    //			$stmt->execute([
    //				':name' => $this->name,
    //			]);
    //
    //			$this->id = (int)self::db()->lastInsertId();
    //		}

    //		public static function findById(int $id): ?self {
    //			$stmt = self::db()->prepare("SELECT * FROM users WHERE id = :id");
    //			$stmt->execute([':id' => $id]);
    //			$row = $stmt->fetch();
    //
    //			if (!$row) return null;
    //
    //			$user = new self($row['name'], $row['username'], $row['email'], $row['password'], $row['id']);
    //			return $user;
    //		}

    //		public static function all(): array {
    //			return self::db()->query("SELECT * FROM teams")->fetchAll();
    //		}

    //		public static function createTable(): void {
    //			self::db()->exec("
    //				CREATE TABLE IF NOT EXISTS teams (
    //			    id INTEGER PRIMARY KEY UNIQUE,
    //			    name TEXT NOT NULL UNIQUE
    //				)
    //			");
    //		}

    public static function allStandings(Season $season): array {
      $stmt = self::db()->prepare("
          SELECT record.*, team.name, team.slug, team.team_id, pick.username, pick.skin_select, pick.id, pick.pick_number as draft_pick_id,   
            CASE              
            	WHEN pick.skin_select = 'wins' THEN record.wins
              WHEN pick.skin_select = 'losses' THEN record.losses  
            END as skins
          FROM team_records record
          JOIN teams team ON team.team_id = record.team_id
          LEFT JOIN draft_picks pick ON pick.team_id = record.team_id AND pick.season =
  record.season
          WHERE record.season = :season
      ");
      $stmt->execute([':season' => $season->value]);
      return $stmt->fetchAll();
    }
  }