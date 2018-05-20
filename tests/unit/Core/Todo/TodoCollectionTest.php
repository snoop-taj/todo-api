<?php
declare(strict_types=1);

namespace Etechnologia\Platform\Todo\Core\Todo;

use PHPUnit\Framework\TestCase;

class TodoCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function itCreates()
    {
        $todoCollection = TestTodoFactory::createCollection(
            [
                TestTodoFactory::create('12345'),
                TestTodoFactory::create('67890'),
                TestTodoFactory::create('12093')
            ]
        );

        self::assertInstanceOf(TodoCollection::class, $todoCollection);
        self::assertCount(3, $todoCollection);
        $todoCollection->rewind();
        self::assertEquals(0, $todoCollection->key());
        while($todoCollection->valid()) {
            self::assertInstanceOf(Todo::class, $todoCollection->current());
            $todoCollection->next();
        }
    }

    /**
     * @test
     */
    public function itCreatesFrom()
    {
        $todoCollection = TodoCollection::createFrom(
            [
                TestTodoFactory::create('12345'),
                TestTodoFactory::create('67890'),
                TestTodoFactory::create('12093')
            ]
        );

        self::assertCount(3, $todoCollection);
    }
}
