<?php
/**
 * This file is part of the hrphp-widget-api package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Hrphp\Entity;

use Hrphp\Db\Db;

class WidgetMapper
{
    const MSG_INVALID_ID = 'Please provide a valid widget ID.';
    const MSG_INVALID_DATA = 'Please provide valid data when updating or creating widgets.';

    public static function findAll($offset = 0, $limit = 0)
    {
        $limit = $limit ?: 5;
        $db = Db::getConnection();
        $sql = sprintf('SELECT id,name FROM widgets LIMIT %d OFFSET %d', $limit, $offset);
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();
        return $results;
    }

    public static function findByKeyword($keyword, $offset = 0, $limit = 0)
    {
        $limit = $limit ?: 5;
        $db = Db::getConnection();
        $format = 'SELECT id,name FROM widgets '
            . 'WHERE color like \'%%%s%%\' OR name like \'%%%s%%\' '
            . 'LIMIT %d OFFSET %d';
        $sql = sprintf($format, $keyword, $keyword, $limit, $offset);
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll();
        return $results;
    }

    public static function findById($id)
    {
        $db = Db::getConnection();
        $sql = 'SELECT * FROM widgets WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        if (!$results = $stmt->fetch()) {
            throw new \DomainException(self::MSG_INVALID_ID);
        }
        return $results;
    }

    public static function create(array $data)
    {
        $db = Db::getConnection();
        $sql = 'INSERT INTO widgets (name, color, createdAt) VALUES (:name, :color, NOW())';
        try {
            $db->beginTransaction();
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':name', $data['name'], \PDO::PARAM_STR);
            $stmt->bindParam(':color', $data['color'], \PDO::PARAM_STR);
            $stmt->execute();
            $id = $db->lastInsertId();
            $db->commit();
            return self::findById($id);
        } catch (\PDOException $ex) {
            $db->rollBack();
            throw new \InvalidArgumentException(self::MSG_INVALID_DATA);
        }
    }

    public static function update($id, array $data)
    {
        if (!self::recordExists($id)) {
            throw new \DomainException(self::MSG_INVALID_ID);
        }
        $db = Db::getConnection();
        $sql = 'UPDATE widgets SET ';
        $updates = [];
        foreach ($data as $field => $value) {
            $updates[] = sprintf('%s = :%s', $field, $field);
        }
        $sql .= implode(', ', $updates) . ' WHERE id = :id';
        try {
            $db->beginTransaction();
            $stmt = $db->prepare($sql);
            foreach ($data as $field => $value) {
                $stmt->bindValue(sprintf(':%s', $field), $value, \PDO::PARAM_STR);
            }
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            $db->commit();
            return self::findById($id);
        } catch (\Exception $ex) {
            $db->rollBack();
            throw new \InvalidArgumentException(self::MSG_INVALID_DATA);
        }
    }

    public static function delete($id)
    {
        if (!self::recordExists($id)) {
            throw new \DomainException(self::MSG_INVALID_ID);
        }
        $db = Db::getConnection();
        $sql = sprintf('DELETE FROM widgets WHERE id = %d', $id);
        $db->beginTransaction();
        $stmt = $db->prepare($sql);
        return $stmt->execute();
    }

    private static function recordExists($id)
    {
        $db = Db::getConnection();
        $sql = 'SELECT id FROM widgets WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        if ($results = $stmt->fetch()) {
            return true;
        }
        return false;
    }
}
