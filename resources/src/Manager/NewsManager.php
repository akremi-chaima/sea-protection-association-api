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

    /**
     * @param News $news
     * @return void
     */
    public function save(News $news) {
        $this->getEntityManager()->persist($news);
        $this->getEntityManager()->flush();
    }

    /**
     * @param News $news
     * @return void
     */
    public function delete(News $news) {
        $this->getEntityManager()->remove($news);
        $this->getEntityManager()->flush();
    }
}