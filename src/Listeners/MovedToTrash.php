<?php

namespace Ajaycalicut17\LaravelTrash\Listeners;

use Ajaycalicut17\LaravelTrash\Events\MoveToTrash;

class MovedToTrash
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MoveToTrash $moveToTrash): void
    {
        $moveToTrash->model->trash()->create([
            'name' => $moveToTrash->model::trashName($moveToTrash->model),
        ]);
    }
}
