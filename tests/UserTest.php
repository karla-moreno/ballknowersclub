<?php

  declare(strict_types=1);

  use App\Models\User;

  beforeEach(function () {
    $this->user = new User('zombiekilla', 7);
  });

  it('has a username', function () {
    expect($this->user->getUsername())->toBe('zombiekilla');
  });

  it('casts to its username', function () {
    expect((string)$this->user)->toBe('zombiekilla');
  });

  it('has no id before saving', function () {
    expect($this->user->getId())->toBeNull();
  });

  it('verifies the favorite team', function () {
    expect($this->user->verifyFavoriteTeam(7))->toBeTrue()
                                              ->and($this->user->verifyFavoriteTeam(3))
                                              ->toBeFalse();
  });
