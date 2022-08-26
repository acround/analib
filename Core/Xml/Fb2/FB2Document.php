<?php

/* * *************************************************************************
 *   @author acround
 * ************************************************************************* */

namespace analib\Core\Xml\Fb2;

use analib\Core\Xml\XMLDocument;
use DOMDocument;
use DOMElement;
use DOMNode;
use DOMXPath;

class FB2Document extends XMLDocument
{

    const PATH_TO_ROOT         = '//FictionBook';
    const PATH_TO_DESCRIPTION  = '//FictionBook/description';
    const PATH_TO_TITLEINFO    = '//FictionBook/description/title-info';
    const PATH_TO_GENRE        = '//FictionBook/description/title-info/genre';
    const PATH_TO_LANG         = '//FictionBook/description/title-info/lang';
    const PATH_TO_AUTHOR       = '//FictionBook/description/title-info/author';
    const PATH_TO_DOCUMENTINFO = '//FictionBook/description/document-info';
    const PATH_TO_PUBLISHINFO  = '//FictionBook/description/publish-info';

    static protected array $emptyTags = array(
        'br',
        'empty-line',
        'paper-pag-break',
    );

    /**
     *
     * @param string $fileName
     * @return FB2Document
     */
    public static function create($fileName = null): FB2Document
    {
        return new self($fileName);
    }

    public function createNode($nodeName)
    {
        return $this->xml->createElement($nodeName);
    }

    public function addTextNode(DOMNode $parent, $text)
    {
        $textNode = $this->xml->createTextNode($text);
        return $parent->appendChild($textNode);
    }

    public function addNode(DOMNode $parent, $nodeName)
    {
        $newNode = $this->createNode($nodeName);
        return $parent->appendChild($newNode);
    }

    public function makeRoot(): FB2Document
    {
        if (!$this->xml) {
            $this->xml = new DOMDocument('1.0', self::DEFAULT_CHARSET);
        }

        if (!$this->hasNode(self::PATH_TO_ROOT)) {
            $fbParams = array(
                'xmlns'   => 'http://www.gribuser.ru/xml/fictionbook/2.0',
                'xmlns:l' => 'http://www.w3.org/1999/xlink',
            );
            $this->makeAndAppendChildNode($this->xml, 'FictionBook', $fbParams);
        }
        return $this;
    }

    public function initNew(): FB2Document
    {
//		$this->makeRoot();
        $this->makeDescription();
        $root = $this->getFirstNode(self::PATH_TO_ROOT);
        $body = $this->makeAndAppendChildNode($root, 'body');
//		$this->xml->normalize();
//		$this->clearDescription();
        return $this;
    }

