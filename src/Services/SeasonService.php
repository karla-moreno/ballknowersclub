<?php

	declare(strict_types=1);

	namespace App\Services;

	use App\Enums\Season;

	use DateTime;
	use DateTimeImmutable;
	use DateTimeInterface;
	use InvalidArgumentException;

	require_once __DIR__ . '/../../vendor/autoload.php';

	class SeasonService {
		private const array SEASONS = [
			Season::S25_26->value => [
				'start' => '2025-10-21',
				'end' => '2026-04-12',
			],
			Season::S26_27->value => [
				'start' => '2026-10-21',
				'end' => '2027-04-12',
			]
		];

		private function makeDate(string $date): DateTimeImmutable {
			$dt = DateTimeImmutable::createFromFormat('Y-m-d', $date);

			if ($dt === false) {
				throw new InvalidArgumentException("Invalid season date format: '{$date}'. Expected Y-m-d.");
			}

			return $dt->setTime(0, 0, 0);
		}

		public function getSeasonDates(Season $season): object {
			$bounds = self::SEASONS[$season->value];

			return (object)[
				'start' => $this->makeDate($bounds['start']),
				'end' => $this->makeDate($bounds['end']),
			];
		}

		public function getSeasonStart(Season $season): DateTimeImmutable {
			return $this->getSeasonDates($season)->start;
		}

		public function getSeasonEnd(Season $season): DateTimeImmutable {
			return $this->getSeasonDates($season)->end;
		}

		public function getCompletion(Season $season, DateTimeInterface $date):
		?object {
			$dates = $this->getSeasonDates($season);

			$total = (float)$dates->end->getTimestamp() -
				$dates->start->getTimestamp();
			$elapsed = (float)$date->getTimestamp() - $dates->start->getTimestamp();

			return (object)[
				"raw" => round(min(max($elapsed / $total, 0.0), 1.0), 4),
				"percent" => round(min(max($elapsed / $total, 0.0), 1.0) * 100, 2)
			];
		}

		public function getDaysUntilEnd(Season $season, DateTimeInterface $date): int {
			$end = $this->getSeasonEnd($season);
			$current = $this->makeDate(new DateTime('@' . $date->getTimestamp())->format('Y-m-d'));
			$diff = $current->diff($end);

			return $diff->invert ? -$diff->days : $diff->days;
		}
	}