<?php

	declare(strict_types=1);

	use App\Models\User;

	beforeEach(function () {
		$this->user = new User('Alice', 'zombiekilla', 'alice@umbrellacorp.net');
	});

	it('has a name', function () {
		expect($this->user->getName())->toBe('Alice');
	});

	it('has a username', function () {
		expect($this->user->getUsername())->toBe('zombiekilla');
	});