    protected function dummy()
    {
        $root = $this->getFirstNode(self::PATH_TO_ROOT);
//				$titleInfo = $this->makeAndAppendChildNode($description, 'title-info');
//					$genre = $this->makeAndAppendChildNode($titleInfo, 'genre');
//					$author = $this->makeAndAppendChildNode($titleInfo, 'author');
//						$this->makeAndAppendChildNode($author, 'first-name');
//						$this->makeAndAppendChildNode($author, 'middle-name');
//						$this->makeAndAppendChildNode($author, 'last-name');
//						$this->makeAndAppendChildNode($author, 'nickname');
//						$this->makeAndAppendChildNode($author, 'home-page');
//						$this->makeAndAppendChildNode($author, 'email');
//						$this->makeAndAppendChildNode($author, 'id');
//					$bookTitle = $this->makeAndAppendChildNode($titleInfo, 'book-title');
//					$annotation = $this->makeAndAppendChildNode($titleInfo, 'annotation');
//					$date = $this->makeAndAppendChildNode($titleInfo, 'date', array('value'=>'00-00-00'));
//					$coverpage = $this->makeAndAppendChildNode($titleInfo, 'coverpage');
//						$image = $this->makeAndAppendChildNode($coverpage, 'image', array('l:href'=>'#cover.jpg'));
//					$lang = $this->makeAndAppendChildNode($titleInfo, 'lang');
//					$srcLang = $this->makeAndAppendChildNode($titleInfo, 'src-lang');
//					$sequence = $this->makeAndAppendChildNode($titleInfo, 'sequence', array('name'=>'Серия', 'number'=>'0'));
        $description = $this->makeAndAppendChildNode($root, 'description');
        $documentInfo = $this->makeAndAppendChildNode($description, 'document-info');
        $author2      = $this->makeAndAppendChildNode($documentInfo, 'author');
        $this->makeAndAppendChildNode($author2, 'nickname');
        $this->makeAndAppendChildNode($author2, 'email');
        $programUsed  = $this->makeAndAppendChildNode($documentInfo, 'program-used');
        $date         = $this->makeAndAppendChildNode($documentInfo, 'date', array('value' => '00-00-00'));
        $id           = $this->makeAndAppendChildNode($documentInfo, 'id');
        $version      = $this->makeAndAppendChildNode($documentInfo, 'version');
        $history      = $this->makeAndAppendChildNode($documentInfo, 'history');
        $publishInfo  = $this->makeAndAppendChildNode($description, 'publish-info');
        $bookName     = $this->makeAndAppendChildNode($publishInfo, 'book-name');
        $publisher    = $this->makeAndAppendChildNode($publishInfo, 'publisher');
        $city         = $this->makeAndAppendChildNode($publishInfo, 'city');
        $year         = $this->makeAndAppendChildNode($publishInfo, 'year');
    }

