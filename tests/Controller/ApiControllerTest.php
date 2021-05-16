<?php

namespace App\Tests\Controller;

use App\Helper\ApiResponses;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class ApiControllerTest extends TestCase
{
    public function testAddVisit(): void
    {
        $client = new Client(array(
            'http_errors' => false
        ));
        $types = ['product','static-page','category','checkout','homepage'];
        $pickedType = $types[array_rand($types)];
        $response = $client->request('POST', $_ENV['BASE_URL'].'api/visit', [
            'json' => [
                'customer' => 'testing-customer-identifier-123',
                'url' => 'https://www.example.com/'.$pickedType.'/'.random_int(1,100),
                'type' => $pickedType
            ]
        ]);
        self::assertEquals(201, $response->getStatusCode());
        $data = json_decode($response->getBody()->getContents(), true);
        self::assertArrayHasKey('message', $data);
        self::assertEquals(ApiResponses::RESOURCE_CREATED['MESSAGE'], $data['message']);
    }

    public function testListCustomerJourney(): void
    {
        $client = new Client(array(
            'http_errors' => false
        ));
        $customer = 'testing-customer-identifier-123';
        $response = $client->request('GET', $_ENV['BASE_URL'].'api/customer/'.$customer.'/journey');
        self::assertEquals(ApiResponses::REQUEST_OK['CODE'], $response->getStatusCode());
    }

    public function testListTypeHits(): void
    {
        $client = new Client(array(
            'http_errors' => false
        ));
        $response = $client->request('GET', $_ENV['BASE_URL'].'api/types/hits');
        self::assertEquals(ApiResponses::REQUEST_OK['CODE'], $response->getStatusCode());
    }

    public function testListLinksHits(): void
    {
        $client = new Client(array(
            'http_errors' => false
        ));
        $response = $client->request('GET', $_ENV['BASE_URL'].'api/links/hits');
        self::assertEquals(ApiResponses::REQUEST_OK['CODE'], $response->getStatusCode());
    }

    public function testListCustomersWithSimilarJourney() {
        $client = new Client(array(
            'http_errors' => false
        ));
        $customer = 'testing-customer-identifier-123';
        $response = $client->request('GET', $_ENV['BASE_URL'].'api/customers-journey/similar/'.$customer);
        self::assertEquals(ApiResponses::REQUEST_OK['CODE'], $response->getStatusCode());

        $client = new Client(array(
            'http_errors' => false
        ));
        $response = $client->request('GET', $_ENV['BASE_URL'].'api/customers-journey/similar/NON_EXISTING_CUSTOMER');
        self::assertEquals(ApiResponses::RESOURCE_NOT_FOUND['CODE'], $response->getStatusCode());
        $data = json_decode($response->getBody()->getContents(), true);
        self::assertArrayHasKey('message', $data);
        self::assertEquals(ApiResponses::RESOURCE_NOT_FOUND['MESSAGE'], $data['message']);
    }
}
