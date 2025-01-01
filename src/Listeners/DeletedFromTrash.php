<?php

namespace Ajaycalicut17\LaravelTrash\Listeners;

use Ajaycalicut17\LaravelTrash\Events\DeleteFromTrash;

class DeletedFromTrash
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
        $r = 0;
    }

    public function handle(DeleteFromTrash $event): void
    {
        $event->model->trashable()->forceDelete();
    }
}