    public function makeDescription(): FB2Document
    {
        $this->makeRoot();

        if (!$this->hasNode(self::PATH_TO_DESCRIPTION)) {
            $root        = $this->getFirstNode(self::PATH_TO_ROOT);
            $description = $this->makeAndAppendChildNode($root, 'description');
        } else {
            $description = $this->getFirstNode(self::PATH_TO_DESCRIPTION);
        }
        if (!$this->hasNode(self::PATH_TO_TITLEINFO)) {
            $titleInfo = $this->prependChild($this->makeNode('title-info'), $description);
        } else {
            $titleInfo = $this->getFirstNode(self::PATH_TO_TITLEINFO);
        }
//        if (!$this->hasNode(self::PATH_TO_TITLEINFO . '/genre')) {
//            $this->prependChild($this->makeNode('genre'), $titleInfo);
//        }
//        if (!$this->hasNode(self::PATH_TO_TITLEINFO . '/author')) {
//            $author = $this->insertAfter($this->makeNode('author'), $this->getFirstNode(self::PATH_TO_TITLEINFO . '/genre'));
//        }
//        if (!$this->hasNode(self::PATH_TO_TITLEINFO . '/author/first-name')) {
//            $this->prependChild($this->makeNode('first-name'), $author);
//        }
//        if (!$this->hasNode(self::PATH_TO_TITLEINFO . '/author/middle-name')) {
//            $this->insertAfter($this->makeNode('middle-name'), $this->getFirstNode(self::PATH_TO_TITLEINFO . '/author/first-name'));
//        }
//        if (!$this->hasNode(self::PATH_TO_TITLEINFO . '/author/last-name')) {
//            $this->insertAfter($this->makeNode('last-name'), $this->getFirstNode(self::PATH_TO_TITLEINFO . '/author/middle-name'));
//        }
//        if (!$this->hasNode(self::PATH_TO_TITLEINFO . '/author/nickname')) {
//            $this->insertAfter($this->makeNode('nickname'), $this->getFirstNode(self::PATH_TO_TITLEINFO . '/author/last-name'));
//        }
//        if (!$this->hasNode(self::PATH_TO_TITLEINFO . '/author/home-page')) {
//            $this->insertAfter($this->makeNode('home-page'), $this->getFirstNode(self::PATH_TO_TITLEINFO . '/author/nickname'));
//        }
//        if (!$this->hasNode(self::PATH_TO_TITLEINFO . '/author/email')) {
//            $this->insertAfter($this->makeNode('email'), $this->getFirstNode(self::PATH_TO_TITLEINFO . '/author/home-page'));
//        }
//        if (!$this->hasNode(self::PATH_TO_TITLEINFO . '/author/id')) {
//            $this->insertAfter($this->makeNode('id'), $this->getFirstNode(self::PATH_TO_TITLEINFO . '/author/email'));
//        }
        if (!$this->hasNode(self::PATH_TO_TITLEINFO . '/book-title')) {
            $bookTitle = $this->makeNode('book-title');
            if ($this->hasNode(self::PATH_TO_TITLEINFO . '/author')) {
                $this->insertAfter($bookTitle, $this->getFirstNode(self::PATH_TO_TITLEINFO . '/author'));
            } elseif ($this->hasNode(self::PATH_TO_TITLEINFO . '/genre')) {
                $this->insertAfter($bookTitle, $this->getFirstNode(self::PATH_TO_TITLEINFO . '/genre'));
            } else {
                $this->prependChild($bookTitle, $titleInfo);
            }
        }
        if (!$this->hasNode(self::PATH_TO_TITLEINFO . '/annotation')) {
            $this->insertAfter($this->makeNode('annotation'), $this->getFirstNode(self::PATH_TO_TITLEINFO . '/book-title'));
        }
        if (!$this->hasNode(self::PATH_TO_TITLEINFO . '/date')) {
            $this->insertAfter($this->makeNode('date', array('value' => '00-00-00')), $this->getFirstNode(self::PATH_TO_TITLEINFO . '/annotation'));
        }
        if (!$this->hasNode(self::PATH_TO_TITLEINFO . '/coverpage')) {
            $coverpage = $this->insertAfter($this->makeNode('coverpage'), $this->getFirstNode(self::PATH_TO_TITLEINFO . '/date'));
        }
        if (!$this->hasNode(self::PATH_TO_TITLEINFO . '/coverpage/image')) {
            $this->appendChild($this->makeNode('image', array('l:href' => '#cover.jpg')), $coverpage);
        }
        if (!$this->hasNode(self::PATH_TO_TITLEINFO . '/lang')) {
            $this->insertAfter($this->makeNode('lang'), $this->getFirstNode(self::PATH_TO_TITLEINFO . '/coverpage'));
        }
        if (!$this->hasNode(self::PATH_TO_TITLEINFO . '/src-lang')) {
            $this->insertAfter($this->makeNode('src-lang'), $this->getFirstNode(self::PATH_TO_TITLEINFO . '/lang'));
        }
        if (!$this->hasNode(self::PATH_TO_TITLEINFO . '/sequence')) {
            $this->insertAfter($this->makeNode('sequence', array('name' => '', 'number' => '')), $this->getFirstNode(self::PATH_TO_TITLEINFO . '/src-lang'));
        }
        if (!$this->hasNode(self::PATH_TO_DOCUMENTINFO)) {
            $this->insertAfter($this->makeNode('document-info'), $this->getFirstNode(self::PATH_TO_TITLEINFO));
        }
        if (!$this->hasNode(self::PATH_TO_PUBLISHINFO)) {
            $this->insertAfter($this->makeNode('publish-info'), $this->getFirstNode(self::PATH_TO_DOCUMENTINFO));
        }
        $this->xml->formatOutput = true;
        return $this;
    }

