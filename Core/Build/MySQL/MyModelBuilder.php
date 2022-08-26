<?php

namespace analib\Core\Build\MySQL;

use analib\Core\Exceptions\FileNotFoundException;
use analib\Core\Exceptions\WrongArgumentException;
use DOMDocument;
use Exception;
use RuntimeException;
use SimpleXMLElement as SimpleXMLElementAlias;

/**
 *
 * @author acround
 */
class MyModelBuilder
{

    const DEFAULT_META_NAME = 'mymeta.xml';
    const CHARSET_IN = 'utf-8';
    const CHARSET_OUT = 'windows-1251';
    const FIELDS_TYPE_ARRAY = 'array';
    const FIELDS_TYPE_FIELD = 'field';

    private $meta;
    private $doProto;
    private $classesOut;
    private $protoOut;
    private $fieldsType;
    private $setget;
    private $rewrite;
    private $suffix = '';
    private $xml;
    private $classes = array();

    /**
     * @param array $params
     * @throws FileNotFoundException
     * @throws WrongArgumentException
     * @throws Exception
     */
    public function __construct(array $params = [])
    {
        if (array_key_exists('meta', $params)) {
            $this->setMeta($params['meta']);
        }
        if (array_key_exists('classes', $params)) {
            $this->setClassesOut($params['classes']);
        }
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
        if ($this->doProto && array_key_exists('proto', $params)) {
            $this->setProtoOut($params['proto']);
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
        if (array_key_exists('rewrite', $params)) {
            if (is_bool($params['rewrite'])) {
                $this->rewrite = $params['rewrite'];
            } elseif (is_numeric($params['rewrite'])) {
                $this->rewrite = (boolean)$params['rewrite'];
            } elseif (strtolower($params['rewrite']) === 'null') {
                $this->rewrite = false;
            } elseif (strtolower($params['rewrite']) === 'false') {
                $this->rewrite = false;
            } elseif (strtolower($params['rewrite']) === 'true') {
                $this->rewrite = true;
            } else {
                $this->rewrite = (boolean)$params['rewrite'];
            }
        }
        if (array_key_exists('suffix', $params)) {
            if ($params['suffix']) {
                $this->suffix = '.' . $params['suffix'] . '.' . 'php';
            } else {
                $this->suffix = '.php';
            }
        }
        $this->checkFields();
    }

    /**
     * @throws FileNotFoundException
     * @throws WrongArgumentException
     * @throws Exception
     */
    private function checkFields()
    {
        if (!$this->meta) {
            $this->setMeta(realpath('./') . DIRECTORY_SEPARATOR . self::DEFAULT_META_NAME);
        }
        if (!$this->classesOut) {
            $this->setClassesOut(realpath('../') . DIRECTORY_SEPARATOR . 'classes');
        }
        if ($this->doProto && !$this->protoOut) {
            $this->setProtoOut(realpath('../') . DIRECTORY_SEPARATOR . 'proto');
        }
        if (!in_array($this->fieldsType, [self::FIELDS_TYPE_ARRAY, self::FIELDS_TYPE_FIELD])) {
            $this->fieldsType = self::FIELDS_TYPE_FIELD;
        }
    }

    public function getFields()
    {
        return [
            'meta' => $this->meta,
            'classes' => $this->classesOut,
            'do_proto' => $this->doProto,
            'proto' => $this->protoOut,
            'fields' => $this->fieldsType,
            'setget' => $this->setget,
            'rewrite' => $this->rewrite,
        ];
    }

    /**
     *
     * @param array $params
     * @return MyModelBuilder
     * @throws FileNotFoundException
     * @throws WrongArgumentException
     */
    public static function create(array $params = [])
    {
        return new self($params);
    }

    /**
     *
     * @param string $pathMeta
     * @return MyModelBuilder
     * @throws FileNotFoundException
     */
    public function setMeta($pathMeta)
    {
        if (!file_exists($pathMeta)) {
            throw new FileNotFoundException($pathMeta . ' - metafile not exists');
        }
        $this->meta = $pathMeta;
        $this->xml = file_get_contents($pathMeta);
        return $this;
    }

    /**
     *
     * @param string $pathOut
     * @return MyModelBuilder
     * @throws Exception
     */
    public function setClassesOut($pathOut)
    {
        if (!file_exists($pathOut) && !mkdir($pathOut) && !is_dir($pathOut)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $pathOut));
        }
        $pathOut = realpath($pathOut);
        if (!$pathOut) {
            throw new FileNotFoundException('Classes directory not exists');
        }
        if (!is_dir($pathOut)) {
            throw new Exception($pathOut . ' - is not directory');
        }
        $this->classesOut = $pathOut;
        return $this;
    }

    /**
     *
     * @param string $path
     * @return MyModelBuilder
     * @throws FileNotFoundException
     * @throws WrongArgumentException
     */
    public function setProtoOut($path)
    {
        if (!file_exists($path) && !mkdir($path) && !is_dir($path)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
        }
        if (!$path) {
            throw new FileNotFoundException('Proto directory not exists');
        }
        if (!is_dir($path)) {
            throw new WrongArgumentException($path . ' - is not directory');
        }
        $this->protoOut = $path;
        return $this;
    }

    /**
     *
     * @return MyModelBuilder
     */
    public function build()
    {
        $doc = new DOMDocument('1.0');
        $doc->loadXML($this->xml);
        $xml = simplexml_import_dom($doc);
        if (isset($xml->class)) {
            foreach ($xml->class as $classXml) {
                $attr = $this->parseSimpleXmlAttributes($classXml);
                $className = $attr['name'];
                $tableName = $attr['table'];
                $tableType = $attr['type'];
                if ($tableType === 'VIEW') {
                    continue;
                }
                $tableCollation = $attr['collation'];
                $title = isset($attr['title']) ? $attr['title'] : null;
                if (strtolower($doc->encoding) !== 'utf-8') {
                    $title = iconv('utf-8', $doc->encoding, $title);
                }
                $class = MyObjectBuilder::create($className, $tableName, $this->getFields())->
                setTitle($title)->
                setType($tableType)->
                setCollation($tableCollation);
                foreach ($classXml->identifier as $pr) {
                    $attr = $this->parseSimpleXmlAttributes($pr);
                    $dbName = isset($attr['dbname']) ? $attr['dbname'] : null;
                    $propname = isset($attr['propname']) ? $attr['propname'] : null;
                    $default = isset($attr['default']) ? $attr['default'] : null;
                    $type = isset($attr['type']) ? $attr['type'] : null;
                    $title = isset($attr['title']) ? $attr['title'] : null;
                    if (strtolower($doc->encoding) !== 'utf-8') {
                        $title = iconv('utf-8', $doc->encoding, $title);
                    }
                    $length = isset($attr['length']) ? $attr['length'] : null;
                    $null = isset($attr['null']) ? $attr['null'] : null;
                    $className = isset($attr['class']) ? $attr['class'] : null;
                    $objectName = isset($attr['object']) ? $attr['object'] : null;
                    $property = MyObjectPropertyBuilder::create($class, $propname, $dbName, $this->getFields())->
                    setClass($className)->
                    setObject($objectName)->
                    setDefault($default)->
                    setLength($length)->
                    setNull($null)->
                    setType($type)->
                    setTitle($title);
                    $class->addProperty($property);
                }
                foreach ($classXml->property as $pr) {
                    $attr = $this->parseSimpleXmlAttributes($pr);
                    $dbName = isset($attr['dbname']) ? $attr['dbname'] : null;
                    $propname = isset($attr['propname']) ? $attr['propname'] : null;
                    $default = isset($attr['default']) ? $attr['default'] : null;
                    $type = isset($attr['type']) ? $attr['type'] : null;
                    $title = isset($attr['title']) ? $attr['title'] : null;
                    if (strtolower($doc->encoding) !== 'utf-8') {
                        $title = iconv('utf-8', $doc->encoding, $title);
                    }
                    $length = isset($attr['length']) ? $attr['length'] : null;
                    $null = isset($attr['null']) ? $attr['null'] : null;
                    $className = isset($attr['class']) ? $attr['class'] : null;
                    $objectName = isset($attr['object']) ? $attr['object'] : null;
                    $property = MyObjectPropertyBuilder::create($class, $propname, $dbName, $this->getFields())->
                    setClass($className)->
                    setObject($objectName)->
                    setDefault($default)->
                    setLength($length)->
                    setNull($null)->
                    setType($type)->
                    setTitle($title);
                    $class->addProperty($property);
                }
                $this->classes[$class->getName()] = $class;
            }
        }
        return $this;
    }

    /**
     *
     * @return MyModelBuilder
     */
    public function dump()
    {
        /* @var $class MyObjectBuilder */
        foreach ($this->classes as $class) {
            if ($this->doProto) {
                /**
                 * Proto
                 */
                echo 'Class ' . MyObjectBuilder::PROTO_NAME_PREFIX . $class->getName() . "\n";
                $fileOutName = $this->protoOut . DIRECTORY_SEPARATOR . $class->getProtoName() . $this->suffix;
                file_put_contents($fileOutName, $class->dumpProto());
            }
            /**
             * Class
             */
            $fileOutName = $this->classesOut . DIRECTORY_SEPARATOR . $class->getName() . $this->suffix;
            if (!file_exists($fileOutName) || $this->rewrite) {
                echo 'Class ' . $class->getName() . "\n";
                file_put_contents($fileOutName, $class->dump());
            }
        }
        return $this;
    }

    /**
     *
     * @param SimpleXMLElementAlias $element
     * @return array
     */
    private function parseSimpleXmlAttributes(SimpleXMLElementAlias $element)
    {
        $attr = array();
        foreach ($element->attributes() as $k => $v) {
            $attr[$k] = (string)$v;
        }
        return $attr;
    }

}
