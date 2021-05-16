<?php


namespace App\Preparator;


use App\Entity\Customers;
use App\Entity\Types;
use App\Factory\CustomerFactory;
use Doctrine\ORM\EntityManagerInterface;

class HistoryPreparator
{
    private $entityManager;
    private $customerFactory;

    public function __construct(EntityManagerInterface $entityManager, CustomerFactory $customerFactory){
        $this->entityManager = $entityManager;
        $this->customerFactory = $customerFactory;
    }


    public function prepare(array $payload) {
        $data['fullUrl'] = $payload['url'] ?? '';
        $parts = explode('?',$data['fullUrl']);
        $data['url'] = $parts[0] ?? '';
        $data['parameters'] = $parts[1] ?? null;
        if(isset($payload['customer']) && $payload['customer'] !== '') {
            $existingClient = $this->entityManager->getRepository(Customers::class)->findOneBy(['uid'=>$payload['customer']]);
            if($existingClient !== null) {
                $data['customer'] = $existingClient;
            }
            else {
                $data['customer'] = $this->customerFactory->create($payload['customer']);
            }
        }
        $data['type'] = (isset($payload['type']) && $payload['type'] !== '') ? $this->entityManager->getRepository(Types::class)->findOneBy(['slug'=>$payload['type']]) : null;
        return $data;
    }

}