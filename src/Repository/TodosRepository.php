<?php

namespace App\Repository;

use App\Entity\Todos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Todos|null find($id, $lockMode = null, $lockVersion = null)
 * @method Todos|null findOneBy(array $criteria, array $orderBy = null)
 * @method Todos[]    findAll()
 * @method Todos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TodosRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Todos::class);
    }

//    /**
//     * @return Todos[] Returns an array of Todos objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Todos
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findUncompleted($creator)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.creator = :creator')
            ->setParameter('creator', $creator)
            ->andWhere('t.state = 0')
            ->orderBy('t.id_todo', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function myfindAll($creator)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.creator = :creator')
            ->setParameter('creator', $creator)
            //->andWhere('t.state = 0')
            ->orderBy('t.id_todo', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    public function myfindAllbyState($creator, $state)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.creator = :creator')
            ->setParameter('creator', $creator)
            ->andWhere('t.state = :state')
            ->setParameter('state', $state)
            ->orderBy('t.id_todo', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    public function findOneByIdTodo($id_todo, $creator): ?Todos
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.id_todo = :id_todo')
            ->setParameter('id_todo', $id_todo)
            ->andWhere('t.creator = :creator')
            ->setParameter('creator', $creator)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    public function countTodos($creator)
    {
        return $this->createQueryBuilder('t')
            ->select('count(t)')
            ->andWhere('t.creator = :creator')
            ->setParameter('creator', $creator)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
