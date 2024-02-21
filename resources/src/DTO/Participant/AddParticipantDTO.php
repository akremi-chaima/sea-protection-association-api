<?php

namespace App\DTO\Participant;

class AddParticipantDTO
{
    /**
     * @inheritdoc
     */
    protected $firstName;

    /**
     * @inheritdoc
     */
    protected $lastName;

    /**
     * @inheritdoc
     */
    protected $email;

    /**
     * @inheritdoc
     */
    protected $phoneNumber;

    /**
     * @inheritdoc
     */
    protected $eventId;

    /**
     * @inheritdoc
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @inheritdoc
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @inheritdoc
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @inheritdoc
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @inheritdoc
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * @inheritdoc
     */
    public function setEventId($eventId)
    {
        $this->eventId = $eventId;
        return $this;
    }
}