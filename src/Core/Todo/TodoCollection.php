<?php
declare(strict_types=1);

namespace Etechnologia\Platform\Todo\Core\Todo;

use Countable;
use Iterator;

class TodoCollection implements Iterator, Countable
{
    /** @var int */
    private $position = 0;

    /**
     * @var array
     */
    private $todos = [];

    /**
     * @param array $todos
     * @return TodoCollection
     */
    public static function createFrom(array $todos): self
    {
        $collection = new self();
        foreach ($todos as $todo) {
            $collection->add($todo);
        }
        return $collection;
    }

    /**
     * @param Todo $todo
     */
    public function add(Todo $todo): void
    {
        $this->todos[] = $todo;
    }

    /**
     * @return Todo
     */
    public function current(): Todo
    {
        return $this->todos[$this->position];
    }

    public function next(): void
    {
        $this->position++;
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->todos[$this->position]);
    }


    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->todos);
    }
}
