<?php

namespace App\Utils;

class DB
{
    public function __construct(protected \PDO $pdo)
    {
    }

    /**
     * Accepts "SELECT" query statements and returns a resolved result via fetchAll().
     *
     * @param string $sql
     *
     * @return array|bool
     */
    public function select(string $sql): array|bool
    {
        $sth = $this->pdo->query($sql);

        return $sth->fetchAll();
    }

    /**
     * Execute an SQL statement and return the number of affected row.
     *
     * @param string $sql
     *
     * @return bool|int
     */
    public function exec(string $sql): bool|int
    {
        return $this->pdo->exec($sql);
    }

    /**
     * Returns the ID of the last inserted row or sequence value.
     *
     * @return bool|string
     */
    public function lastInsertId(): bool|string
    {
        return $this->pdo->lastInsertId();
    }
}