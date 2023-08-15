<?php

uses(\Ajaycalicut17\LaravelTrash\Tests\TestCase::class);
use Ajaycalicut17\LaravelTrash\Models\Trash;
use Ajaycalicut17\LaravelTrash\Tests\Models\User;
use function Pest\Laravel\artisan;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseEmpty;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertModelMissing;
use function Pest\Laravel\assertNotSoftDeleted;
use function Pest\Laravel\assertSoftDeleted;

test('verify that the deleted model is trashed', function () {
    $user = User::factory()->create();

    $user->delete();

    assertSoftDeleted($user);

    assertDatabaseHas('trashes', [
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

    assertModelMissing($trash);

    assertNotSoftDeleted($user);
});

test('check if the model has been deleted from the trash', function () {
    $user = User::factory()->create();

    $user->delete();

    $trash = Trash::query()
        ->where('trashable_type', User::class)
        ->where('trashable_id', $user->id)
        ->first();

    $trash->deleteFromTrash();

    assertModelMissing($trash);

    assertModelMissing($user);
});

test('verify that all models have been deleted from the trash', function () {
    assertDatabaseEmpty('trashes');

    assertDatabaseEmpty('users');

    $users = User::factory(10)->create();

    $users->each(function ($user) {
        $user->delete();
    });

    assertDatabaseCount('trashes', 10);

    Trash::emptyTrash();

    assertDatabaseEmpty('trashes');

    assertDatabaseEmpty('users');
});

test('check if the models are pruned', function () {
    assertDatabaseEmpty('trashes');

    assertDatabaseEmpty('users');

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

    assertDatabaseCount('trashes', 10);

    config(['trash.pruning_status' => true]);

    $result = artisan('model:prune', [
        '--model' => Trash::class,
    ]);

    expect($result === 0)->toBeTrue();

    assertDatabaseEmpty('trashes');

    assertDatabaseEmpty('users');
});

test('verify that models are not pruned if pruning status is disabled', function () {
    assertDatabaseEmpty('trashes');

    assertDatabaseEmpty('users');

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

    assertDatabaseCount('trashes', 10);

    $result = artisan('model:prune', [
        '--model' => Trash::class,
    ]);

    expect($result === 0)->toBeTrue();

    assertDatabaseCount('trashes', 10);

    assertDatabaseCount('users', 10);
});
