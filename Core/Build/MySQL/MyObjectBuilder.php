<?php

namespace analib\Core\Build\MySQL;

/**
 * Description of MyObjectBuilder
 *
 * @author acround
 */
class MyObjectBuilder
{

    const PROTO_NAME_PREFIX = 'Proto';

    private $name;
    private $table;
    private $title;
    protected $type;
    private $fieldsType;
    private $doProto;
    private $setget;
    protected $collation;
    private $properties = [];

    public function __construct($name = null, $table = null, array $params = [])
    {
        $this->name = $name;
        $this->table = $table;
        if (array_key_exists('do_proto', $params)) {
            if (is_bool($params['do_proto'])) {
                $this->doProto = $params['do_proto'];
            } elseif (is_numeric($params['do_proto'])) {
                $this->doProto = (boolean)$params['do_proto'];
            } elseif (strtolower($params['do_proto']) === 'null') {
                $this->doProto = false;
            } elseif (strtolower($params['do_proto']) === 'false') {
                $this->doProto = false;
            } elseif (strtolower($params['do_proto']) === 'true') {
                $this->doProto = true;
            } else {
                $this->doProto = (boolean)$params['do_proto'];
            }
        }
        if (array_key_exists('fields', $params)) {
            $this->fieldsType = strtolower($params['fields']);
        }
        if (array_key_exists('setget', $params)) {
            if (is_bool($params['setget'])) {
                $this->setget = $params['setget'];
            } elseif (is_numeric($params['setget'])) {
                $this->setget = (boolean)$params['setget'];
            } elseif (strtolower($params['setget']) === 'null') {
                $this->setget = false;
            } elseif (strtolower($params['setget']) === 'false') {
                $this->setget = false;
            } elseif (strtolower($params['setget']) === 'true') {
                $this->setget = true;
            } else {
                $this->setget = (boolean)$params['setget'];
            }
        }
        $this->checkFields();
    }

    private function checkFields()
    {
        if (!in_array($this->fieldsType, [MyModelBuilder::FIELDS_TYPE_ARRAY, MyModelBuilder::FIELDS_TYPE_FIELD], true)) {
            $this->fieldsType = MyModelBuilder::FIELDS_TYPE_FIELD;
        }
    }

    public function getFields()
    {
        return [
            'name' => $this->name,
            'table' => $this->table,
            'title' => $this->title,
            'type' => $this->type,
            'fields' => $this->fieldsType,
            'do_proto' => $this->doProto,
            'setget' => $this->setget,
        ];
    }

