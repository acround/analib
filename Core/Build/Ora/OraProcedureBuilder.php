<?php

namespace analib\Core\Build\Ora;

/**
 * Description of OraProcedureBuilder
 *
 * @author acround
 */
class OraProcedureBuilder
{

    const DATA_TYPE_SCALAR = 'scalar';
    const DATA_TYPE_BLOB = 'blob';
    const DATA_TYPE_CLOB = 'clob';
    const DATA_TYPE_CURSOR = 'cursor';
    const TYPE_IN = 'in';
    const TYPE_OUT = 'out';
    const TYPE_PROC = 0;
    const TYPE_FUN = 1;
    const INDENT_PARAM = "\t\t\t";
    const PARAM_OUT_LINE = '0';
    const PARAM_OUT_VERT = '1';
    const PARAM_OUT = self::PARAM_OUT_LINE;

    /**
     * @var OraPackageBuilder
     */
    protected OraPackageBuilder $package;
    protected int $type = self::TYPE_PROC;
    protected string $name;
    protected string $class;
    protected string $title;
    protected string $comment;
    protected array $params;

    public function __construct(OraPackageBuilder $package, $name, $title = '', $type = self::TYPE_PROC)
    {
        $this->package = $package;
        $this->name = $name;
        $this->title = $title;
        $this->type = $type;
    }

    public static function create(OraPackageBuilder $package, $name, $title = null, $type = self::TYPE_PROC): OraProcedureBuilder
    {
        return new self($package, $name, $title, $type);
    }

