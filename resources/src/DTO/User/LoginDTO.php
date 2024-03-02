<?php

namespace App\DTO\User;

/**
 * Class LoginDTO
 */
class LoginDTO
{
    /**
     * @inheritdoc
     */
    private $email;

    /**
     * @inheritdoc
     */
    private $password;

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
}