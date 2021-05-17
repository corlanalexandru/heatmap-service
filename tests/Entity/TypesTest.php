<?php


namespace App\Tests\Entity;


use App\Entity\Types;
use PHPUnit\Framework\TestCase;

class TypesTest extends TestCase
{
    public function testReturnsBasicType(): void
    {
        $slug = 'testing-type-slug';
        $name = 'Testing type';
        $type = new Types($slug,$name);
        self::assertEquals($name, $type->getName());
        self::assertEquals($slug, $type->getSlug());
    }

}