    /**
     *
     * @param string $name
     * @param string $table
     * @return MyObjectBuilder
     */
    public static function create($name = null, $table = null, array $params = [])
    {
        return new self($name, $table, $params);
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getProtoName()
    {
        return self::PROTO_NAME_PREFIX . $this->name;
    }

    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setCollation($collation)
    {
        $this->collation = $collation;
        return $this;
    }

    public function getCollation()
    {
        return $this->collation;
    }

    public function addProperty(MyObjectPropertyBuilder $property)
    {
        $this->properties[$property->getDBName()] = $property;
    }

    protected function putFields()
    {
        $out = '';
        switch ($this->fieldsType) {
            case MyModelBuilder::FIELDS_TYPE_ARRAY:
                $out .= "	protected \$content = array(\n";
                /* @var $property MyObjectPropertyBuilder */
                foreach ($this->properties as $property) {
                    $defaultS = $default = $property->getDefault();
                    if (is_null($default)) {
                        $defaultS = 'null';
                    } elseif (is_string($default) && ($default === 'null')) {
                        $defaultS = 'null';
                    } elseif ((string)(int)$default === $default) {

                    } else {
                        $defaultS = "'" . $defaultS . "'";
                    }
                    $out .= "\t\t'" . lcfirst($property->getPropname()) . "' => " . $defaultS . ",\n";
                }
                $out .= "	);\n";
                break;
            case MyModelBuilder::FIELDS_TYPE_FIELD:
                foreach ($this->properties as $property) {
                    $defaultS = $default = $property->getDefault();
                    if (is_null($default)) {
                        $defaultS = 'null';
                    } elseif (is_string($default) && ($default === 'null')) {
                        $defaultS = 'null';
                    } elseif ((string)(int)$default === $default) {

                    } else {
                        $defaultS = "'" . $defaultS . "'";
                    }
                    $access = $this->setget ? 'private' : 'public';
                    $out .= "\t" . $access . " \$" . lcfirst($property->getPropname()) . ' = ' . $defaultS . ";\n";
                    if ($property->getClass()) {
                        $out .= "\n";
                        $out .= "\t/**\n";
                        $out .= "\t *\n";
                        $out .= "\t * @var " . $property->getClass() . "\n";
                        $out .= "\t */\n";
                        $out .= "\t" . $access . " \$" . lcfirst($property->getClass()) . " = null;\n";
                    }
                }
                break;
        }
        $out .= "\n";
        return $out;
    }

    public function dumpProto()
    {
        /* @var $property MyObjectPropertyBuilder */
        /*
         * Шапка
         */
        $out = "<?php\n";
        $out .= "\n";
        $out .= "namespace Business\Proto;\n";
        $out .= "\n";
        $out .= "/*******************************************************************************\n";
        $out .= " *   " . $this->getTitle() . " (Proto)\n";
        $out .= " *   Do not edit this file!\n";
        $out .= " *******************************************************************************\n";
        $out .= " *   Made by MySQL proto objects generator\n";
        $out .= " *   @author acround\n";
        $out .= " ***************************************************************************** */\n";
        $out .= "\n";
        $out .= "abstract class " . $this->getProtoName() . ""; // . " extends Resource\n";
        $out .= "{\n";
        $out .= "\n";
        $out .= "\tprotected \$table = '" . $this->getTable() . "';\n";
        /*
         * Поля
         */
        $out .= $this->putFields();
        if ($this->setget) {
            $out .= "\tpublic function getId()\n";
            $out .= "\t{\n";
            $out .= "\t\treturn \$this->id;\n";
            $out .= "\t}\n";
            $out .= "\n";
        }
        /*
         * get
         * set
         */
        if ($this->setget) {
            foreach ($this->properties as $property) {
                $out .= $property->dumpSetter();
                $out .= $property->dumpGetter();
            }
        }
        $out .= "}\n";

        return $out;
    }

    public function dump()
    {
        $out = "<?php\n";
        $out .= "\n";
        $out .= "namespace Business\Classes;\n";
        $out .= "\n";
        if ($this->doProto) {
            $out .= "use Business\Proto\Proto" . $this->getName() . ";\n";
        }
        $out .= "\n";
        $out .= "/**\n";
        $out .= " * " . $this->getTitle() . "\n";
        $out .= " *\n";
        $out .= " * @author acround\n";
        $out .= " */\n";
        if ($this->doProto) {
            $out .= "class " . $this->getName() . " extends Proto" . $this->getName() . "\n";
        } else {
            $out .= "class " . $this->getName() . "\n";
        }
        $out .= "{\n";
        $out .= "\n";
        if (!$this->doProto) {
            $out .= $this->putFields();
        }
        if (!$this->doProto && $this->setget) {
            $out .= "\tpublic function getId()\n";
            $out .= "\t{\n";
            $out .= "\t\treturn \$this->id;\n";
            $out .= "\t}\n";
            $out .= "\n";
        }
        if ($this->setget) {
            $out .= "\t/**\n";
            $out .= "\t * Do not edit this method!\n";
            $out .= "\t */\n";
            $out .= "\tpublic static function create(array \$row = array())\n";
            $out .= "\t{\n";
            $out .= "\t\treturn new " . $this->getName() . "(\$row);\n";
            $out .= "\t}\n";
            $out .= "\n";
            $out .= "\t/**\n";
            $out .= "\t * Do not edit this method!\n";
            $out .= "\t */\n";
            $out .= "\tpublic static function getDAO()\n";
            $out .= "\t{\n";
            $out .= "\t\treturn MyDAO::getInstance(__CLASS__);\n";
            $out .= "\t}\n";
        }
        if (!$this->doProto && $this->setget) {
            $out .= "\n";
            /*
             * get
             * set
             */
            foreach ($this->properties as $property) {
                $out .= $property->dumpSetter();
                $out .= $property->dumpGetter();
            }
        }
        $out .= "}\n";

        return $out;
    }

}
