<?php

	declare(strict_types=1);

	namespace App\Database;

	use PDO;

	class Database {
		private static ?PDO $connection = null;

		public static function connection(): PDO
		{
			if (self::$connection === null) {
				$path = __DIR__ . '/../../storage/db.sqlite';

				self::$connection = new PDO("sqlite:{$path}");
				self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			}

			return self::$connection;
		}
	}