<?php

/* * *************************************************************************
 *   @author acround
 * ************************************************************************* */

namespace analib\Core\Xml;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;

class XMLDocument
{

    const DEFAULT_CHARSET = 'utf-8';

    /**
     *
     * @var ?DOMDocument
     */
    protected ?DOMDocument $xml = null;

    public function __construct($fileName = null)
    {
        if ($fileName) {
            $this->loadFromFile($fileName);
        } else {
            $this->emptyDoc();
        }
    }

    public function emptyDoc($charset = self::DEFAULT_CHARSET)
    {
        $this->xml = new DOMDocument('1.0', $charset);
    }

    /**
     * @param string $fileName
     * @return \analib\Core\Xml\XMLDocument
     */
    public function loadFromFile(string $fileName): XMLDocument
    {
        $this->xml = new DOMDocument();
        $this->xml->loadXML(file_get_contents($fileName));
        return $this;
    }

    /**
     *
     * @param string $fileName
     * @return \analib\Core\Xml\XMLDocument
     */
    public function saveToFile(string $fileName): XMLDocument
    {
        $this->xml->save($fileName);
        return $this;
    }

    public function hasNode($path, DOMNode $context = null): bool
    {
        $this->xml->preserveWhiteSpace = false;
        $xp = new DOMXPath($this->xml);
        $list = $xp->query($path, $context);
        return $list->length > 0;
    }

    /**
     *
     * @param string $path
     * @param \DOMNode|null $context
     * @return \DOMNode
     */
    public function getFirstNode(string $path, DOMNode $context = null): ?DOMNode
    {
        $xp = new DOMXPath($this->xml);
        $list = $xp->query($path, $context);

        if ($list->length) {
            $node = $list->item(0);
        } else {
            $node = null;
        }
        return $node;
    }

    /**
     *
     * @param string $path
     * @return array
     */
    public function getNodeArray(string $path): array
    {
        $xp = new DOMXPath($this->xml);
        $list = $xp->query($path);
        $nodes = array();
        for ($i = 0; $i < $list->length; $i++) {
            $nodes[] = $list->item($i);
        }
        return $nodes;
    }

    /**
     *
     * @param DOMNode $node
     * @param string $name
     * @param array $params
     * @return DOMNode
     */
    protected function makeAndAppendChildNode(DOMNode $node, string $name, array $params = array()): DOMNode
    {
        $child = $this->xml->createElement($name);
        if (count($params)) {
            foreach ($params as $key => $value) {
                $attr = $this->xml->createAttribute($key);
                $attr->value = $value;
                $child->appendChild($attr);
            }
        }
        $node->appendChild($child);
        return $child;
    }

    /**
     *
     * @param string $name
     * @param array $params
     * @return DOMNode
     */
    protected function makeNode(string $name, array $params = array()): DOMNode
    {
        $node = $this->xml->createElement($name);
        if (count($params)) {
            foreach ($params as $key => $value) {
                $attr = $this->xml->createAttribute($key);
                $attr->value = $value;
                $node->appendChild($attr);
            }
        }
        return $node;
    }

    /**
     *
     * @param DOMNode $newnode
     * @param DOMNode $refnode
     * @return DOMNode
     */
    public function insertBefore(DOMNode $newnode, DOMNode $refnode): DOMNode
    {
        $refnode->parentNode->insertBefore($newnode, $refnode);
        return $newnode;
    }

    /**
     *
     * @param DOMNode $newnode
     * @param DOMNode $refnode
     * @return DOMNode
     */
    public function insertAfter(DOMNode $newnode, DOMNode $refnode): DOMNode
    {
        if ($refnode->nextSibling) {
            $refnode->parentNode->insertBefore($newnode, $refnode->nextSibling);
        } else {
            $refnode->parentNode->appendChild($newnode);
        }
        return $newnode;
    }

    /**
     *
     * @param DOMNode $newnode
     * @param DOMNode $refnode
     * @return DOMNode
     */
    public function appendChild(DOMNode $newnode, DOMNode $refnode): DOMNode
    {
        $refnode->appendChild($newnode);
        return $newnode;
    }

    /**
     *
     * @param DOMNode $newnode
     * @param DOMNode $refnode
     * @return \DOMNode
     */
    public function prependChild(DOMNode $newnode, DOMNode $refnode): DOMNode
    {
        if ($refnode->hasChildNodes()) {
            $refnode->insertBefore($newnode, $refnode->firstChild);
        } else {
            $refnode->appendChild($newnode);
        }
        return $newnode;
    }

    /**
     *
     * @param DOMNode $node
     * @param string $text
     * @return \analib\Core\Xml\XMLDocument
     */
    protected function makeTextNode(DOMNode $node, string $text): XMLDocument
    {
        $node->
        appendChild(
            $this->xml->createTextNode($text)
        )->
        normalize();
        return $this;
    }

    /**
     *
     * @param DOMNode $node
     * @return \analib\Core\Xml\XMLDocument
     */
    public function clearTag(DOMNode $node): XMLDocument
    {
        if ($node instanceof DOMElement) {
            if ($node->hasChildNodes()) {
                $childs = $node->childNodes;
                for ($i = $childs->length - 1; $i >= 0; $i--) {
                    /* @var $child DOMNode */
                    $child = $childs->item($i);
                    $this->clearTag($child);
                }
            }
            if (!$node->hasAttributes() && !$node->hasChildNodes()) {
                $node->parentNode->removeChild($node);
            }
        }
        return $this;
    }

    public function dump()
    {
        return $this->xml->saveXML(); //$node
    }

    public function setFormatOutput($value)
    {
        $this->xml->formatOutput = (boolean)$value;
    }

}
