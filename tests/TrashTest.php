<?php

namespace Ajaycalicut17\LaravelTrash\Tests;

use Ajaycalicut17\LaravelTrash\Models\Trash;
use Ajaycalicut17\LaravelTrash\Tests\Models\User;

class TrashTest extends TestCase
{
    public function test_verify_that_the_deleted_model_is_trashed(): void
    {
        $user = User::factory()->create();

        $user->delete();

        $this->assertSoftDeleted($user);

        $this->assertDatabaseHas('trashes', [
            'trashable_type' => User::class,
            'trashable_id' => $user->id,
            'name' => User::trashName($user),
        ]);
    }

    public function test_check_if_the_corresponding_model_has_been_restored_from_the_trash()
    {
        $user = User::factory()->create();

        $user->delete();

        $trash = Trash::query()
            ->where('trashable_type', User::class)
            ->where('trashable_id', $user->id)
            ->first();

        $trash->restoreFromTrash();

        $this->assertModelMissing($trash);

        $this->assertNotSoftDeleted($user);
    }

    public function test_check_if_the_model_has_been_deleted_from_the_trash()
    {
        $user = User::factory()->create();

        $user->delete();

        $trash = Trash::query()
            ->where('trashable_type', User::class)
            ->where('trashable_id', $user->id)
            ->first();

        $trash->deleteFromTrash();

        $this->assertModelMissing($trash);

        $this->assertModelMissing($user);
    }

    public function test_verify_that_all_models_have_been_deleted_from_the_trash()
    {
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
    }

    public function test_check_if_the_models_are_pruned()
    {
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

        $this->assertTrue($result === 0);

        $this->assertDatabaseEmpty('trashes');

        $this->assertDatabaseEmpty('users');
    }

    public function test_verify_that_models_are_not_pruned_if_pruning_status_is_disabled()
    {
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

        $this->assertTrue($result === 0);

        $this->assertDatabaseCount('trashes', 10);

        $this->assertDatabaseCount('users', 10);
    }
}