    /**
     *
     * @param array $nodeList
     * @return FB2Document
     */
    public static function makeFromArray(array $nodeList): FB2Document
    {
        $fb2         = self::create();
        $fb2->makeRoot();
        $root        = $fb2->getFirstNode(self::PATH_TO_ROOT);
        $description = $fb2->makeAndAppendChildNode($root, 'description');
        $titleInfo   = $fb2->prependChild($fb2->makeNode('title-info'), $description);
        if (isset($nodeList['genre'])) {
            foreach ($nodeList['genre'] as $genreName) {
                $genre = $fb2->prependChild($fb2->makeNode('genre'), $titleInfo);
                $fb2->makeTextNode($genre, $genreName);
            }
        }
        if (isset($nodeList['author'])) {
            /* @var $authorName FB2Author */
            foreach ($nodeList['author'] as $authorName) {
                $author = $fb2->appendChild($fb2->makeNode('author'), $titleInfo);
                $fb2->makeTextNode($fb2->appendChild($fb2->makeNode('first-name'), $author), $authorName->getFirstName());
                $fb2->makeTextNode($fb2->appendChild($fb2->makeNode('middle-name'), $author), $authorName->getMiddleName());
                $fb2->makeTextNode($fb2->appendChild($fb2->makeNode('last-name'), $author), $authorName->getLastName());
            }
        }
        $fb2->makeTextNode($fb2->appendChild($fb2->makeNode('book-title'), $titleInfo), $nodeList['title']);
        $fb2->makeTextNode($fb2->appendChild($fb2->makeNode('annotation'), $titleInfo), $nodeList['annotation']);
        $fb2->appendChild($fb2->makeNode('date'), $titleInfo);
        $fb2->makeTextNode($fb2->appendChild($fb2->makeNode('lang'), $titleInfo), $nodeList['lang']);
        if ($nodeList['sequence']) {
            $seq = array('name' => $nodeList['sequence']->getName());
            if ($nodeList['sequence']->getNumber()) {
                $seq['number'] = $nodeList['sequence']->getNumber();
            }
            $fb2->appendChild($fb2->makeNode('sequence', $seq), $titleInfo);
        }
        $fb2->makeTextNode($fb2->appendChild($fb2->makeNode('document-info'), $description), '');
        $fb2->makeTextNode($fb2->appendChild($fb2->makeNode('publish-info'), $description), '');
        $body = $fb2->insertAfter($fb2->makeNode('body'), $description);
        for ($i = 0; $i < $nodeList['sections']; $i++) {
            $fb2->makeTextNode($fb2->appendChild($fb2->makeNode('p'), $fb2->appendChild($fb2->makeNode('title'), $fb2->appendChild($fb2->makeNode('section'), $body))), '');
        }

        $fb2->setFormatOutput(true);
        return $fb2;
    }

    public function removeNodesByPath($path): FB2Document
    {
        $xp   = new DOMXPath($this->xml);
        $list = $xp->query($path);
        if ($list->length) {
            for ($i = 0; $i < $list->length; $i++) {
                $this->clearTag($list->item(0));
            }
        }
        return $this;
    }

    public function getDescription(): ?DOMNode
    {
        $xp          = new DOMXPath($this->xml);
        $description = $xp->query(self::PATH_TO_DESCRIPTION);
        if ($description->length) {
            return $description->item(0);
        }
        return null;
    }

    public function getTitleInfo(): ?DOMNode
    {
        $xp        = new DOMXPath($this->xml);
        $titleInfo = $xp->query(self::PATH_TO_TITLEINFO);
        if ($titleInfo->length) {
            return $titleInfo->item(0);
        }
        return null;
    }

    public function getAuthors(): array
    {
        $xp         = new DOMXPath($this->xml);
        $collection = $xp->query(self::PATH_TO_AUTHOR);
        $ret        = [];
        if ($collection->length) {
            for ($i = 0; $i < $collection->length; $i++) {
                $auhorNode = $collection->item($i);
                $author    = FB2Author::create();
                $childNode = $auhorNode->firstChild;
                while ($childNode) {
                    switch ($childNode->nodeName) {
                        case FB2Author::NODENAME_FIRSTNAME:
                            $author->setFirstName($childNode->textContent);
                            break;
                    }
                    $childNode = $childNode->nextSibling;
                }
                $ret[] = $collection->item($i);
            }
        }
        return $ret;
    }

    public function getGenres(): array
    {
        $xp         = new DOMXPath($this->xml);
        $collection = $xp->query(self::PATH_TO_GENRE);
        $ret        = [];
        if ($collection->length) {
            for ($i = 0; $i < $collection->length; $i++) {
                $ret[] = $collection->item($i)->textContent;
            }
        }
        return $ret;
    }

