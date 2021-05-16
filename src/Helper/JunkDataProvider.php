<?php


namespace App\Helper;

use App\Factory\HistoryFactory;
use App\Factory\TypeFactory;
use App\Preparator\HistoryPreparator;
use App\Repository\CustomersRepository;
use App\Repository\HistoryRepository;
use App\Repository\TypesRepository;

class JunkDataProvider
{
    private $typeFactory;
    private $typesRepository;
    private $customersRepository;
    private $historyFactory;
    private $historyRepository;
    private $historyPreparator;

    public function __construct
    (
        TypeFactory $typeFactory,
        TypesRepository $typesRepository,
        CustomersRepository $customersRepository,
        HistoryFactory $historyFactory,
        HistoryRepository $historyRepository,
        HistoryPreparator $historyPreparator
    )
    {
        $this->typeFactory = $typeFactory;
        $this->typesRepository = $typesRepository;
        $this->customersRepository = $customersRepository;
        $this->historyFactory = $historyFactory;
        $this->historyRepository = $historyRepository;
        $this->historyPreparator = $historyPreparator;
    }

    public function provideData(): void
    {
        $types = [
            ['slug' => 'product','name' => 'Product'],
            ['slug' => 'category','name' => 'Category'],
            ['slug' => 'static-page','name' => 'Static page'],
            ['slug' => 'checkout','name' => 'Checkout'],
            ['slug' => 'homepage','name' => 'Homepage']
        ];

        foreach ($types as $type) {
            if($this->typesRepository->findOneBy(['slug' => $type['slug']]) === null){
                $type = $this->typeFactory->create($type['slug'], $type['name']);
                $this->typesRepository->save($type);
            }
        }

        for($i = 0; $i <= 10; $i++){
            $types = ['product','static-page','category','checkout','homepage'];
            $pickedType = $types[array_rand($types)];
            $payload = [
                'customer' => uniqid('customer',true),
                'url' => 'https://www.example.com/'.$pickedType.'/'.random_int(1,100),
                'type' => $pickedType
            ];
            $data = $this->historyPreparator->prepare($payload);
            $this->customersRepository->save($data['customer']);
            $history = $this->historyFactory->create($data['url'], $data['fullUrl'], $data['type'], $data['customer'], $data['parameters']);
            $this->historyRepository->save($history);
        }

        // Insert testing customer
        $payload = [
            'customer' => 'testing-customer-identifier-123',
            'url' => 'https://www.example.com/product/'.random_int(1,100),
            'type' => 'product'
        ];
        $data = $this->historyPreparator->prepare($payload);
        $this->customersRepository->save($data['customer']);
        $history = $this->historyFactory->create($data['url'], $data['fullUrl'], $data['type'], $data['customer'], $data['parameters']);
        $this->historyRepository->save($history);
    }
}