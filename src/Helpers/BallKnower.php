<?php
	declare(strict_types=1);

	namespace App\Helpers;

	class BallKnower {
		private static array $status = [
			1 => 'Certified Ball Knower',
			2 => 'Ball Knower',
			3 => 'Ball Knower in training',
			4 => 'Ball Enjoyer',
		];

		public static function status(int $rank): string
		{
			return self::$status[$rank] ?? 'You do not meet ball knowing expectations';
		}
	}