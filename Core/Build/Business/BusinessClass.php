<?php

namespace analib\Core\Build\Business;

/* * *************************************************************************
 *   @author acround
 * ************************************************************************* */

class BusinessClass
{

    const PROTO_NAME_PREFIX = 'Proto';
    const COLLECTION_NAME_SUFFIX = 'Collection';

    protected $name;
    protected $extends;
    protected $title;
    protected $fields = [];

    public function __construct($name)
    {
        $this->setName($name);
    }

    /**
     *
     * @param string $name
     * @return BusinessClass
     */
    public static function create($name)
    {
        return new BusinessClass($name);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getProtoName()
    {
        return self::PROTO_NAME_PREFIX . $this->name;
    }

    public function getCollectionName()
    {
        return $this->name . self::COLLECTION_NAME_SUFFIX;
    }

    /**
     *
     * @param string $name
     * @return BusinessClass
     */
    public function setName($name)
    {
        $this->name = ucfirst($name);
        return $this;
    }

    public function getExtends()
    {
        return $this->extends;
    }

    /**
     *
     * @param string $name
     * @return BusinessClass
     */
    public function setExtends($name)
    {
        $this->extends = $name;
        return $this;
    }

    /**
     *
     * @param string $title
     * @return BusinessClass
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function addField(BusinessClassField $field)
    {
        $this->fields[$field->getName()] = $field;
        return $this;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function dumpProto()
    {
        /* @var $field BusinessClassField */
        /*
         * Шапка
         */
        $out = "<?php\n";
        $out .= "\n";
        $out .= "/* * ***************************************************************************\n";
        $out .= " *   Business proto objects generator\n";
        $out .= " *   @author acround\n";
        $out .= " * *************************************************************************** */\n";
        $out .= "\n";
        $extends = ' extends ' . ($this->getExtends() ?: 'NOSSObject');
        $out .= "abstract class " . $this->getProtoName() . $extends . " {\n";
        $out .= "\n";
        $out .= "\tstatic protected \$actuallyFields = array(\n";
        /*
         * Поля
         */
        foreach ($this->getFields() as $field) {
            $out .= $field->dumpField();
        }
        $out .= "\t);\n";
//		$out .= "\n";
        /*
         * getField
         * setField
         */
        foreach ($this->getFields() as $field) {
            $out .= $field->dumpSetter();
            $out .= $field->dumpGetter();
        }
        /**
         * Финал
         */
        $out .= "\n";
        $out .= "}\n";
        return $out;
    }

    public function dumpCollection()
    {
        /*
         * Шапка
         */
        $out = "<?php\n";
        $out .= "\n";
        $out .= "/* * ***************************************************************************\n";
        $out .= " *   Business collection objects generator\n";
        $out .= " *   @author acround\n";
        $out .= " * *************************************************************************** */\n";
        $out .= "\n";
        $extends = ' extends AnaCollection';
        $out .= "class " . $this->getCollectionName() . $extends . " {\n";
        $out .= "\n";
        $out .= "\tconst CLASS_NAME = '" . $this->getName() . "';\n";
        /**
         * create()
         */
        $out .= "\n";
        $out .= "\t/**\n";
        $out .= "\t * @return \\" . $this->getCollectionName() . "\n";
        $out .= "\t */\n";
        $out .= "\tpublic static function create() {\n";
        $out .= "\t\t$" . lcfirst($this->getCollectionName()) . " = new " . $this->getCollectionName() . "();\n";
        $out .= "\t\treturn $" . lcfirst($this->getCollectionName()) . ";\n";
        $out .= "\t}\n";
        /**
         * Финал
         */
        $out .= "\n";
        $out .= "}\n";
        return $out;
    }

    public function dump()
    {
        /* @var $field BusinessClassField */
        /*
         * Шапка
         */
        $out = "<?php\n";
        $out .= "\n";
        $out .= "/* * ***************************************************************************\n";
        $out .= " *   Business objects generator\n";
        $out .= " *   @author acround\n";
        $out .= " * *************************************************************************** */\n";
        $out .= "\n";
        $out .= "class " . $this->getName() . ' extends ' . $this->getProtoName() . " {\n";
        /**
         * create()
         */
        $out .= "\n";
        $out .= "\t/**\n";
        $out .= "\t * @param array \$row\n";
        $out .= "\t * @return \\" . $this->getName() . "\n";
        $out .= "\t */\n";
        $out .= "\tpublic static function create(array \$row = array()) {\n";
        $out .= "\t\t$" . lcfirst($this->getName()) . " = new " . $this->getName() . "();\n";
        $out .= "\t\treturn $" . lcfirst($this->getName()) . "->init(\$row);\n";
        $out .= "\t}\n";
        $out .= "\n";
        $out .= "}\n";
        return $out;
    }

}
