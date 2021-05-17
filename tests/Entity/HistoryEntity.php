<?php


namespace App\Tests\Entity;


use App\Entity\Customers;
use App\Entity\History;
use App\Entity\Types;
use App\Preparator\HistoryPreparator;
use PHPUnit\Framework\TestCase;

class HistoryEntity extends TestCase
{

    public function testReturnsBasicHistory(): void
    {

        $uid = uniqid('test-customer',true);
        $slug = 'test-type';
        $customer = new Customers($uid);
        $type = new Types($slug, 'Test type');
        $url = 'https://www.example.com/product/'.random_int(1,100);
        $parameters = 'getParameter=1';
        $fullUrl = $url.'?'.$parameters;
        $data['fullUrl'] = $fullUrl;
        $data['url'] = $url;
        $data['parameters'] = $parameters;
        $data['type'] = $type;
        $data['customer'] = $customer;
        $history = new History($data['url'], $data['fullUrl'], $data['type'], $data['customer'], $data['parameters']);

        self::assertEquals($data['url'], $history->getUrl());
        self::assertEquals($data['fullUrl'], $history->getFullUrl());
        self::assertEquals($data['parameters'], $history->getParameters());
        self::assertInstanceOf(Types::class, $history->getType());
        self::assertInstanceOf(Customers::class, $history->getCustomer());
        self::assertEquals($slug, $history->getType()->getSlug());
        self::assertEquals($uid, $history->getCustomer()->getUid());
    }

}