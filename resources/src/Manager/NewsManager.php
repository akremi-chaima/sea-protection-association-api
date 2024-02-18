<?php

namespace App\Manager;

use App\Entity\News;
use Doctrine\ORM\EntityManagerInterface;

class NewsManager extends AbstractManager
{
    /**
     * @param EntityManagerInterface $managerInterface
     */
    public function __construct(
        EntityManagerInterface $managerInterface
    )
    {
        parent::__construct($managerInterface, News::class);
    }
}