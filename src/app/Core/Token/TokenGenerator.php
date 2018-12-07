<?php
/**
 * Created by PhpStorm.
 * User: herant
 * Date: 22-03-18
 * Time: 14:27
 */

namespace App\Core\Token;


use Carbon\Carbon;

class TokenGenerator
{

    /**
     * Generate a random token string
     *
     * @return string
     */
    public static function generateTokenValue()
    {
        $token = bin2hex(random_bytes(TokenSpecs::TOKEN_VALUE_LENGTH));

        return $token;
    }

    /**
     * Generate the expire date time
     * for a token
     *
     * @return string
     */
    public static function generateTokenExpire()
    {
        return Carbon::create()
            ->addDays(TokenSpecs::TOKEN_EXPIRE_DATE_AFTER_DAYS)
            ->toDateTimeString();
    }
}