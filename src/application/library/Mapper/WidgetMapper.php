<?php
/**
 * This file is part of the hrphp-widget-api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hrphp\Mapper;

use Hrphp\Db\Db;
use Hrphp\Exception\RecordsNotFoundException;

class WidgetMapper
{
    public static function findAll($offset = 0, $limit = 0)
    {
        $db = Db::getConnection();
        $limit = $limit ?: 5;
        $sql = sprintf('SELECT id,name FROM widgets LIMIT %d OFFSET %d', $limit, $offset);
        $stmt = $db->prepare($sql);
        $stmt->execute();
        if (!$results = $stmt->fetchAll(\PDO::FETCH_ASSOC)) {
            throw new RecordsNotFoundException();
        }
        return $results;
    }

    public static function findByKeyword($keyword, $offset = 0, $limit = 0)
    {
        $db = Db::getConnection();
        $limit = $limit ?: 5;
        $format = 'SELECT id,name FROM widgets '
            . 'WHERE color like \'%%%s%%\' OR name like \'%%%s%%\' '
            . 'LIMIT %d OFFSET %d';
        $sql = sprintf($format, $keyword, $keyword, $limit, $offset);
        $stmt = $db->prepare($sql);
        $stmt->execute();
        if (!$results = $stmt->fetchAll(\PDO::FETCH_ASSOC)) {
            throw new RecordsNotFoundException();
        }
        return $results;
    }

    public static function findById($id)
    {
        $db = Db::getConnection();
        $sql = 'SELECT * FROM widgets WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$results) {
            throw new RecordsNotFoundException();
        }
        return $results;
    }

    public static function create($id, $data)
    {
        $db = Db::getConnection();
        $sql = sprintf('DELETE FROM widgets WHERE id = %d', $id);
        if ($rows = $db->exec($sql)) {
            return true;
        }
        return false;
    }

    public static function delete($id)
    {
        $db = Db::getConnection();
        $sql = sprintf('DELETE FROM widgets WHERE id = %d', $id);
        if ($rows = $db->exec($sql)) {
            return true;
        }
        return false;
    }
}
