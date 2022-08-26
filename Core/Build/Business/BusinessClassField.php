<?php

namespace analib\Core\Build\Business;

/* * *************************************************************************
 *   @author acround
 * ************************************************************************* */

class BusinessClassField
{

    const FIELD_TYPE_DEFAULT = '';
    const FIELD_TYPE_BOOLEAN = 'boolean';
    const FIELD_TYPE_INTEGER = 'integer';
    const FIELD_TYPE_EMAIL   = 'email';
    const FIELD_TYPE_PHONE   = 'phone';

    protected $types   = array(
        self::FIELD_TYPE_DEFAULT => 'string',
        self::FIELD_TYPE_BOOLEAN => 'boolean',
        self::FIELD_TYPE_INTEGER => 'integer',
        self::FIELD_TYPE_EMAIL   => 'string',
        self::FIELD_TYPE_PHONE   => 'string',
    );
    protected $filters = array(
        self::FIELD_TYPE_DEFAULT => null,
        self::FIELD_TYPE_BOOLEAN => null,
        self::FIELD_TYPE_INTEGER => 'Integer',
        self::FIELD_TYPE_EMAIL   => 'Email',
        self::FIELD_TYPE_PHONE   => 'Phone',
    );

    /**
     * @var BusinessClass
     */
    private $parent;
    private $name;
    private $dbname;
    private $title;
    private $type     = self::FIELD_TYPE_DEFAULT;
    private $override = false;

    public function __construct(BusinessClass $parent)
    {
        $this->parent = $parent;
    }

    /**
     *
     * @param BusinessClass $parent
     * @return BusinessClassField
     */
    public static function create(BusinessClass $parent)
    {
        return new self($parent);
    }

    /**
     *
     * @param string $name
     * @return BusinessClassField
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getUCName()
    {
        return ucfirst($this->name);
    }

    /**
     *
     * @param string $name
     * @return BusinessClassField
     */
    public function setDBName($name)
    {
        $this->dbname = $name;
        return $this;
    }

    public function getDBName()
    {
        return $this->dbname;
    }

    /**
     *
     * @param string $title
     * @return BusinessClassField
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

    /**
     *
     * @param string $type
     * @return BusinessClassField
     */
    public function setType($type = self::FIELD_TYPE_DEFAULT)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    /**
     *
     * @param boolean $isOverride
     * @return BusinessClassField
     */
    public function setOverride($isOverride = true)
    {
        $this->override = ($isOverride);
        return $this;
    }

    public function isOverride()
    {
        return $this->override;
    }

    /**
     *
     * @return string
     */
    public function dumpField()
    {
        if ($this->isOverride()) {
            $out = '';
        } else {
//			$out = "\tprivate $" . $this->getName() . " = null;\n";
//			$out = "\t\t'" . $this->getUCName() . "' => '" . strtoupper(StringUtils::camel2c($this->getName())) . "',\n";
            $out = "\t\t'" . $this->getUCName() . "' => '" . $this->getDBName() . "',\n";
        }
        return $out;
    }

    /**
     *
     * @return string
     */
    public function dumpSetter()
    {
        if ($this->isOverride()) {
            $out = '';
        } else {
            $fieldType = isset($this->types[$this->type]) ? $this->types[$this->type] : $this->types[self::FIELD_TYPE_DEFAULT];
            $out       = "\n";
            $out       .= "\t/**\n";
            $out       .= "\t * @param " . $fieldType . " \$value\n";
            $out       .= "\t * @return \\" . $this->parent->getProtoName() . "\n";
            $out       .= "\t */\n";
            $out       .= "\tpublic function set" . $this->getUCName() . "(\$value) {\n";
            $filter    = isset($this->filters[$this->type]) ? $this->filters[$this->type] : $this->filters[self::FIELD_TYPE_DEFAULT];
            if ($filter) {
                $out .= "\t\tif (!" . $filter . "Filter::test(\$value)){\n";
                $out .= "\t\t\tthrow new WrongArgumentException('" . $this->parent->getName() . '::$' . $this->getName() . " - wrong value:'.\$value);\n";
                $out .= "\t\t}\n";
            }
            $i = $this->type;
            if ($i === self::FIELD_TYPE_BOOLEAN) {
                $out .= "\t\t\$this->values['" . $this->getUCName() . "'] = (int)(boolean)\$value;\n";
            } else {
                $out .= "\t\t\$this->values['" . $this->getUCName() . "'] = \$value;\n";
            }
            $out .= "\t\treturn \$this;\n";
            $out .= "\t}\n";
        }
        return $out;
    }

    /**
     *
     * @return string
     */
    public function dumpGetter()
    {
        if ($this->isOverride()) {
            $out = '';
        } else {
            $out = "\n";
            $out .= "\tpublic function get" . $this->getUCName() . "() {\n";
            $out .= "\t\treturn \$this->values['" . $this->getUCName() . "'];\n";
            $out .= "\t}\n";
        }
        return $out;
    }

    /**
     *
     * @return string
     */
    public function dumpCreateRow()
    {
        $out = "";
        $dbn = explode(',', $this->getDBName());
        $dbn = array_reverse($dbn);
        foreach ($dbn as $dbName) {
            $out .= "\n";
            $out .= "\t\tif (isset(\$row['" . $dbName . "'])){\n";
            $out .= "\t\t\t\$this->set" . $this->getUCName() . "(\$row['" . $dbName . "']);\n";
            $out .= "\t\t}\n";
        }
        return $out;
    }

}