    public function addParam($name, $dataType, $type, $length, $class, $collection)
    {
        $this->params[$name] = array(
            'dataType' => $dataType,
            'type' => $type,
            'length' => $length,
            'class' => $class,
            'collection' => $collection,
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setComment($comment): OraProcedureBuilder
    {
        $this->comment = $comment;
        return $this;
    }

    public function dump(): string
    {
        switch ($this->type) {
            case self::TYPE_PROC:
                return $this->dumpProc();
            case self::TYPE_FUN:
                return $this->dumpFun();
        }
        return '';
    }

    protected function dumpProc(): string
    {
        $out = '';
        $paramNames = array();
        $paramNamesSql = array();
        foreach ($this->params as $k => $v) {
            $paramNames[$k] = '$' . $k;
            $paramNamesSql[] = ':' . $k;
            if ($v['type'] == self::TYPE_OUT) {
                $paramNames[$k] = '&' . $paramNames[$k];
            }
            if ($v['class']) {
                if ($v['collection']) {
                    $paramNames[$k] = ucfirst($v['class']) . 'Collection ' . $paramNames[$k] . ' = null';
                } else {
                    $paramNames[$k] = ucfirst($v['class']) . ' ' . $paramNames[$k] . ' = null';
                }
            }
        }
        $out .= "\t/**\n";
        if ($this->title) {
            $out .= "\t * " . iconv(OraModelBuilder::CHARSET_IN, OraModelBuilder::CHARSET_OUT, $this->title) . "\n";
            $out .= "\t *\n";
        }
        if ($this->comment) {
            $comment = iconv(OraModelBuilder::CHARSET_IN, OraModelBuilder::CHARSET_OUT, $this->comment);
            $comment = explode("\n", $comment);
            foreach ($comment as $n => $line) {
                $comment[$n] = rtrim($line);
            }
            $comment = implode("\n\t *", $comment);
            $out .= "\t * " . $comment . "\n";
            $out .= "\t *\n";
        }
        $out .= "\t * @return " . $this->package->getName() . "\n";
        $out .= "\t */\n";
        switch (self::PARAM_OUT) {
            // Параметры линейно
            case self::PARAM_OUT_LINE:
                $out .= "\tpublic function " . $this->name . "(" . implode(', ', $paramNames) . ")";
                break;
            // Параметры в столбик
            case self::PARAM_OUT_VERT:
                $out .= "\tpublic function " . $this->name . "(\n" . self::INDENT_PARAM;
                $out .= implode(",\n" . self::INDENT_PARAM, $paramNames);
                $out .= "\n\t\t)";
                break;
        }
        $out .= " {\n";
        switch (self::PARAM_OUT) {
            // Параметры линейно
            case self::PARAM_OUT_LINE:
                $out .= "\t\t\$sql = 'begin " . $this->package->getName() . "." . $this->name . "(" . implode(', ', $paramNamesSql) . "); end;';\n";
                break;
            // Параметры в столбик
            case self::PARAM_OUT_VERT:
                $out .= "\t\t\$sql = 'begin " . $this->package->getName() . "." . $this->name . "('\n" . self::INDENT_PARAM . ".'";
                $out .= implode(", '\n" . self::INDENT_PARAM . ".'", $paramNamesSql);
                $out .= "'\n\t\t.'); end;';\n";
                break;
        }
        $out .= "\t\t\$this->parse(\$sql);\n";
        $blobsIn = array();
        $blobsOut = array();
        $clobsIn = array();
        $clobsOut = array();
        $classOut = array();
        $collectionOut = array();
        foreach ($this->params as $name => $param) {
            switch ($param['dataType']) {
                case self::DATA_TYPE_SCALAR:
                    $length = ($param['length'] == -1) ? '' : ', ' . $param['length'];
                    $out .= "\t\t\$this->bindScalar(':" . $name . "', $" . $name . $length . ");\n";
                    break;
                case self::DATA_TYPE_BLOB:
                    if ($param['type'] == self::TYPE_OUT) {
                        $out .= "\t\t\$this->bindBlob(':" . $name . "', \$" . $name . "_blob);\n";
                        $blobsOut[$name] = $param;
                    } elseif ($param['type'] == self::TYPE_IN) {
                        $out .= "\t\t\$this->bindBlob(':" . $name . "', \$" . $name . "_blob);\n";
                        $blobsIn[$name] = $param;
                    }
                    break;
                case self::DATA_TYPE_CLOB:
                    if ($param['type'] == self::TYPE_OUT) {
                        $out .= "\t\t\$this->bindClob(':" . $name . "', \$" . $name . "_clob);\n";
                        $clobsOut[$name] = $param;
                    } elseif ($param['type'] == self::TYPE_IN) {
                        $out .= "\t\t\$this->bindClob(':" . $name . "', \$" . $name . "_clob);\n";
                        $clobsIn[$name] = $param;
                    }
                    break;
                case self::DATA_TYPE_CURSOR:
                    if ($param['class']) {
                        $out .= "\t\t\$this->bindCursor(':" . $name . "', \$" . $name . "Cursor);\n";
                        if ($param['collection']) {
                            $collectionOut[$name] = $param;
                        } else {
                            $classOut[$name] = $param;
                        }
                    } else {
                        $out .= "\t\t\$this->bindCursor(':" . $name . "', \$" . $name . ");\n";
                    }
                    break;
                default :
            }
        }
        foreach ($blobsIn as $name => $param) {
            $out .= "\t\t\$" . $name . "_blob->writeTemporary($" . $name . ", OCI_TEMP_BLOB);\n";
        }
        foreach ($clobsIn as $name => $param) {
            $out .= "\t\t\$" . $name . "_clob->writeTemporary($" . $name . ", OCI_TEMP_CLOB);\n";
        }
        $out .= "\t\t\$this->execute();\n";
        foreach ($blobsOut as $name => $param) {
            $out .= "\t\tif (\$" . $name . "_blob) {\n";
            $out .= "\t\t\t$" . $name . " = $" . $name . "_blob->load();\n";
            $out .= "\t\t\t$" . $name . "_blob->free();\n";
            $out .= "\t\t} else {\n";
            $out .= "\t\t\t$" . $name . " = null;\n";
            $out .= "\t\t}\n";
        }
        foreach ($clobsOut as $name => $param) {
            $out .= "\t\tif (\$" . $name . "_clob) {\n";
            $out .= "\t\t\t$" . $name . " = $" . $name . "_clob->load();\n";
            $out .= "\t\t\t$" . $name . "_clob->free();\n";
            $out .= "\t\t} else {\n";
            $out .= "\t\t\t$" . $name . " = null;\n";
            $out .= "\t\t}\n";
        }
        foreach ($classOut as $name => $param) {
            $out .= "\t\tif (\$row = \$this->executeCursor(\$" . $name . "Cursor)){\n";
            $out .= "\t\t\t\$" . $name . " = " . ucfirst($param['class']) . "::create()->init(\$row);\n";
            $out .= "\t\t}\n";
        }
        foreach ($collectionOut as $name => $param) {
            $out .= "\t\t$" . $name . " = " . ucfirst($param['class']) . "Collection::create();\n";
            $out .= "\t\tforeach (\$this->executeCursor(\$" . $name . "Cursor) as \$row) {\n";
            $out .= "\t\t\t\$" . $name . "->addObjectFromRow(\$row);\n";
            $out .= "\t\t}\n";
        }
        $out .= "\t\t\$this->free();\n";
        $out .= "\t\treturn \$this;\n";
        $out .= "\t}\n";
        $out .= "\n";
        return $out;
    }

    protected function dumpFun(): string
    {
        $out = '';
        $paramNames = array();
        $paramNamesSql = array();
        foreach ($this->params as $k => $v) {
            $paramNames[$k] = '$' . $k;
            $paramNamesSql[] = ':' . $k;
            if ($v['class']) {
                if ($v['collection']) {
                    $paramNames[$k] = ucfirst($v['class']) . 'Collection ' . $paramNames[$k];
                } else {
                    $paramNames[$k] = ucfirst($v['class']) . ' ' . $paramNames[$k];
                }
            } elseif ($v['type'] == self::TYPE_OUT) {
                $paramNames[$k] = '&' . $paramNames[$k];
            }
        }
        $out .= "\t/**\n";
        if ($this->title) {
            $out .= "\t * " . iconv(OraModelBuilder::CHARSET_IN, OraModelBuilder::CHARSET_OUT, $this->title) . "\n";
            $out .= "\t *\n";
        }

        $out .= "\t */\n";
        switch (self::PARAM_OUT) {
            // Параметры линейно
            case self::PARAM_OUT_LINE:
                $out .= "\tpublic function " . $this->name . "(" . implode(', ', $paramNames) . ")";
                break;
            // Параметры в столбик
            case self::PARAM_OUT_VERT:
                $out .= "\tpublic function " . $this->name . "(\n" . self::INDENT_PARAM;
                $out .= implode(",\n" . self::INDENT_PARAM, $paramNames);
                $out .= "\n\t\t)";
                break;
        }
        $out .= " {\n";
        switch (self::PARAM_OUT) {
            // Параметры линейно
            case self::PARAM_OUT_LINE:
                $out .= "\t\t\$sql = 'begin :" . $this->name . "Result := " . $this->package->getName() . "." . $this->name . "(" . implode(', ', $paramNamesSql) . "); end;';\n";
                break;
            // Параметры в столбик
            case self::PARAM_OUT_VERT:
                $out .= "\t\t\$sql = 'begin :" . $this->name . "Result := " . $this->package->getName() . "." . $this->name . "('\n" . self::INDENT_PARAM . ".'";
                $out .= implode(", '\n" . self::INDENT_PARAM . ".'", $paramNamesSql);
                $out .= "'\n\t\t.'); end;';\n";
                break;
        }
        $out .= "\t\t\$this->parse(\$sql);\n";
        $out .= "\t\t\$this->bindScalar(':" . $this->name . "Result', $" . $this->name . "Result);\n";
        $blobsIn = array();
        $clobsIn = array();
        $classOut = array();
        $collectionOut = array();
        foreach ($this->params as $name => $param) {
            switch ($param['dataType']) {
                case self::DATA_TYPE_SCALAR:
                    $length = ($param['length'] == -1) ? '' : ', ' . $param['length'];
                    $out .= "\t\t\$this->bindScalar(':" . $name . "', $" . $name . $length . ");\n";
                    break;
                case self::DATA_TYPE_BLOB:
                    $out .= "\t\t\$this->bindBlob(':" . $name . "', \$" . $name . "_blob);\n";
                    $blobsIn[$name] = $param;
                    break;
                case self::DATA_TYPE_CLOB:
                    $out .= "\t\t\$this->bindClob(':" . $name . "', \$" . $name . "_clob);\n";
                    $clobsIn[$name] = $param;
                    break;
                case self::DATA_TYPE_CURSOR:
                    if ($param['class']) {
                        $out .= "\t\t\$this->bindCursor(':" . $name . "', \$" . $name . "Cursor);\n";
                        if ($param['collection']) {
                            $collectionOut[$name] = $param;
                        } else {
                            $classOut[$name] = $param;
                        }
                    } else {
                        $out .= "\t\t\$this->bindCursor(':" . $name . "', \$" . $name . ");\n";
                    }
                    break;
                default :
            }
        }
        foreach ($blobsIn as $name => $param) {
            $out .= "\t\t\$" . $name . "_blob->writeTemporary($" . $name . ", OCI_TEMP_BLOB);\n";
        }
        foreach ($clobsIn as $name => $param) {
            $out .= "\t\t\$" . $name . "_clob->writeTemporary($" . $name . ", OCI_TEMP_CLOB);\n";
        }
        $out .= "\t\t\$this->execute();\n";
        $out .= "\t\t\$this->free();\n";
        foreach ($classOut as $name => $param) {
            $out .= "\t\tif ((\$row = \$this->fetchRow(\$" . $name . "Cursor)) !== false) {\n";
            $out .= "\t\t\t\$" . $name . " = " . ucfirst($param['class']) . "::create()->init(\$row);\n";
            $out .= "\t\t}\n";
        }
        foreach ($collectionOut as $name => $param) {
            $out .= "\t\t\$" . $name . " = " . ucfirst($param['class']) . "Collection::create();\n";
            $out .= "\t\twhile ((\$row = \$this->fetchRow(\$" . $name . "Cursor)) !== false) {\n";
            $out .= "\t\t\t\$" . $name . "->addObjectFromRow(\$row);\n";
            $out .= "\t\t}\n";
        }
        $out .= "\t\treturn  $" . $this->name . "Result;\n";
        $out .= "\t}\n";
        $out .= "\n";
        return $out;
    }

}
