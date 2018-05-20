<?php
declare(strict_types=1);

namespace Etechnologia\Platform\Todo\Core\Todo;

use DateTimeImmutable;
use Etechnologia\Platform\Todo\Core\Todo\Exception\TodoEmptyPayloadException;
use Etechnologia\Platform\Todo\Core\Todo\Exception\TodoTaskExistAlreadyException;
use Etechnologia\Platform\Todo\Core\Todo\Exception\TodoValidateIdNotFoundException;
use Etechnologia\Platform\Todo\Core\Todo\Exception\TodoTaskDoesNotExistException;

class TodoService
{
    /** @var TodoRepositoryInterface */
    private $todoRepository;

    /**
     * @param TodoRepositoryInterface $todoRepository
     */
    public function __construct(TodoRepositoryInterface $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    /**
     * @param string $id
     * @param array|null $todo
     * @param DateTimeImmutable $now
     * @return Todo
     * @throws TodoEmptyPayloadException
     * @throws TodoTaskDoesNotExistException
     * @throws TodoTaskExistAlreadyException
     */
    public function create(string $id, ?array $todo, DateTimeImmutable $now): Todo
    {
        if (is_null($todo)) {
            throw new TodoEmptyPayloadException("Payload can not be empty");
        }

        $this->ensureTaskDoesNotExist($todo['title']);
        $this->validateTaskTitle($todo['title']);

        $data = new Todo($id, $todo['title'], $now);
        $this->todoRepository->create($data);
        return $data;
    }

    /**
     * @return TodoCollection
     */
    public function list(): TodoCollection
    {
        return $this->todoRepository->list();
    }

    /**
     * @param string $id
     * @return Todo|null
     * @throws TodoValidateIdNotFoundException
     */
    public function getById(string $id): ?Todo
    {
        $todo = $this->todoRepository->getById($id);
        if (is_null($todo)) {
            throw new TodoValidateIdNotFoundException("ID Not Found");
        }

        return $todo;
    }

    /**
     * @param string $id
     * @param array $todo
     * @param DateTimeImmutable $now
     * @return Todo
     * @throws TodoTaskDoesNotExistException
     * @throws TodoValidateIdNotFoundException
     */
    public function update(string $id, array $todo, DateTimeImmutable $now): Todo
    {
        $existingTodo = $this->getById($id);

        if ($existingTodo->getTitle() === $todo['title']) {
            return $existingTodo;
        }

        $this->validateTaskTitle($todo['title']);

        $updatedTodo = new Todo($id, $todo['title'], $existingTodo->getCreated(), $now);
        $this->todoRepository->update($updatedTodo);
        return $updatedTodo;
    }

    /**
     * @param string $id
     * @return Todo
     * @throws TodoValidateIdNotFoundException
     */
    public function delete(string $id): Todo
    {
        $existingTodo = $this->getById($id);
        $this->todoRepository->delete($existingTodo);

        return $existingTodo;
    }

    /**
     * @param string $title
     * @return Todo|null
     */
    public function getByName(string $title): ?Todo
    {
        return $this->todoRepository->getByName($title);
    }

    /**
     * @param string $title
     * @throws TodoTaskExistAlreadyException
     */
    private function ensureTaskDoesNotExist(string $title): void
    {
        $todo = $this->getByName($title);
        if ($todo instanceof Todo && $todo->getTitle() === $title) {
            throw new TodoTaskExistAlreadyException(sprintf("Task %s already exist", $title));
        }
    }

    /**
     * @param string $title
     * @throws TodoTaskDoesNotExistException
     */
    private function validateTaskTitle(string $title): void
    {
        if (empty($title)) {
            throw new TodoTaskDoesNotExistException("Title is empty");
        }
    }

}
