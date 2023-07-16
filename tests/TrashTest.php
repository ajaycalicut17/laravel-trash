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
}
