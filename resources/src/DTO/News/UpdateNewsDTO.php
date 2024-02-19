<?php

namespace App\DTO\News;

class UpdateNewsDTO extends CreateNewsDTO
{
    /**
     * @inheritdoc
     */
    protected $id;

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