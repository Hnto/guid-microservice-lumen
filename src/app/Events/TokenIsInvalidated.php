<?php

namespace App\Events;

use App\Core\Token\Token;

class TokenIsInvalidated extends Event
{

    protected $token;

    /**
     * Create a new event instance.
     */
    public function __construct(Token $token)
    {
        $this->params['token'] = $token;
    }

    /**
     * Get parameters
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
