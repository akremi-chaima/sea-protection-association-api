<?php

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserManager extends AbstractManager
{
    /**
     * @param EntityManagerInterface $managerInterface
     */
    public function __construct(
        EntityManagerInterface $managerInterface
    )
    {
        parent::__construct($managerInterface, User::class);
    }

    /**
     * @param User $user
     * @return void
     */
    public function save(User $user) {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * @param User $user
     * @return void
     */
    public function delete(User $user) {
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
    }
}