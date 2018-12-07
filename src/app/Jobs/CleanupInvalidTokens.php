<?php
/**
 * Created by PhpStorm.
 * User: herant
 * Date: 22-03-18
 * Time: 16:22
 */

namespace App\Jobs;


use App\Core\Token\TokenRepository;

class CleanupInvalidTokens extends Job
{

    /**
     * Contains the token repository
     *
     * @var TokenRepository
     */
    private $tokenRepository;

    /**
     * CleanupInvalidTokens constructor.
     *
     * @param TokenRepository $tokenRepository
     */
    public function __construct(TokenRepository $tokenRepository)
    {
        $this->tokenRepository = $tokenRepository;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tokens = $this->tokenRepository->findExpiredTokes(1);

        foreach ($tokens as $token) {
            $this->tokenRepository
                ->deleteById($token->getId());
        }
    }
}
