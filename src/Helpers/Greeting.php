<?php
	declare(strict_types=1);

	namespace App\Helpers;

	class Greeting {
		private static array $greetings = [
			'Hello',
			'Hey',
			'Howdy',
			'Greetings',
			'Welcome back',
			'Ahoy',
			'Sup'
		];

		public static function random(): string
		{
			return self::$greetings[array_rand(self::$greetings)];
		}

		public static function forSession(): string
		{
			if (!isset($_SESSION['greeting'])) {
				$_SESSION['greeting'] = self::random();
			}

			return $_SESSION['greeting'];
		}
	}