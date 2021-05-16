<?php

namespace App\Repository;

use App\Entity\Customers;
use App\Entity\History;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method History|null find($id, $lockMode = null, $lockVersion = null)
 * @method History|null findOneBy(array $criteria, array $orderBy = null)
 * @method History[]    findAll()
 * @method History[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, History::class);
    }


    public function findHistoryByCustomer(Customers $customer, string $from, string $until, $limit)
    {
        $qb =  $this->createQueryBuilder('h');

        $qb
            ->andWhere('h.customer = :val')
            ->setParameter('val', $customer->getId());

        if((bool)strtotime($from)) {
            $qb
                ->andWhere('h.createdAt >= :from')
                ->setParameter('from', (new \DateTime($from))->setTime(00,00,00)->format('Y-m-d H:i:s'));
        }
        if((bool)strtotime($from)) {
            $qb
                ->andWhere('h.createdAt <= :until')
                ->setParameter('until', (new \DateTime($until))->setTime(23,59,59)->format('Y-m-d H:i:s'));
        }

        return
            $qb
                ->orderBy('h.createdAt', 'ASC')
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult()
        ;
    }


    public function findLinkHits(string $from, string $until, $exact = true)
    {
        $qb = $this->createQueryBuilder('h');
        if($exact) {
            $qb
                ->select('count(h.fullUrl) as hits, h.fullUrl as link');
        }
        else {
            $qb
                ->select('count(h.url) as hits, h.url as link');
        }

        if((bool)strtotime($from)) {
            $qb
                ->andWhere('h.createdAt >= :from')
                ->setParameter('from', (new \DateTime($from))->setTime(00,00,00)->format('Y-m-d H:i:s'));
        }
        if((bool)strtotime($until)) {
            $qb
                ->andWhere('h.createdAt <= :until')
                ->setParameter('until', (new \DateTime($until))->setTime(23,59,59)->format('Y-m-d H:i:s'));
        }
        return
            $qb
                ->orderBy('hits', 'DESC')
                ->groupBy('link')
                ->getQuery()
                ->getResult()
            ;
    }


    public function save(History $history){
        $this->_em->persist($history);
        $this->_em->flush();
    }
}
