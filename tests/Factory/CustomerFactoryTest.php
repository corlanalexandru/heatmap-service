<?php


namespace App\Tests\Factory;


use App\Factory\CustomerFactory;
use PHPUnit\Framework\TestCase;

class CustomerFactoryTest extends TestCase
{
    public function testFactoryReturnsCustomer(): void
    {
        $uid = uniqid('testing-uid',true);
        $customerFactory = new CustomerFactory();
        $customer = $customerFactory->create($uid);
        self::assertEquals($uid, $customer->getUid());
    }

}