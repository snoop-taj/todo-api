<?php
declare(strict_types=1);

namespace Etechnologia\Platform\Todo\Core\Todo;

use DateTimeImmutable;

class Todo
{
    /** @var string */
    private $id;

    /** @var string */
    private $title;

    /** @var DateTimeImmutable */
    private $created;

    /** @var DateTimeImmutable */
    private $updated;

    /**
     * @param string $id
     * @param string $title
     * @param DateTimeImmutable $created
     * @param DateTimeImmutable $updated
     */
    public function __construct(string $id, string $title, DateTimeImmutable $created, DateTimeImmutable $updated = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->created = $created;
        $this->updated = $updated;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreated(): DateTimeImmutable
    {
        return $this->created;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getUpdated(): ?DateTimeImmutable
    {
        return $this->updated;
    }
}
