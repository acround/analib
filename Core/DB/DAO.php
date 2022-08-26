<?php

namespace analib\Core\DB;

/**
 * Обмен с БД
 *
 * @author acround
 */
abstract class DAO
{

    protected function __construct($modelName)
    {

    }

    abstract public static function getDB();

    abstract public static function getInstance($modelName): DAO;

    abstract public function createModel(): Resource;

    abstract public function getTable(): string;

    abstract public static function query($query);

    abstract public static function getRawByQuery($query);

    abstract public static function getRawListByQuery($query);

    abstract public function getByQuery($query);

    abstract public function getListByQuery($query);

    abstract public function getById($id);

    abstract public static function add(Resource $model);

    abstract public static function take(Resource $model);

    abstract public static function delete(Resource $model);

}
