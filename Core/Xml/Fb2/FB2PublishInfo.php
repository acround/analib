<?php

/**
 * Description of FB2Publish
 *
 * @author acround
 */

namespace analib\Core\Xml\Fb2;

use SimpleXMLElement;

class FB2PublishInfo
{

    private $bookName  = null;
    private $publisher = null;
    private $city      = null;
    private $year      = null;
    private $isbn      = null;
    private $sequence  = array();

    public function __construct(SimpleXMLElement $fb2)
    {
        $this->parse($fb2);
    }

    public static function create(SimpleXMLElement $fb2)
    {
        return new self($fb2);
    }

    private function parse(SimpleXMLElement $fb2)
    {
        if (isset($fb2->description->{'publish-info'})) {
            $publishInfo = $fb2->description->{'publish-info'};
            if (isset($publishInfo->{'book-name'})) {
                $this->bookName = $publishInfo->{'book-name'}->__toString();
            }
            if (isset($publishInfo->publisher)) {
                $this->publisher = $publishInfo->publisher->__toString();
            }
            if (isset($publishInfo->city)) {
                $this->city = $publishInfo->city->__toString();
            }
            if (isset($publishInfo->year)) {
                $this->year = $publishInfo->year->__toString();
            }
            if (isset($publishInfo->isbn)) {
                $this->isbn = $publishInfo->isbn->__toString();
            }
            foreach ($publishInfo->sequence as $sequence) {
                $ret = array();
                foreach ($sequence->attributes() as $attr => $value) {
                    $ret[$attr] = $value->__toString();
                }
                $name             = isset($ret['name']) ? $ret['name'] : null;
                $number           = isset($ret['number']) ? $ret['number'] : null;
                $this->sequence[] = FB2Sequence::create(array(
                        'name'   => $name,
                        'number' => $number
                ));
            }
        };
    }

    public function getBookName()
    {
        return $this->bookName;
    }

    public function getPublisher()
    {
        return $this->publisher;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function getIsbn()
    {
        return $this->isbn;
    }

    public function getSequenceList()
    {
        return $this->sequence;
    }

}
