<?php


namespace App\Tests\Validator;


use App\Entity\Customers;
use App\Entity\Types;
use App\Validator\HistoryValidator;
use PHPUnit\Framework\TestCase;

class HistoryValidatorTest extends TestCase
{
    public function testHistoryValidatorSuccess(): void
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
        $historyValidator = new HistoryValidator();
        $validatorResponse = $historyValidator->validate($data);
        self::assertSameSize([],$validatorResponse);
    }


    public function testHistoryValidatorEmptyBody(): void
    {
        $data['fullUrl'] = '';
        $data['type'] = '';
        $data['customer'] = '';
        $historyValidator = new HistoryValidator();
        $validatorResponse = $historyValidator->validate($data);
        self::assertCount(5,$validatorResponse);
        self::assertContains('The key url must be specified and not empty!', $validatorResponse);
        self::assertContains('The key customer must be specified and not empty!', $validatorResponse);
        self::assertContains('The key type must be specified and not empty!', $validatorResponse);
        self::assertContains('The specified type does not exist!', $validatorResponse);
        self::assertContains("The specified customer can't be used!", $validatorResponse);
    }

    public function testHistoryValidatorInvalidType(): void
    {
        $data['url'] = 'https://www.example.com/product';
        $data['type'] = 'somes-string-passed-to-validator';
        $data['customer'] = new Customers(uniqid('test-customer',true));
        $historyValidator = new HistoryValidator();
        $validatorResponse = $historyValidator->validate($data);
        self::assertCount(1,$validatorResponse);
        self::assertContains('The specified type does not exist!', $validatorResponse);
    }

    public function testHistoryValidatorInvalidCustomer(): void
    {
        $data['url'] = 'https://www.example.com/product';
        $data['type'] = new Types('test-type', 'Test type');
        $data['customer'] = 'somes-string-passed-to-validator';
        $historyValidator = new HistoryValidator();
        $validatorResponse = $historyValidator->validate($data);
        self::assertCount(1,$validatorResponse);
        self::assertContains("The specified customer can't be used!", $validatorResponse);
    }

}