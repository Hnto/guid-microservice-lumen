<?php

namespace App\Core\User;

use App\Core\Traits\FillClassProperties;

class User
{

    use FillClassProperties;

    /**
     * Contains the id
     *
     * @var int
     */
    private $id;

    /**
     * Contains the email
     *
     * @var string
     */
    private $email;

    /**
     * Contains the name
     *
     * @var string
     */
    private $name;

    /**
     * Contains the api key
     *
     * @var string
     */
    private $api_key;

    /**
     * Contains the user model
     *
     * @var UserModel
     */
    private $userModel;

    /**
     * Get id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Get the user his/her email
     *
     * @return mixed
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Get the user his/her name
     *
     * @return mixed
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the api key
     *
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->api_key;
    }

    /**
     * Get the user model
     *
     * @return UserModel
     */
    public function getUserModel(): UserModel
    {
        return $this->userModel;
    }
}
