<?php

  declare(strict_types=1);

  namespace App\Services;

  use App\Database\Database;

  class RankingService {
    public function __construct() {}

    public function getRankings(string $season): array {
      $db = Database::connection();
      $stmt = $db->prepare("
        SELECT
          pick.username,
          COUNT(pick.id) as all_picks,
          SUM(
            CASE
              WHEN pick.skin_select = 'wins' THEN team_record.wins
              WHEN pick.skin_select = 'losses' THEN team_record.losses
            END
          ) as total_skins
        FROM draft_picks pick
        JOIN teams team ON pick.team_id = team.team_id
        JOIN team_records team_record ON pick.team_id = team_record.team_id
        WHERE pick.season = :season
        GROUP BY pick.username
        ORDER BY total_skins DESC
      ");

      $stmt->execute([':season' => $season]);
      $results = $stmt->fetchAll();

      foreach ($results as $i => &$row) {
        $row['rank'] = $i + 1;
      }

      return $results;
    }

    public function getUserRank(string $username, string $season): ?array {
      $rankings = $this->getRankings($season);
      //			foreach ($rankings as $row) {
      //				if ($row['username'] === $username) {
      //					return $row;
      //				}
      //			}
      return array_find($rankings, fn($row) => $row['username'] === $username);

    }
  }