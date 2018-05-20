<?php
declare(strict_types=1);

namespace Etechnologia\Platform\Todo\Infrastructure\Database\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Command\Proxy\DoctrineCommandHelper;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Etechnologia\Platform\Todo\Core\Todo\Todo;
use Etechnologia\Platform\Todo\Core\Todo\TodoCollection;
use Etechnologia\Platform\Todo\Core\Todo\TodoRepositoryInterface;
use Symfony\Component\Cache\Adapter\DoctrineAdapter;

class TodoRepository implements TodoRepositoryInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var EntityRepository */
    private $repo;

    public function __construct(Registry $registry)
    {
        $this->em = $registry->getManager('default');
        $this->repo = $this->em->getRepository(Todo::class);
    }

    /**
     * @param Todo $todo
     */
    public function create(Todo $todo): void
    {
        $this->em->persist($todo);
        $this->em->flush();
    }

    /**
     * @return TodoCollection
     */
    public function list(): TodoCollection
    {
        return TodoCollection::createFrom($this->repo->findAll());
    }

    /**
     * @param Todo $todo
     */
    public function update(Todo $todo): void
    {
        $this->em->merge($todo);
        $this->em->flush();
    }

    /**
     * @param Todo $todo
     */
    public function delete(Todo $todo): void
    {
        $this->em->remove($todo);
        $this->em->flush();
    }

    /**
     * @param string $id
     * @return Todo|null
     */
    public function getById(string $id): ?Todo
    {
        $todo = $this->repo->findOneBy(['id' => $id]);
        return $todo instanceof Todo ? $todo : null;
    }

    /**
     * @param string $title
     * @return Todo|null
     */
    public function getByName(string $title): ?Todo
    {
        $todo = $this->repo->findOneBy(['title' => $title]);
        return $todo instanceof Todo ? $todo : null;
    }
}
