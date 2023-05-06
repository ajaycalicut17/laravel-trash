<?php

namespace Ajaycalicut17\LaravelTrash\Listeners;

use Ajaycalicut17\LaravelTrash\Events\RestoreModel;

class Restore
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
    public function handle(RestoreModel $restoreModel): void
    {
        $restoreModel->model->trashable()->restore();
    }
}
