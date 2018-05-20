<?php
declare(strict_types=1);

namespace Etechnologia\Platform\Todo\Core\Todo;

use DateTimeImmutable;

class TestTodoFactory
{
    public const ID = '1234';
    public const TITLE = 'my todo task';
    public const CREATED = 'now';
    public const UPDATED = '+1 week';
    public const DATE_ATOM_REGEX = '#^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\+\d{2}:\d{2}$#';

    /**
     * @param string $id
     * @return Todo
     */
    public static function create($id = self::ID): Todo
    {
        return new Todo(
            $id,
            self::TITLE,
            new DateTimeImmutable(self::CREATED)
        );
    }

    /**
     * @param string $id
     * @return Todo
     */
    public static function update($id = self::ID): Todo
    {
        return new Todo(
            $id,
            self::TITLE,
            new DateTimeImmutable(self::CREATED),
            new DateTimeImmutable(self::UPDATED)
        );
    }

    /**
     * @param array $todos
     * @return TodoCollection
     */
    public static function createCollection(array $todos): TodoCollection
    {
        $collection = new TodoCollection();
        foreach ($todos as $todo) {
            $collection->add($todo);
        }

        return $collection;
    }
}
