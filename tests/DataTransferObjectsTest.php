<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Tests\Stubs\ExampleData;
use Zurbaev\DataTransferObjects\Exceptions\DtoMethodNotFoundException;

class DataTransferObjectsTest extends TestCase
{
    public function testVirtualSetters()
    {
        $dto = new ExampleData();

        $this->assertNull($dto->first);
        $this->assertNull($dto->second);

        $dto->setFirst('Hello');
        $this->assertSame('Hello', $dto->first);
    }

    public function testVirtualSettersChain()
    {
        $dto = new ExampleData();

        $dto->setFirst('Hello')->setSecond(123);

        $this->assertSame('Hello', $dto->getFirst());
        $this->assertSame(123, $dto->getSecond());
    }

    public function testVirtualGetters()
    {
        $dto = new ExampleData('Hello', 123);

        $this->assertSame('Hello', $dto->getFirst());
        $this->assertSame(123, $dto->getSecond());
    }

    public function testMissingMethodException()
    {
        $dto = new ExampleData('Hello', 123);

        $this->expectException(DtoMethodNotFoundException::class);
        $dto->helloWorld();
    }

    public function testCreateFromArray()
    {
        $data = [
            'first' => 'Hello',
            'second' => 123,
        ];

        $dto = ExampleData::fromArray($data);

        $this->assertInstanceOf(ExampleData::class, $dto);
        $this->assertSame($data['first'], $dto->first);
        $this->assertSame($data['second'], $dto->second);
    }
}
