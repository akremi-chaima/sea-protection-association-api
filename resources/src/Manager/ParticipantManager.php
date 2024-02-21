<?php

namespace App\Manager;

use App\Entity\Participant;
use Doctrine\ORM\EntityManagerInterface;

class ParticipantManager extends AbstractManager
{
    /**
     * @param EntityManagerInterface $managerInterface
     */
    public function __construct(
        EntityManagerInterface $managerInterface
    )
    {
        parent::__construct($managerInterface, Participant::class);
    }

    /**
     * @param Participant $participant
     * @return void
     */
    public function save(Participant $participant) {
        $this->getEntityManager()->persist($participant);
        $this->getEntityManager()->flush();
    }
}