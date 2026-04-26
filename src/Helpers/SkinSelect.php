<?php
  declare(strict_types=1);

  namespace App\Helpers;

  function skinSelect(string $skinSelect): string {
    return $skinSelect === 'wins' ? 'success' : 'danger';
  }