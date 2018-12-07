<?php
/**
 * Created by PhpStorm.
 * User: herant
 * Date: 22-03-18
 * Time: 15:24
 */

namespace App\Core\Token;


interface TokenSpecs
{

    /**
     * Contains the token value length
     */
    const TOKEN_VALUE_LENGTH = 32;

    /**
     * Contains the amount of days
     * a token is valid
     */
    const TOKEN_EXPIRE_DATE_AFTER_DAYS = 1;
}
