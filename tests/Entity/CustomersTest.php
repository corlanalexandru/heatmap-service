<?php


namespace App\Tests\Entity;


use App\Entity\Customers;
use PHPUnit\Framework\TestCase;

class CustomersTest extends TestCase
{

    public function testReturnBasicCustomer(): void
    {
        $uid = uniqid('testing-uid',true);
        $customer = new Customers($uid);
        self::assertEquals($uid, $customer->getUid());
    }

}