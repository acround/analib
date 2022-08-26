<?php

namespace analib\Core\Build\MySQL;

use function lcfirst;

/**
 * Description of MyObjectPropertyBuilder
 *
 * @author acround
 */
class MyObjectPropertyBuilder
{

    const FIELD_TYPE_DEFAULT = '';
    const FIELD_TYPE_BOOLEAN = 'boolean';

    private $parent;
    private $class;
    private $object;
    private $dbname;
    private $default;
    private $length;
    private $null;
    private $propname;
    private $type;
    private $title;
    private $fieldsType;

    public function __construct($parent, $propname, $dbname, array $params = [])
    {
        $this->parent = $parent;
        $this->propname = $propname;
        $this->dbname = $dbname;
        if (array_key_exists('fields', $params)) {
            $this->fieldsType = strtolower($params['fields']);
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
            'class' => $this->class,
            'dbname' => $this->dbname,
            'default' => $this->default,
            'length' => $this->length,
            'null' => $this->null,
            'propname' => $this->propname,
            'type' => $this->type,
            'title' => $this->title,
            'fields' => $this->fieldsType,
        ];
    }

    public static function create($parent, $propname, $dbname, array $params = [])
    {
        return new self($parent, $propname, $dbname, $params);
    }

    public function setClass($class)
    {
        $this->class = $class;
        return $this;
    }

    public function setObject($object)
    {
        $this->object = $object;
        return $this;
    }

    public function setDBName($dbname)
    {
        $this->dbname = $dbname;
        return $this;
    }

    public function setDefault($default)
    {
        $this->default = $default;
        return $this;
    }

    public function setLength($length)
    {
        $this->length = $length;
        return $this;
    }

    public function setNull($null)
    {
        $this->null = $null;
        return $this;
    }

    public function setPropname($propname)
    {
        $this->propname = $propname;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function getObject()
    {
        return $this->object;
    }

    public function getDBName()
    {
        return $this->dbname;
    }

    public function getDefault()
    {
        return $this->default;
    }

    public function getLength()
    {
        return $this->length;
    }

    public function getNull()
    {
        return $this->null;
    }

    public function getPropname()
    {
        return $this->propname;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getTitle()
    {
        return $this->title;
    }

    /**
     *
     * @return string
     */
    public function dumpSetter()
    {
        $out = "\t/**\n";
        if ($this->getTitle()) {
            $out .= "\t * " . $this->getTitle() . "\n";
            $out .= "\t * " . "\n";
        }
        $out .= "\t * @param " . $this->type . " \$" . lcfirst($this->getPropname()) . "\n";
        $out .= "\t * @return " . $this->parent->getProtoName() . "\n";
        $out .= "\t */\n";
        $out .= "\tpublic function set" . $this->getPropname() . "(\$" . lcfirst($this->getPropname()) . ")\n";
        $out .= "\t{\n";
        $varName = ($this->fieldsType === MyModelBuilder::FIELDS_TYPE_ARRAY) ? "\$this->content['" . lcfirst($this->getPropname()) . "']" : '$this->' . lcfirst($this->getPropname());
        $i = $this->type;
        if ($i === self::FIELD_TYPE_BOOLEAN) {
            $out .= "\t\t" . $varName . " = (int)(boolean)\$" . lcfirst($this->getPropname()) . ";\n";
        } else {
            $out .= "\t\t" . $varName . " = \$" . lcfirst($this->getPropname()) . ";\n";
        }
        $out .= "\t\treturn \$this;\n";
        $out .= "\t}\n";
        $out .= "\n";
        if ($this->getClass()) {
            $out .= "\t/**\n";
            if ($this->getTitle()) {
                $out .= "\t * " . $this->getTitle() . "\n";
                $out .= "\t * " . "\n";
            }
            $out .= "\t * @param " . $this->getClass() . " \$" . lcfirst($this->getObject()) . "\n";
            $out .= "\t * @return " . $this->parent->getProtoName() . "\n";
            $out .= "\t */\n";
            $out .= "\tpublic function set" . $this->getObject() . "(" . $this->getClass() . " $" . lcfirst($this->getObject()) . ")\n";
            $out .= "\t{\n";
            $out .= "\t\t\$this->objects['" . $this->getObject() . "'] = \$" . lcfirst($this->getObject()) . ";\n";
            $out .= "\t\t\$this->set" . $this->getPropname() . "(\$" . lcfirst($this->getObject()) . "->getId());\n";
            $out .= "\t\treturn \$this;\n";
            $out .= "\t}\n";
            $out .= "\n";
        }
        return $out;
    }

    /**
     *
     * @return string
     */
    public function dumpGetter()
    {
        $out = "";
        if ($this->getTitle()) {
            $out .= "\t/**\n";
            $out .= "\t * " . $this->getTitle() . "\n";
            $out .= "\t* " . "\n";
            $out .= "\t */\n";
        }
        $out .= "\tpublic function get" . $this->getPropname() . "()\n";
        $out .= "\t{\n";
        $varName = ($this->fieldsType === MyModelBuilder::FIELDS_TYPE_ARRAY) ? "\$this->content['" . lcfirst($this->getPropname()) . "']" : '$this->' . lcfirst($this->getPropname());
        $out .= "\t\treturn " . $varName . ";\n";
        $out .= "\t}\n";
        $out .= "\n";
        if ($this->getClass()) {
            $out .= "\t/**\n";
            if ($this->getTitle()) {
                $out .= "\t * " . $this->getTitle() . "\n";
                $out .= "\t * " . "\n";
            }
            $out .= "\t * @return \\Business\\Classes\\" . $this->getClass() . "\n";
            $out .= "\t */\n";
            $out .= "\tpublic function get" . $this->getObject() . "()\n";
            $out .= "\t{\n";
            switch ($this->fieldsType) {
                case MyModelBuilder::FIELDS_TYPE_ARRAY:
                    $out .= "\t\tif (!isset(\$this->objects['" . $this->getObject() . "']) || !\$this->objects['" . $this->getObject() . "']) {\n";
                    $out .= "\t\t\t\$this->objects['" . $this->getObject() . "'] = MyDAO::getInstance('" . $this->getClass() . "')->getById(\$this->get" . $this->getPropname() . "());\n";
                    $out .= "\t\t}\n";
                    $out .= "\t\treturn \$this->objects['" . $this->getObject() . "'];\n";
                    break;
                case MyModelBuilder::FIELDS_TYPE_FIELD:
                    $out .= "\t\tif (!\$this->" . lcfirst($this->getClass()) . ") {\n";
                    $out .= "\t\t\t\$this->" . lcfirst($this->getClass()) . " = MyDAO::getInstance('" . $this->getClass() . "')->getById(\$this->get" . $this->getPropname() . "());\n";
                    $out .= "\t\t}\n";
                    $out .= "\t\treturn \$this->" . lcfirst($this->getClass()) . ";\n";
                    break;
            }
            $out .= "\t}\n";
            $out .= "\n";
        }
        return $out;
    }

}
