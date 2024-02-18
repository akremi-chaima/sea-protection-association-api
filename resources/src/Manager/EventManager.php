<?php

namespace App\Manager;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;

class EventManager extends AbstractManager
{
    /**
     * @param EntityManagerInterface $managerInterface
     */
    public function __construct(
        EntityManagerInterface $managerInterface
    )
    {
        parent::__construct($managerInterface, Event::class);
    }
}