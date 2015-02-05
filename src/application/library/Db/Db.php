<?php
/**
 * This file is part of the hrphp-widget-api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// test
namespace Hrphp\Db;

class Db
{
    /**
     * @var \Hrphp\Db\Db
     */
    private static $instance;

    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @return \PDO
     */
    public static function getConnection()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance->getPdo();
    }

    /**
     * @param \PDO $pdo
     */
    public function setPdo(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @return \PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     * Parses the connection URL and registers the PDO driver.
     */
    private function __construct()
    {
        $url = getenv('CLEARDB_DATABASE_URL');
        $conn = parse_url($url);
        $db = substr($conn['path'], 1);
        $dsn = sprintf('mysql:host=%s;dbname=%s', $conn['host'], $db);
        $pdo = new \PDO($dsn, $conn['user'], $conn['pass']);
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->setPdo($pdo);
    }
}
