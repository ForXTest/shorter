<?php

namespace Shortener\Data;

use Shortener\DatabaseInterface;

/**
 * Class UrlRegistry
 *
 * @package Shortener\Data
 */
class UrlRegistry
{
    /**
     * @var DatabaseInterface
     */
    private $db;

    /**
     * @var string
     */
    private $table = 'short_links';

    /**
     * UrlRegistry constructor.
     *
     * @param DatabaseInterface $db
     */
    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
    }

    /**
     * Check if a short url is exists
     *
     * @param string $url a full url
     * @return string
     * @throws UrlRegestryException
     */
    public function checkUrl(string $url): string
    {
        try {
            $query = $this->db->getConnection()
                ->prepare("SELECT short FROM {$this->table} WHERE hash = :hash LIMIT 1");
            $query->execute(['hash' => $this->getHash($url)]);
            $result = $query->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new UrlRegestryException('Database error', 0, $e);
        }

        return $result['short'] ?? '';
    }

    /**
     * Set short url to DB
     *
     * @param string $url
     * @param string $shortUrl
     * @throws UrlRegestryException
     */
    public function setUrl(string $url, string $shortUrl): void
    {
        $data = [
            ':short' => $shortUrl,
            ':hash' => $this->getHash($url),
            ':link' => $url
        ];

        try {
            $query = $this->db->getConnection()
                ->prepare("
                  INSERT INTO {$this->table}
                    (short, hash, link)
                  VALUES (:short, :hash, :link)
                ");
            $query->execute($data);
        } catch (\PDOException $e) {
            throw new UrlRegestryException('Database error', 0, $e);
        }
    }

    /**
     * Get a full url by a short url
     *
     * @param string $shortUrl
     * @return string
     * @throws UrlRegestryException
     */
    public function getFullUrl(string $shortUrl): string
    {
        try {
            $query = $this->db->getConnection()
                ->prepare("SELECT link FROM {$this->table} WHERE short = :short LIMIT 1");
            $query->execute([':short' => $shortUrl]);
            $result = $query->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            throw new UrlRegestryException('Database error', 0, $e);
        }

        return $result['link'] ?? '';
    }

    /**
     * Return url hash
     *
     * @param string $url
     * @return string
     */
    private function getHash(string $url): string
    {
        return md5($url);
    }
}