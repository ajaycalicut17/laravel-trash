<?php

namespace Ajaycalicut17\LaravelTrash\Listeners;

use Ajaycalicut17\LaravelTrash\Events\ModelTrashed;

class Trashed
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ModelTrashed  $event
     * @return void
     */
    public function handle(ModelTrashed $modelTrashed): void
    {
        $modelTrashed->model->trash()->create([
            'name' => $modelTrashed->model::trashName($modelTrashed->model),
        ]);
    }
}
