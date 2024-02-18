<?php
namespace App\Manager;

use Doctrine\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class AbstractManager
 */
abstract class AbstractManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /** @var ObjectRepository $repository */
    protected $repository;

    /**
     * AbstractManager constructor.
     * @param EntityManagerInterface $entityManager
     * @param string $className
     */
    public function __construct(EntityManagerInterface $entityManager, string $className)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository($className);
    }

    /**
     * @return EntityManagerInterface
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return ObjectRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param array $params
     * @param array $orderBy
     * @param int|null $limit
     * @return array|object[]|string[]
     */
    public function findBy(array $params, array $orderBy = [], int $limit = null)
    {
        return $this->repository->findBy($params, $orderBy, $limit);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function findOneBy(array $params)
    {
        return $this->repository->findOneBy($params);
    }

    /**
     * @param int $id
     * @return object|null
     */
    public function find(int $id)
    {
        return $this->repository->find($id);
    }

    /**
     * @return object[]
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }
}
