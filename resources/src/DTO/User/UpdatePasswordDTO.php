<?php

namespace App\DTO\User;

/**
 * Class UpdatePasswordDTO
 */
class UpdatePasswordDTO
{
    /**
     * @inheritdoc
     */
    private $password;

    /**
     * @inheritdoc
     */
    private $newPassword;

    /**
     * @inheritdoc
     */
    private $newPasswordConfirmation;

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return UpdatePasswordDTO
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * @param mixed $newPassword
     * @return UpdatePasswordDTO
     */
    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNewPasswordConfirmation()
    {
        return $this->newPasswordConfirmation;
    }

    /**
     * @param mixed $newPasswordConfirmation
     * @return UpdatePasswordDTO
     */
    public function setNewPasswordConfirmation($newPasswordConfirmation)
    {
        $this->newPasswordConfirmation = $newPasswordConfirmation;
        return $this;
    }
}