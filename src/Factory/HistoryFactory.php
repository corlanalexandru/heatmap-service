<?php


namespace App\Factory;


use App\Entity\Customers;
use App\Entity\History;
use App\Entity\Types;

class HistoryFactory
{
    public function create(string $url, string $fullUrl, Types $type, Customers $customer, $parameters = null): History
    {
        return new History($url,$fullUrl,$type,$customer,$parameters);
    }

}