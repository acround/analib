<?php

namespace analib\Core\DB;

use analib\Core\DB\MySQL\MyDAO;
use analib\Core\Exceptions\DBException;
use analib\Core\Exceptions\ObjectNotFoundException;
use analib\Core\Exceptions\WrongArgumentException;
use analib\Util\StringUtils;

/**
 * Базовый класс для моделей
 *
 * @author acround
 */
abstract class BaseResource
{

    public const ACCESS_READ = 'r';
    public const ACCESS_WRITE = 'w';
    public const ACCESS_READ_WRITE = 'rw';

    protected array $content;
    protected array $objects;
    protected string $table;

    /**
     *
     * @param array $row
     * @return BaseResource
     */
    public function init(array $row = array()): BaseResource
    {
        foreach ($this->content as $name => $dbName) {
            if (isset($row[$dbName])) {
                $setter = 'set' . $name;
                $this->$setter($row[$dbName]);
            }
        }
        return $this;
    }

    protected function describe(): BaseResource
    {
        $columnsList = MyDAO::getColumnsList($this->getTable());
        foreach ($columnsList as $info) {
            $this->content[$info['Field']] = $info['Default'];
        }
        return $this;
    }

    /**
     * BaseResource constructor.
     * @param array|null $row
     */
    public function __construct(array $row = null)
    {
        $this->describe();
        foreach ($this->content as $key => $value) {
            if (isset($row[$key])) {
                $this->content[$key] = $row[$key];
            }
        }
    }

    public function import(array $row, $charset = StringUtils::LOCALE_CHARSET): BaseResource
    {
        foreach ($this->content as $key => $value) {
            if (isset($row[$key])) {
                if (strtolower($charset) === StringUtils::LOCALE_CHARSET) {
                    $this->content[$key] = $row[$key];
                } else {
                    $this->content[$key] = iconv($charset, StringUtils::LOCALE_CHARSET, $row[$key]);
                }
            }
        }
        return $this;
    }

    public function getContent(): array
    {
        return $this->content;
    }

    /**
     * @return DAO
     * @throws WrongArgumentException
     */
    public static function getDAO(): DAO
    {
        throw new WrongArgumentException('Do not call this method:' . __METHOD__);
    }

    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * @throws ObjectNotFoundException
     */
    public function __call($name, $arguments)
    {
        if (substr($name, 0, 3) === 'get') {
            $propertyName = substr($name, 3);
            if (isset($this->content[$propertyName])) {
                return $this->content[$propertyName];
            }
            throw new ObjectNotFoundException($propertyName . ' - undefined field.');
        } elseif (substr($name, 0, 3) === 'set') {
            $propertyName = substr($name, 3);
            if (isset($this->content[$propertyName])) {
                $value = $arguments[0] ?? null;
                $this->content[$propertyName] = $value;
                return $this;
            }

            throw new ObjectNotFoundException($propertyName . ' - undefined field.');
        } else {
            throw new ObjectNotFoundException($name . ' - undeclared method.');
        }
    }

    abstract public function getId();

    abstract public function setId($id);

    /**
     * @throws DBException
     * @throws WrongArgumentException
     */
    public function save(): BaseResource
    {
        static::getDAO()->save($this);
        return $this;
    }

    /**
     * @throws WrongArgumentException
     */
    public function add(): BaseResource
    {
        self::getDAO()->add($this);
        return $this;
    }

    /**
     * @throws WrongArgumentException
     */
    public function take(): BaseResource
    {
        self::getDAO()->take($this);
        return $this;
    }

}
