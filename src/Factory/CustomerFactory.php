<?php


namespace App\Factory;


use App\Entity\Customers;

class CustomerFactory
{
    public function create(string $uid): Customers
    {
        return new Customers($uid);
    }
}