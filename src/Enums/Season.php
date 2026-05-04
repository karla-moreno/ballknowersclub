<?php

  declare(strict_types=1);

  namespace App\Enums;

  enum Season: string {
    case S25_26 = '25-26';
    case S26_27 = '26-27';

    public function label(bool $apiSpec = false): string {
      if ($apiSpec) {
        return match ($this) {
          Season::S25_26 => '2025-26',
          Season::S26_27 => '2026-27',
        };
      }
      return match ($this) {
        Season::S25_26 => '2025-2026',
        Season::S26_27 => '2026-2027',
      };
    }
  }