<?php

namespace App\DTO\Event;

class UpdateEventDTO extends CreateEventDTO
{
    /**
     * @inheritdoc
     */
    private $id;

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
}