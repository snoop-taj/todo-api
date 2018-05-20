<?php
declare(strict_types=1);

namespace Etechnologia\Platform\Todo\Core\Todo;

use PHPUnit\Framework\TestCase;

class TodoTest extends TestCase
{
    /**
     * @test
     */
    public function itCreates()
    {
        $todo = TestTodoFactory::create();

        self::assertEquals(TestTodoFactory::ID, $todo->getId());
        self::assertEquals(TestTodoFactory::TITLE, $todo->getTitle());
        self::assertRegExp(TestTodoFactory::DATE_ATOM_REGEX, $todo->getCreated()->format(DATE_ATOM));
        self::assertNull($todo->getUpdated());
    }

    /**
     * @test
     */
    public function itUpdates()
    {
        $todo = TestTodoFactory::update();

        self::assertEquals(TestTodoFactory::ID, $todo->getId());
        self::assertEquals(TestTodoFactory::TITLE, $todo->getTitle());
        self::assertRegExp(TestTodoFactory::DATE_ATOM_REGEX, $todo->getCreated()->format(DATE_ATOM));
        self::assertRegExp(TestTodoFactory::DATE_ATOM_REGEX, $todo->getCreated()->format(DATE_ATOM));
    }
}
