<?php

declare(strict_types=1);

namespace Shortener;

class DatabaseMysql implements DatabaseInterface
{
    /**
     * @var \PDO
     */
    private $connection;

    /** @var string  */
    private $host;

    /** @var string  */
    private $user;

    /** @var string  */
    private $password;

    /** @var string  */
    private $dbName;

    /** @var array  */
    private $options = [
        \PDO::ATTR_PERSISTENT => true,
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
    ];

    /**
     * @param string $host
     * @param string $user
     * @param string $password
     * @param string $dbName
     * @throws DatabaseException
     */
    public function __construct(string $host, string $user, string $password, string $dbName)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->dbName = $dbName;
    }

    public function getConnection()
    {
        $this->connect();

        return $this->connection;
    }

    private function connect(array $options = []): void
    {
        if (!is_null($this->connection)) {
            return;
        }

        try {
            $pdo = new \PDO(
                sprintf('mysql:host=%s;dbname=%s', $this->host, $this->dbName),
                $this->user,
                $this->password,
                array_merge($this->options, $options)
            );
        } catch (\PDOException $e) {
            throw new DatabaseException($e->getMessage(), $e->getCode(), $e);
        }

        $this->connection = $pdo;
    }
}
