<?php

namespace analib\Core\Build\Business;

/* * *************************************************************************
 *   @author acround
 * ************************************************************************* */

use analib\Core\Exceptions\ObjectNotFoundException;
use analib\Core\Exceptions\WrongArgumentException;
use analib\Util\StringUtils;
use DOMDocument;
use RuntimeException;
use SimpleXMLElement;

class BusinessBuilder
{

    const DEFAULT_META_NAME = 'classes.xml';

    private $meta;
    private $businessOut;
    private $protoOut;
    private $xml;
    private $classes = [];

    /**
     * @throws WrongArgumentException
     * @throws ObjectNotFoundException
     */
    public function __construct($pathBusinessOut = null, $pathProtoOut = null, $meta = null)
    {
        $this->setMeta($meta);
        $this->setBusinessOut($pathBusinessOut);
        $this->setProtoOut($pathProtoOut);
    }

    /**
     *
     * @param string $pathBusinessOut
     * @param string $pathProtoOut
     * @param string $meta
     * @return BusinessBuilder
     * @throws ObjectNotFoundException
     * @throws WrongArgumentException
     */
    public static function create($pathBusinessOut = null, $pathProtoOut = null, $meta = null)
    {
        return new self($pathBusinessOut, $pathProtoOut, $meta);
    }

    /**
     *
     * @param string $pathMeta
     * @return BusinessBuilder
     * @throws ObjectNotFoundException
     */
    public function setMeta($pathMeta)
    {
        if (!file_exists($pathMeta)) {
            throw new ObjectNotFoundException($pathMeta . ' - metafile not exists');
        }
        $this->meta = $pathMeta;
        $this->xml = file_get_contents($pathMeta);
        return $this;
    }

    /**
     *
     * @param string $path
     * @return BusinessBuilder
     * @throws WrongArgumentException
     */
    public function setBusinessOut($path)
    {
        if (!file_exists($path) && !mkdir($path) && !is_dir($path)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
        }
        if (!is_dir($path)) {
            throw new WrongArgumentException($path . ' - is not directory');
        }
        $this->businessOut = $path;
        return $this;
    }

    /**
     *
     * @param string $path
     * @return BusinessBuilder
     * @throws WrongArgumentException
     */
    public function setProtoOut($path)
    {
        if (!file_exists($path) && !mkdir($path) && !is_dir($path)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
        }
        if (!is_dir($path)) {
            throw new WrongArgumentException($path . ' - is not directory');
        }
        $this->protoOut = $path;
        return $this;
    }

    /**
     *
     * @return BusinessBuilder
     */
    public function build()
    {
        $doc = new DOMDocument('1.0');
        $doc->loadXML($this->xml);
        $xml = simplexml_import_dom($doc);
        if (isset($xml->class)) {
            foreach ($xml->class as $cl) {
                $attr = $this->parseSimpleXmlAttributes($cl);
                $cName = $attr['name'];
                $cTitle = isset($attr['title']) ? $attr['title'] : null;
                $cExtends = isset($attr['extends']) ? $attr['extends'] : null;
                $class = BusinessClass::create($cName)->
                setTitle($cTitle)->
                setExtends($cExtends);
                foreach ($cl->field as $pr) {
                    $attr = $this->parseSimpleXmlAttributes($pr);
                    $fName = $attr['name'];
                    $fTitle = isset($attr['title']) ? $attr['title'] : null;
                    $fDBNAme = isset($attr['dbname']) ? $attr['dbname'] : strtoupper(StringUtils::camel2c($fName));
                    $fType = isset($attr['type']) ? $attr['type'] : BusinessClassField::FIELD_TYPE_DEFAULT;
                    $fOverride = isset($attr['override']) ? ($attr['override'] === 'yes') : false;
                    $field = BusinessClassField::create($class)->
                    setName($fName)->
                    setTitle($fTitle)->
                    setDBName($fDBNAme)->
                    setType($fType)->
                    setOverride($fOverride);
                    $class->addField($field);
                }
                $this->classes[$class->getName()] = $class;
            }
        }
        return $this;
    }

    /**
     *
     * @return BusinessBuilder
     */
    public function dump()
    {
        /* @var $class BusinessClass */
        foreach ($this->classes as $class) {
            /**
             * Proto
             */
            echo 'Class ' . BusinessClass::PROTO_NAME_PREFIX . $class->getName() . "\n";
            $fileOutName = $this->protoOut . DIRECTORY_SEPARATOR . $class->getProtoName() . '.class.php';
            file_put_contents($fileOutName, $class->dumpProto());
            /**
             * Class
             */
            $fileOutName = $this->businessOut . DIRECTORY_SEPARATOR . $class->getName() . '.class.php';
            if (DEBUG || !file_exists($fileOutName)) {
                echo 'Class ' . $class->getName() . "\n";
                file_put_contents($fileOutName, $class->dump());
            }
            /**
             * Collection
             */
            echo 'Class ' . $class->getName() . BusinessClass::COLLECTION_NAME_SUFFIX . "\n";
            $fileOutName = $this->businessOut . DIRECTORY_SEPARATOR . $class->getCollectionName() . '.class.php';
            file_put_contents($fileOutName, $class->dumpCollection());
        }
        return $this;
    }

    /**
     *
     * @param SimpleXMLElement $element
     * @return array
     */
    private function parseSimpleXmlAttributes(SimpleXMLElement $element)
    {
        $attr = array();
        foreach ($element->attributes() as $k => $v) {
            $attr[$k] = (string)$v;
        }
        return $attr;
    }

}
