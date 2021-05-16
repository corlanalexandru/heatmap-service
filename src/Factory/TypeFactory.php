<?php


namespace App\Factory;


use App\Entity\Types;

class TypeFactory
{
    public function create(string $slug, string $name): Types
    {
        return new Types($slug, $name);
    }
}