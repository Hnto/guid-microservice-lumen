<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

abstract class Event
{
    use SerializesModels;

    protected $params;

    /**
     * Get parameters
     *
     * @return array
     */
    abstract public function getParams(): array;
}
