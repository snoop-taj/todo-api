<?php
declare(strict_types=1);

namespace Etechnologia\Platform\Todo\Behat;

use Doctrine\Bundle\DoctrineBundle\Registry;

class DBHandler
{
    /** @var Registry */
    private $registry;

    /**
     * @param Registry $registry
     */
    public function setRegistry(Registry $registry): void
    {
        $this->registry = $registry;
    }

    /**
     * @param string $entityName
     * @param string $table
     * @param string $colName
     * @param array $row
     * @return array
     */
    public function createEntity(string $entityName, string $table, string $colName, array $row): array
    {
        $entityManager = $this->registry->getManager($entityName);
        $connection = $entityManager->getConnection();
        $connection->insert($table, $row);

        $lastInsertedId = $connection->lastInsertId() !== '0' ? $connection->lastInsertId() : $row[$colName];

        $statement = $connection->query($this->getStatement($table, $colName, $lastInsertedId));
        return $statement->fetch();
    }

    /**
     * @param string $entityName
     * @param string $table
     * @param string $colName
     * @param string $id
     * @param array $data
     * @return array
     */
    public function updateEntity(string $entityName, string $table, string $colName, string $id, array $data): array
    {
        $entityManager = $this->registry->getManager($entityName);
        $connection = $entityManager->getConnection();
        $connection->update($table, $data, [$colName => $id]);

        $statement = $connection->query($this->getStatement($table, $colName, $connection->quote($id)));
        return $statement->fetch();
    }

    /**
     * @param string $entityName
     * @param string $table
     * @param string $colName
     * @param string $id
     */
    public function deleteEntity(string $entityName, string $table, string $colName, string $id): void
    {
        $entityManager = $this->registry->getManager($entityName);
        $connection = $entityManager->getConnection();
        $connection->delete($table, [$colName => $id]);
    }

    /**
     * @param string $table
     * @param string $colName
     * @param string $id
     * @return string
     */
    private function getStatement(string $table, string $colName, string $id): string
    {
        return sprintf('SELECT * FROM %s WHERE %s = %s', $table, $colName, $id);
    }

}
