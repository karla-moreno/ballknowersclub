<?php

	declare(strict_types=1);

	namespace App\Enums;

	enum NBATeam: string {
		case Lakers = 'lakers';
		case Pistons = 'pistons';
		case Celtics = 'celtics';
		case Bulls = 'bulls';
		case Warriors = 'warriors';
		case Knicks = 'knicks';
		case Spurs = 'spurs';
		case Kings = 'kings';

		public function label(): string
		{
			return match ($this) {
				NBATeam::Lakers => 'Los Angeles Lakers',
				NBATeam::Pistons => 'Detroit Pistons',
				NBATeam::Celtics => 'Boston Celtics',
				NBATeam::Bulls => 'Chicago Bulls',
				NBATeam::Warriors => 'Golden State Warriors',
				NBATeam::Knicks => 'New York Knicks',
				NBATeam::Spurs => 'San Antonio Spurs',
				NBATeam::Kings => 'Sacramento Kings',
			};
		}

		public function logo(): string
		{
			return match ($this) {
				NBATeam::Lakers => 'https://cdn.nba.com/logos/nba/1610612747/global/L/logo.svg',
				NBATeam::Pistons => 'https://cdn.nba.com/logos/nba/1610612765/primary/L/logo.svg',
				NBATeam::Celtics => 'https://cdn.nba.com/logos/nba/1610612738/global/L/logo.svg',
				NBATeam::Bulls => 'https://cdn.nba.com/logos/nba/1610612741/global/L/logo.svg',
				NBATeam::Warriors => 'https://cdn.nba.com/logos/nba/1610612744/global/L/logo.svg',
				NBATeam::Knicks => 'https://cdn.nba.com/logos/nba/1610612752/global/L/logo.svg',
				NBATeam::Spurs => 'https://cdn.nba.com/logos/nba/1610612759/primary/L/logo.svg',
				NBATeam::Kings => 'https://cdn.nba.com/logos/nba/1610612758/primary/L/logo.svg',
			};
		}
	}