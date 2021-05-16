<?php

namespace App\Repository;

use App\Entity\History;
use App\Entity\Types;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Types|null find($id, $lockMode = null, $lockVersion = null)
 * @method Types|null findOneBy(array $criteria, array $orderBy = null)
 * @method Types[]    findAll()
 * @method Types[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Types::class);
    }

    public function findTypeHits(string $from, string $until)
    {
        $qb = $this->createQueryBuilder('t');
        $fromCondition = null;
        $untilCondition = null;
        if((bool)strtotime($from)) {
            $fromCondition = $qb->expr()->gte('h.createdAt', "'".(new \DateTime($from))->setTime(00,00,00)->format('Y-m-d H:i:s')."'");
        }
        if((bool)strtotime($until)) {
            $untilCondition = $qb->expr()->lte('h.createdAt', "'".(new \DateTime($until))->setTime(23,59,59)->format('Y-m-d H:i:s')."'");
        }
        $qb
            ->select('count(h.type) as hits, t.name')
            ->leftJoin(History::class, 'h','WITH',$qb->expr()->andX(
                $qb->expr()->eq('t.id' ,'h.type'),
                $fromCondition,
                $untilCondition
            ));
        return
            $qb
                ->orderBy('hits', 'DESC')
                ->groupBy('t.id')
                ->getQuery()
                ->getResult()
            ;
    }
    public function save(Types $type){
        $this->_em->persist($type);
        $this->_em->flush();
    }
}
