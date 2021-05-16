<?php
//
//
//namespace App\Tests\Repository;
//
//use App\Entity\Customers;
//use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
//
//class CustomerRepositoryTest extends KernelTestCase
//{
//    private $entityManager;
//
//    protected function setUp(): void
//    {
//        $kernel = self::bootKernel();
//
//        $this->entityManager = $kernel->getContainer()
//            ->get('doctrine')
//            ->getManager();
//    }
//
//    public function testSearchByUid(): void
//    {
//        $customer = $this->entityManager
//            ->getRepository(Customers::class)
//            ->findOneBy(['uid' => 'testing-customer-identifier-123'])
//        ;
//
//        self::assertSame('testing-customer-identifier-123', $customer->getUid());
//    }
//
//    protected function tearDown(): void
//    {
//        parent::tearDown();
//
//        // doing this is recommended to avoid memory leaks
//        $this->entityManager->close();
//        $this->entityManager = null;
//    }
//
//}