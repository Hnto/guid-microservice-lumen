<?php

namespace App\Core\Token;

class TokenRepository
{

    /**
     * Create a new token
     * and save as new record
     *
     * @return Token
     */
    public function createToken()
    {
        $tokenModel = new TokenModel();

        $tokenModel->value = TokenGenerator::generateTokenValue();
        $tokenModel->ends_at = TokenGenerator::generateTokenExpire();

        $tokenModel->save();

        $token = new Token();
        $token->fill([
            'id' => $tokenModel->id,
            'value' => $tokenModel->value,
            'ends_at' => $tokenModel->ends_at
        ]);

        return $token;
    }

    /**
     * Find token by its value
     *
     * @param string $tokenValue contains the token value
     *
     * @return Token
     */
    public function findTokenByValue(string $tokenValue)
    {
        $tokenModel = TokenModel::where('value', $tokenValue)
            ->first();

        $token = new Token();

        $token->fill([]);
        $token->modify();

        //Check if token model is available
        if (!empty($tokenModel) && $tokenModel instanceof TokenModel) {
            $token->fill($tokenModel->toArray());
        }

        return $token;
    }

    /**
     * Retrieve token records
     * older than the given days
     *
     * @param int $days
     *
     * @return Token[]
     */
    public function findExpiredTokes(int $days)
    {
        $tokenData = TokenModel::olderThan($days)->get()->toArray();

        $tokens = [];
        foreach ($tokenData as $key => $tokenInfo) {
            $token = new Token();
            $token->fill($tokenInfo);

            $tokens[] = $token;
        }

        return $tokens;
    }

    /**
     * Delete a token record by its id
     *
     * @param int $id
     */
    public function deleteById(int $id)
    {
        $tokenModel = TokenModel::find($id);
        $tokenModel->delete();
    }
}
