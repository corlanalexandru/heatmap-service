<?php

namespace App\Repository;

use App\Entity\Customers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Customers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customers[]    findAll()
 * @method Customers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customers::class);
    }


    public function findCustomersWithSimilarJourney(Customers $customer,$limit): array
    {
        $connection = $this->_em->getConnection();
        $statement = $connection->prepare(
            'SELECT customers.id as customerId, customers.uid as customerUid, GROUP_CONCAT(history.full_url) as userJourney,
                (SELECT GROUP_CONCAT(history.full_url) From history WHERE history.customer_id='.$customer->getId().' ORDER BY created_at asc) as searchJourney
                FROM customers
                INNER JOIN history ON (customers.id=history.customer_id)
                GROUP by customerId
                HAVING LOCATE(searchJourney, userJourney) > 0 and customerId != '.$customer->getId().' LIMIT '.$limit
        );
        $statement->executeQuery();
        return $statement->fetchAll();
    }

    public function save(Customers $customer){
        $this->_em->persist($customer);
        $this->_em->flush();
    }
}
