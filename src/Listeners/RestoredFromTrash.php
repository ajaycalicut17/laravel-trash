<?php

namespace Ajaycalicut17\LaravelTrash\Listeners;

use Ajaycalicut17\LaravelTrash\Events\RestoreFromTrash;

class RestoredFromTrash
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
    public function handle(RestoreFromTrash $event): void
    {
        $event->model->trashable()->restore();
    }
}