    public function getPublishInfo(): ?DOMNode
    {
        $xp          = new DOMXPath($this->xml);
        $publishInfo = $xp->query(self::PATH_TO_PUBLISHINFO);
        if ($publishInfo->length) {
            return $publishInfo->item(0);
        }
        return null;
    }

    public function clearDescription(): FB2Document
    {
        $xp          = new DOMXPath($this->xml);
        $description = $xp->query(self::PATH_TO_DESCRIPTION);
        if ($description->length) {
            $description = $description->item(0);
            $this->clearTag($description);
        }
        return $this;
    }

    public function setGenres(array $genres = []): FB2Document
    {
        $this->removeNodesByPath(self::PATH_TO_TITLEINFO . '/genre');
        if ($genres) {
            $titleInfo  = $this->getTitleInfo();
            $genresList = array_reverse($genres);
            foreach ($genresList as $key => $value) {
                $genre = $this->createNode('genre');
                $this->addTextNode($genre, $value);
                $this->prependChild($genre, $titleInfo);
            }
        }
        return $this;
    }

    public function addAuthor(FB2Author $author): FB2Document
    {
        $bookTitle  = $this->getFirstNode(self::PATH_TO_TITLEINFO . '/book-title');
        $authorNode = $this->makeNode('author');
        if ($author->getFirstName()) {
            $aNode = $this->addNode($authorNode, 'first-name');
            $this->addTextNode($aNode, $author->getFirstName());
        }
        if ($author->getMiddleName()) {
            $aNode = $this->addNode($authorNode, 'middle-name');
            $this->addTextNode($aNode, $author->getMiddleName());
        }
        if ($author->getLastName()) {
            $aNode = $this->addNode($authorNode, 'last-name');
            $this->addTextNode($aNode, $author->getLastName());
        }
        if ($author->getNickName()) {
            $aNode = $this->addNode($authorNode, 'last-name');
            $this->addTextNode($aNode, $author->getNickName());
        }
        if ($author->getHomePage()) {
            $aNode = $this->addNode($authorNode, 'homepage');
            $this->addTextNode($aNode, $author->getHomePage());
        }
        $this->insertBefore($authorNode, $bookTitle);
        return $this;
    }

    public function getBookTitle(): ?string
    {
        $bookTitle = $this->getFirstNode(self::PATH_TO_TITLEINFO . '/book-title');
        if ($bookTitle) {
            return $bookTitle->textContent;
        } else {
            return null;
        }
    }

    public function setBookTitle($title): FB2Document
    {
        $bookTitle = $this->getFirstNode(self::PATH_TO_TITLEINFO . '/book-title');
        if ($bookTitle) {
            if ($bookTitle->hasChildNodes()) {
                $child = $bookTitle->firstChild;
                while ($child) {
                    $this->clearTag($child);
                    $child = $bookTitle->firstChild;
                }
            }
            $this->addTextNode($bookTitle, $title);
        } else {
            $bookTitle = $this->createNode('book-info');
            $this->addTextNode($bookTitle, $title);
            $author    = $this->getNodeArray(self::PATH_TO_AUTHOR);
            if ($author) {
                $lastAuthor = $author[count($author) - 1];
                $this->insertAfter($bookTitle, $lastAuthor);
            } else {
                $genre = $this->getNodeArray(self::PATH_TO_GENRE);
                if ($genre) {
                    $lastGenre = $genre[count($genre) - 1];
                    $this->insertAfter($bookTitle, $lastGenre);
                } else {
                    $titleInfo = $this->getFirstNode(self::PATH_TO_TITLEINFO);
                    $this->appendChild($bookTitle, $titleInfo);
                }
            }
        }
        return $this;
    }

    public function getLang()
    {
        $xp   = new DOMXPath($this->xml);
        $lang = $xp->query(self::PATH_TO_LANG);
        if ($lang->length) {
            $text = $lang->item(0)->firstChild->wholeText;
        } else {
            $text = '';
        }
        return $text;
    }

}
