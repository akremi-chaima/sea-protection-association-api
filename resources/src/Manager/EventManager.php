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

    /**
     * @param Event $event
     * @return void
     */
    public function save(Event $event) {
        $this->getEntityManager()->persist($event);
        $this->getEntityManager()->flush();
    }

    /**
     * @param Event $event
     * @return void
     */
    public function delete(Event $event) {
        $this->getEntityManager()->remove($event);
        $this->getEntityManager()->flush();
    }
}