<?php

	declare(strict_types=1);

	namespace App\Enums;

	enum Season: string {
		case S25_26 = '25-26';
		case S26_27 = '26-27';

		public function label(): string
		{
			return match ($this) {
				Season::S25_26 => '2025-2026',
				Season::S26_27 => '2026-2027',
			};
		}
	}