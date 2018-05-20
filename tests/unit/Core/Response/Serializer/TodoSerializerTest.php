<?php
declare(strict_types=1);

namespace Etechnologia\Platform\Todo\Core\Response\Serializer;

use DateTime;
use DateTimeImmutable;
use Etechnologia\Platform\Todo\Core\Todo\TestTodoFactory;
use PHPUnit\Framework\TestCase;

class TodoSerializerTest extends TestCase
{
    /**
     * @test
     */
    public function itSerialize()
    {
        $expectedCreate = [
            'id' => TestTodoFactory::ID,
            'title' => TestTodoFactory::TITLE,
            'created' => (new DateTimeImmutable(TestTodoFactory::CREATED))->format(DateTime::ATOM),
            'updated' => null
        ];

        $expectedUpdate = [
            'id' => TestTodoFactory::ID,
            'title' => TestTodoFactory::TITLE,
            'created' => (new DateTimeImmutable(TestTodoFactory::CREATED))->format(DateTime::ATOM),
            'updated' => (new DateTimeImmutable(TestTodoFactory::UPDATED))->format(DateTime::ATOM)
        ];

        self::assertEquals($expectedCreate, (new TodoSerializer())->toArray(TestTodoFactory::create()));
        self::assertEquals($expectedUpdate, (new TodoSerializer())->toArray(TestTodoFactory::update()));
    }

    /**
     * @test
     */
    public function itSerializeCollection()
    {
        $expected = [
            [
                'id' => TestTodoFactory::ID,
                'title' => TestTodoFactory::TITLE,
                'created' => (new DateTimeImmutable(TestTodoFactory::CREATED))->format(DateTime::ATOM),
                'updated' => null
            ]
        ];

        self::assertEquals(
            $expected,
            (new TodoSerializer())->collectionToArray(
                TestTodoFactory::createCollection([TestTodoFactory::create()])
            )
        );
    }

}
