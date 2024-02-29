<?php

namespace App\Manager;

use App\Entity\Event;
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

    /**
     * @param Event $event
     * @return int
     */
    public function count(Event $event) {
        try {
            $queryBuilder =  $this->getEntityManager()->createQueryBuilder()
                ->select('count(participant.id)')
                ->from(Participant::class, 'participant')
                ->join(Event::class, 'event', 'WITH', 'event = participant.event')
                ->andWhere('event = :event')
                ->setParameter(':event', $event);

            return intval($queryBuilder->getQuery()->getSingleScalarResult());
        } catch (\Exception $exception) {
            return 0;
        }
    }
}