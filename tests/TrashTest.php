<?php

namespace Ajaycalicut17\LaravelTrash\Tests;

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
}
