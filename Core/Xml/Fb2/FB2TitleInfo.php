<?php

/**
 *
 * @author acround
 */

namespace analib\Core\Xml\Fb2;

class FB2TitleInfo
{

    private $genres     = array();
    private $authors    = array();
    private $bookTitle  = null;
    private $annotation = null;
    private $keywords   = null;
    private $date       = null;
    private $lang       = null;
    private $srcLang    = null;
    private $translator = array();
    private $sequences  = array();

    public function __construct(\SimpleXMLElement $fb2)
    {
        $this->parse($fb2);
    }

    public static function create(\SimpleXMLElement $fb2)
    {
        return new self($fb2);
    }

    private function parse(\SimpleXMLElement $fb2)
    {
        if (isset($fb2->description->{'title-info'})) {
            $titleInfo = $fb2->description->{'title-info'};

            foreach ($titleInfo->genres as $genre) {
                $this->genres[] = $genre->__toString();
            }
            $this->genres = array_unique($this->genres);

            foreach ($titleInfo->author as $author) {
                $this->authors[] = FB2Author::createFromSXML($author);
            }

            if (isset($titleInfo->{'book-title'})) {
                $this->bookTitle = $titleInfo->{'book-title'}->__toString();
            }

            if (isset($titleInfo->annotation)) {
                $this->annotation = $titleInfo->annotation->__toString();
            }

            if (isset($titleInfo->keywords)) {
                $this->keywords = $titleInfo->keywords->__toString();
            }

            if (isset($titleInfo->date)) {
                $this->date = $titleInfo->date->__toString();
            }

            if (isset($titleInfo->lang)) {
                $this->lang = $titleInfo->lang->__toString();
            }

            if (isset($titleInfo->{'src-lang'})) {
                $this->srcLang = $titleInfo->{'src-lang'}->__toString();
            }

            foreach ($titleInfo->translator as $translator) {
                $this->translator[] = FB2Author::createFromSXML($translator);
            }

            foreach ($titleInfo->sequence as $sequence) {
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

    public function getGenres()
    {
        return $this->genres;
    }

    public function getBookTitle()
    {
        return $this->bookTitle;
    }

    public function getAuthors()
    {
        return $this->authors;
    }

    public function getAnnotation()
    {
        return $this->annotation;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getKeywords()
    {
        return $this->keywords;
    }

    public function getLang()
    {
        return $this->lang;
    }

    public function getSrcLang()
    {
        return $this->srcLang;
    }

    public function getSequences()
    {
        return $this->sequences;
    }

    public function getTranslators()
    {
        return $this->translator;
    }

}
