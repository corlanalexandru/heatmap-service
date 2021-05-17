<?php


namespace App\Tests\Factory;


use App\Factory\TypeFactory;
use PHPUnit\Framework\TestCase;

class TypeFactoryTest extends TestCase
{

    public function testFactoryReturnsCustomer(): void
    {
        $slug = 'test-type';
        $name = 'Test type';
        $typeFactory = new TypeFactory();
        $type = $typeFactory->create($slug, $name);
        self::assertEquals($slug, $type->getSlug());
        self::assertEquals($name, $type->getName());
    }

}