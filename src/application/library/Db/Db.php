<?php
/**
 * This file is part of the hrphp-widget-api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hrphp\Db;

class Db
{
    private static $instance;

    private $dbh;

    public static function getConnection()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance->getDbh();
    }

    public function getDbh()
    {
        return $this->dbh;
    }

    private function __construct()
    {
        $url = getenv('CLEARDB_DATABASE_URL');
        $conn = parse_url($url);
        $server = $conn['host'];
        $username = $conn['user'];
        $password = $conn['pass'];
        $db = substr($conn['path'], 1);
        $dsn = sprintf('mysql:host=%s;dbname=%s', $server, $db);
        $this->dbh = new \PDO($dsn, $username, $password);
    }
}
