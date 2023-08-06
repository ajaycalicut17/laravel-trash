<?php

uses(\Ajaycalicut17\LaravelTrash\Tests\TestCase::class);
use Ajaycalicut17\LaravelTrash\Models\Trash;
use Ajaycalicut17\LaravelTrash\Tests\Models\User;

test('verify that the deleted model is trashed', function () {
    $user = User::factory()->create();

    $user->delete();

    $this->assertSoftDeleted($user);

    $this->assertDatabaseHas('trashes', [
        'trashable_type' => User::class,
        'trashable_id' => $user->id,
        'name' => User::trashName($user),
    ]);
});

test('check if the corresponding model has been restored from the trash', function () {
    $user = User::factory()->create();

    $user->delete();

    $trash = Trash::query()
        ->where('trashable_type', User::class)
        ->where('trashable_id', $user->id)
        ->first();

    $trash->restoreFromTrash();

    $this->assertModelMissing($trash);

    $this->assertNotSoftDeleted($user);
});

test('check if the model has been deleted from the trash', function () {
    $user = User::factory()->create();

    $user->delete();

    $trash = Trash::query()
        ->where('trashable_type', User::class)
        ->where('trashable_id', $user->id)
        ->first();

    $trash->deleteFromTrash();

    $this->assertModelMissing($trash);

    $this->assertModelMissing($user);
});

test('verify that all models have been deleted from the trash', function () {
    $this->assertDatabaseEmpty('trashes');

    $this->assertDatabaseEmpty('users');

    $users = User::factory(10)->create();

    $users->each(function ($user) {
        $user->delete();
    });

    $this->assertDatabaseCount('trashes', 10);

    Trash::emptyTrash();

    $this->assertDatabaseEmpty('trashes');

    $this->assertDatabaseEmpty('users');
});

test('check if the models are pruned', function () {
    $this->assertDatabaseEmpty('trashes');

    $this->assertDatabaseEmpty('users');

    $users = User::factory(10)->create([
        'deleted_at' => now(),
    ]);

    $trashes = [];
    $users->each(function (User $user, int $key) use (&$trashes) {
        $trashes[$key]['trashable_type'] = $user::class;
        $trashes[$key]['trashable_id'] = $user->id;
        $trashes[$key]['name'] = User::trashName($user);
        $trashes[$key]['created_at'] = config('trash.pruning_period');
        $trashes[$key]['updated_at'] = config('trash.pruning_period');
    });

    Trash::insert($trashes);

    $this->assertDatabaseCount('trashes', 10);

    config(['trash.pruning_status' => true]);

    $result = $this->artisan('model:prune', [
        '--model' => Trash::class,
    ]);

    expect($result === 0)->toBeTrue();

    $this->assertDatabaseEmpty('trashes');

    $this->assertDatabaseEmpty('users');
});

test('verify that models are not pruned if pruning status is disabled', function () {
    $this->assertDatabaseEmpty('trashes');

    $this->assertDatabaseEmpty('users');

    $users = User::factory(10)->create([
        'deleted_at' => now(),
    ]);

    $trashes = [];
    $users->each(function (User $user, int $key) use (&$trashes) {
        $trashes[$key]['trashable_type'] = $user::class;
        $trashes[$key]['trashable_id'] = $user->id;
        $trashes[$key]['name'] = User::trashName($user);
        $trashes[$key]['created_at'] = config('trash.pruning_period');
        $trashes[$key]['updated_at'] = config('trash.pruning_period');
    });

    Trash::insert($trashes);

    $this->assertDatabaseCount('trashes', 10);

    $result = $this->artisan('model:prune', [
        '--model' => Trash::class,
    ]);

    expect($result === 0)->toBeTrue();

    $this->assertDatabaseCount('trashes', 10);

    $this->assertDatabaseCount('users', 10);
});
