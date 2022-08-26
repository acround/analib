<?php

namespace analib\Core\Build\Ora;

use analib\Core\Exceptions\ObjectNotFoundException;
use DOMDocument;
use Exception;
use RuntimeException;
use SimpleXMLElement;

/**
 * @author acround
 */
class OraModelBuilder
{

    const DEFAULT_META_NAME = 'ora.xml';
    const CHARSET_IN = 'utf-8';
    const CHARSET_OUT = 'windows-1251';

    private string $meta;
    private string $out;
    private string $xml;
    private bool $ext;
    private array $packages;

    /**
     * @throws ObjectNotFoundException
     * @throws Exception
     */
    public function __construct($pathMeta, $pathOut = null)
    {
        $this->setMeta($pathMeta);
        $this->setOut($pathOut);
    }

    /**
     *
     * @param string $pathMeta
     * @param string $pathOut
     * @return OraModelBuilder
     * @throws ObjectNotFoundException
     */
    public static function create(string $pathMeta, string $pathOut = ''): OraModelBuilder
    {
        return new self($pathMeta, $pathOut);
    }

    /**
     *
     * @param string $pathMeta
     * @return OraModelBuilder
     * @throws ObjectNotFoundException
     * @throws Exception
     */
    public function setMeta(string $pathMeta):OraModelBuilder
    {
        if (!file_exists($pathMeta)) {
            throw new ObjectNotFoundException($pathMeta . ' - metafile not exists');
        }
        $this->meta = $pathMeta;
        $this->xml = file_get_contents($pathMeta);
        if (!$this->out) {
            if (is_dir($pathMeta)) {
                $this->setOut($pathMeta);
            } else {
                $this->setOut(dirname($pathMeta));
            }
        }
        return $this;
    }

    /**
     *
     * @param boolean $ext
     * @return OraModelBuilder
     */
    public function setExt(bool $ext):OraModelBuilder
    {
        $this->ext = $ext;
        return $this;
    }

    /**
     *
     * @param $pathOut
     * @return OraModelBuilder
     * @throws Exception
     */
    public function setOut($pathOut):OraModelBuilder
    {
        if (!file_exists($pathOut) && !mkdir($pathOut) && !is_dir($pathOut)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $pathOut));
        }
        if (!is_dir($pathOut)) {
            throw new Exception($pathOut . ' - is not directory');
        }
        $this->out = $pathOut;
        return $this;
    }

    private function fillProc(OraProcedureBuilder $proc, SimpleXMLElement $param){
        foreach ($param as $prm) {
            $attr = $this->parseSimpleXmlAttributes($prm);
            $name = $attr['name'];
            $class = $attr['class'] ?? '';
            $collection = isset($attr['collection']) && strtolower($attr['collection']) == 'yes';
            $dataType = $attr['datatype'];
            $type = $attr['type'];
            $length = $attr['length'] ?? -1;
            if (!$length)
                $length = -1;
            $proc->addParam($name, $dataType, $type, $length, $class, $collection);
        }

    }

    /**
     *
     * @return OraModelBuilder
     */
    public function build(): OraModelBuilder
    {
        $doc = new DOMDocument('1.0');
        $doc->loadXML($this->xml);
        $xml = simplexml_import_dom($doc);
        if (isset($xml->package)) {
            foreach ($xml->package as $p) {
                $attr = $this->parseSimpleXmlAttributes($p);
                $pName = $attr['name'];
                $title = $attr['title'] ?? null;
                $package = OraPackageBuilder::create($pName, $title)->setExt($this->ext);
                foreach ($p->function as $pr) {
                    $attr = $this->parseSimpleXmlAttributes($pr);
                    $cName = $attr['name'];
                    $cTitle = $attr['title'] ?? null;
                    $cComment = $pr->comment ?? null;
                    $fun = OraProcedureBuilder::create($package, $cName, $cTitle, OraProcedureBuilder::TYPE_FUN)->setComment($cComment);
                    $this->fillProc($fun,$pr->param);
                    $package->addProcedure($fun);
                }
                foreach ($p->procedure as $pr) {
                    $attr = $this->parseSimpleXmlAttributes($pr);
                    $cName = $attr['name'];
                    $cTitle = $attr['title'] ?? null;
                    $cComment = $pr->comment ?? null;
                    $proc = OraProcedureBuilder::create($package, $cName, $cTitle)->setComment($cComment);
                    $this->fillProc($proc,$pr->param);
                    $package->addProcedure($proc);
                }
                $this->packages[$package->getName()] = $package;
            }
        }
        ksort($this->packages);
        return $this;
    }

    /**
     *
     * @return OraModelBuilder
     */
    public function dump(): OraModelBuilder
    {
        /* @var $package OraPackageBuilder */
        foreach ($this->packages as $package) {
            echo 'Package ' . $package->getName() . ":\n";
            $fileOutName = $this->out . DIRECTORY_SEPARATOR . $package->getName() . '.class.php';
            file_put_contents($fileOutName, $package->dump($procList));
            echo "\tProcedure " . implode("\n\tProcedure ", $procList) . "\n";
        }
        return $this;
    }

    /**
     *
     * @param SimpleXMLElement $element
     * @return array
     */
    private function parseSimpleXmlAttributes(SimpleXMLElement $element): array
    {
        $attr = array();
        foreach ($element->attributes() as $k => $v) {
            $attr[$k] = (string)$v;
        }
        return $attr;
    }

}
