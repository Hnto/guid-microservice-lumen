<?php

namespace App\Listeners;

use App\Core\Token\Token;
use App\Core\Token\TokenRepository;
use App\Events\Event;

class DeleteTokenListener
{
    /**
     * @param Event $event
     */
    public function handle(Event $event)
    {
        $params = $event->getParams();

        if (!isset($params['token']) && !$params['token'] instanceof Token) {
            return;
        }

        /** @var Token $token */
        $token = $params['token'];

        //If no id, continue
        if (empty($token->getId())) {
            return;
        }

        $tokenRepository = app(TokenRepository::class);

        //Delete the token
        $tokenRepository->deleteById(
            $token->getId()
        );
    }
}
