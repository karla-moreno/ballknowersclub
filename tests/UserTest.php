<?php

	declare(strict_types=1);

	use App\Models\User;

	it('has a name', function () {
		$user = new User('Alice', 'alice@umbrellacorp.net');
		expect($user->getName())->toBe('Alice');
	});

	it('has an email', function () {
		$user = new User('Alice', 'alice@umbrellacorp.net');
		expect($user->getEmail())->toBe('alice@umbrellacorp.net');
	});