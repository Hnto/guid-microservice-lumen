<?php

namespace App\Core\User;

class UserRepository
{

    /**
     * Authenticate by api key
     * and return user object
     * if successfully authenticated
     *
     * @param string $apiKey contains the api key
     *
     * @return User|null
     */
    public function authenticate(string $apiKey)
    {
        /** @var UserModel $userModel */
        $userModel = app(UserModel::class)->findBy('api_key', $apiKey);

        if (empty($userModel)) {
            return null;
        }

        $user = new User();
        $user->fill(array_merge(['userModel' => $userModel], $userModel->toArray()));

        return $user;
    }
}
