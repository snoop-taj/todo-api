<?php
declare(strict_types=1);

namespace Etechnologia\Platform\Todo\Core\Response\Serializer;

use DateTimeImmutable;
use Etechnologia\Platform\Todo\Core\Todo\Todo;
use Etechnologia\Platform\Todo\Core\Todo\TodoCollection;

class TodoSerializer
{
    public function toArray(Todo $todo)
    {
        return [
            'id'        => $todo->getId(),
            'title'     => $todo->getTitle(),
            'created'   => $todo->getCreated()->format(DATE_ATOM),
            'updated'   => $todo->getUpdated() instanceof DateTimeImmutable
                ? $todo->getUpdated()->format(DATE_ATOM)
                : null
        ];
    }

    public function collectionToArray(TodoCollection $todoCollection)
    {
        $collection = [];
        foreach ($todoCollection as $todo) {
            $collection[] = $this->toArray($todo);
        }

        return $collection;
    }
}
