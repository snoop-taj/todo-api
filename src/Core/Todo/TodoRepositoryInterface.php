<?php
declare(strict_types=1);

namespace Etechnologia\Platform\Todo\Core\Todo;

interface TodoRepositoryInterface
{
    /**
     * @param Todo $todo
     */
    public function create(Todo $todo): void;

    /**
     * @return TodoCollection
     */
    public function list(): TodoCollection;

    /**
     * @param Todo $todo
     */
    public function update(Todo $todo): void;

    /**
     * @param Todo $todo
     */
    public function delete(Todo $todo): void;

    /**
     * @param string $id
     * @return Todo|null
     */
    public function getById(string $id): ?Todo;

    /**
     * @param string $title
     * @return Todo|null
     */
    public function getByName(string $title): ?Todo;

}