<?php
declare(strict_types=1);

namespace Etechnologia\Platform\Todo\Core\Todo;

use DateTimeImmutable;
use Etechnologia\Platform\Todo\Core\Todo\Exception\TodoEmptyPayloadException;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

class TodoServiceTest extends TestCase
{
    /** @var TodoRepositoryInterface|ObjectProphecy */
    private $todoRepository;

    /** @var TodoService */
    private $todoService;

    public function setUp()
    {
        $this->todoRepository = $this->prophesize(TodoRepositoryInterface::class);
        $this->todoService = new TodoService($this->todoRepository->reveal());
    }

    /**
     * @test
     */
    public function itCreate()
    {
        $this->todoRepository->getByName(Argument::type('string'))->shouldBeCalled()->willReturn(null);
        $this->todoRepository->create(Argument::type(Todo::class))->shouldBeCalled();

        $created = $this->todoService->create(
            TestTodoFactory::ID,
            ['title' => TestTodoFactory::TITLE],
            new DateTimeImmutable()
        );

        self::assertEquals(TestTodoFactory::ID, $created->getId());
        self::assertEquals(TestTodoFactory::TITLE, $created->getTitle());
        self::assertRegExp(TestTodoFactory::DATE_ATOM_REGEX, $created->getCreated()->format(DATE_ATOM));
        self::assertNull($created->getUpdated());
    }

    /**
     * @test
     */
    public function itUpdate()
    {
        $this->todoRepository->getById(Argument::type('string'))->shouldBeCalled()->willReturn(TestTodoFactory::create());
        $this->todoRepository->update(Argument::type(Todo::class))->shouldBeCalled();

        $updated = $this->todoService->update(TestTodoFactory::ID, ['title' => 'updated title'], new DateTimeImmutable());

        self::assertEquals(TestTodoFactory::ID, $updated->getId());
        self::assertEquals('updated title', $updated->getTitle());
        self::assertRegExp(TestTodoFactory::DATE_ATOM_REGEX, $updated->getCreated()->format(DATE_ATOM));
        self::assertRegExp(TestTodoFactory::DATE_ATOM_REGEX, $updated->getUpdated()->format(DATE_ATOM));
    }

    /**
     * @test
     */
    public function itDoesNotUpdateWithSameTitleName()
    {
        $this->todoRepository->getById(Argument::type('string'))->shouldBeCalled()->willReturn(TestTodoFactory::create());

        $updated = $this->todoService->update(TestTodoFactory::ID, ['title' => TestTodoFactory::TITLE], new DateTimeImmutable());

        self::assertEquals(TestTodoFactory::ID, $updated->getId());
        self::assertEquals(TestTodoFactory::TITLE, $updated->getTitle());
        self::assertRegExp(TestTodoFactory::DATE_ATOM_REGEX, $updated->getCreated()->format(DATE_ATOM));
        self::assertNull($updated->getUpdated());
    }

    /**
     * @test
     */
    public function itList()
    {
        $this->todoRepository->list()->shouldBeCalled()->willReturn(
            TestTodoFactory::createCollection(
                [
                    TestTodoFactory::create('12345'),
                    TestTodoFactory::create('67890'),
                    TestTodoFactory::create('12093')
                ]
            )
        );

        $this->todoService->list();
    }

    /**
     * @test
     */
    public function itDelete()
    {
        $this->todoRepository->getById(Argument::type('string'))->shouldBeCalled()->willReturn(TestTodoFactory::create());
        $this->todoRepository->delete(Argument::type(Todo::class))->shouldBeCalled();

        $this->todoService->delete(TestTodoFactory::ID);
    }

    /**
     * @test
     */
    public function itGetById()
    {
        $this->todoRepository->getById(Argument::type('string'))->shouldBeCalled()->willReturn(TestTodoFactory::create());

        $todo = $this->todoService->getById(TestTodoFactory::ID);

        self::assertEquals(TestTodoFactory::ID, $todo->getId());
        self::assertEquals(TestTodoFactory::TITLE, $todo->getTitle());
        self::assertRegExp(TestTodoFactory::DATE_ATOM_REGEX, $todo->getCreated()->format(DATE_ATOM));
        self::assertNull($todo->getUpdated());
    }

    /**
     * @test
     */
    public function itGetByName()
    {
        $this->todoRepository->getByName(Argument::type('string'))->shouldBeCalled()->willReturn(TestTodoFactory::create());

        $todo = $this->todoService->getByName(TestTodoFactory::ID);

        self::assertEquals(TestTodoFactory::ID, $todo->getId());
        self::assertEquals(TestTodoFactory::TITLE, $todo->getTitle());
        self::assertRegExp(TestTodoFactory::DATE_ATOM_REGEX, $todo->getCreated()->format(DATE_ATOM));
        self::assertNull($todo->getUpdated());
    }

    /**
     * @test
     * @expectedException \Etechnologia\Platform\Todo\Core\Todo\Exception\TodoEmptyPayloadException
     */
    public function itThrowsEmptyPayloadExceptionAsItCreates()
    {
        $this->todoService->create(TestTodoFactory::ID, null, new DateTimeImmutable());
    }

    /**
     * @test
     * @expectedException \Etechnologia\Platform\Todo\Core\Todo\Exception\TodoValidateIdNotFoundException
     */
    public function itThrowsIdNotFoundExceptionAsItGetsById()
    {
        $this->todoRepository->getById(Argument::type('string'))->shouldBeCalled()->willReturn(null);

        $this->todoService->getById(TestTodoFactory::ID);
    }

    /**
     * @test
     * @expectedException \Etechnologia\Platform\Todo\Core\Todo\Exception\TodoTaskExistAlreadyException
     */
    public function itThrowsTaskAlreadyExistException()
    {
        $this->todoRepository->getByName(TestTodoFactory::TITLE)->shouldBeCalled()->willReturn(TestTodoFactory::create());

        $this->todoService->create(TestTodoFactory::ID, ['title' => TestTodoFactory::TITLE], new DateTimeImmutable());
    }

    /**
     * @test
     * @expectedException \Etechnologia\Platform\Todo\Core\Todo\Exception\TodoTaskDoesNotExistException
     */
    public function itThrowsTaskDoesNotExistExceptionWhenCreatingATaskWithEmptyTitle()
    {
        $this->todoRepository->getByName(Argument::type('string'))->shouldBeCalled()->willReturn(null);

        $this->todoService->create(TestTodoFactory::ID, ['title' => ''], new DateTimeImmutable());
    }

    /**
     * @test
     * @expectedException \Etechnologia\Platform\Todo\Core\Todo\Exception\TodoTaskDoesNotExistException
     */
    public function itThrowsTaskDoesNotExistExceptionWhenUpdatingATaskWithEmptyTitle()
    {
        $this->todoRepository->getById(Argument::type('string'))->shouldBeCalled()->willReturn(TestTodoFactory::create());

        $this->todoService->update(TestTodoFactory::ID, ['title' => ''], new DateTimeImmutable());
    }
}
