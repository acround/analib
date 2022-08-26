<?php

/* * *************************************************************************
 *   @author acround
 * ************************************************************************* */

namespace analib\Core\Xml\Fb2;

use analib\Util\FileUtils;
use DOMDocument;
use Exception;
use SimpleXMLElement;
use ZipArchive;

class FB2Informer
{

    const DEFAUILT_ENCODING = 'utf-8';
    const UTF8_ENCODING = 'utf-8';
    const CP1251_ENCODING = 'windows-1251';
    const FILE_TYPE_FB2 = 0;
    const FILE_TYPE_FB2_ZIP = 1;

    /**
     *
     * @var ?\SimpleXMLElement
     */
    protected ?SimpleXMLElement $xml = null;
    protected ?string $text = null;
    protected ?string $fileName = null;
    protected ?string $fileNameZip = null;
    protected int $fileType = self::FILE_TYPE_FB2;
    protected ?string $encoding = null;
    protected ?string $version = null;
    protected string $outEncoding = self::DEFAUILT_ENCODING;

    public function __construct($fileName = null)
    {
        if ($fileName && file_exists($fileName) && is_readable($fileName)) {
            $this->loadFromFile($fileName);
            $this->fileName = $fileName;
        } else {
            $this->emptyDoc();
        }
    }

    /**
     *
     * @param string $fileName
     * @return FB2Informer
     */
    public static function create($fileName = null): FB2Informer
    {
        return new self($fileName);
    }

    public function emptyDoc(): FB2Informer
    {
        $this->xml = null;
        return $this;
    }

    /**
     * @param string $fileName
     * @return FB2Informer
     */
    public function loadFromFile($fileName = null): FB2Informer
    {
        if (!$fileName) {
            $fileName = $this->fileName;
        }
        $this->xml = null;
        if (FileUtils::isFb2File($fileName)) {
            $this->fileType = self::FILE_TYPE_FB2;
            $this->text = file_get_contents($fileName);
            $this->xml = simplexml_load_string(file_get_contents($fileName));
            $doc = new DOMDocument();
            $doc->load($fileName);
            $this->encoding = $doc->encoding;
            $this->version = $doc->xmlVersion;
            $doc = null;
            $this->fileName = $fileName;
        } elseif (FileUtils::isZipFile($fileName)) {
            $this->fileType = self::FILE_TYPE_FB2_ZIP;
            $zip = new ZipArchive();
            $zip->open($fileName);
            if ($zip->numFiles) {
                $this->fileNameZip = $zip->getNameIndex(0);
                $this->text = $zip->getFromIndex(0);
                $this->loadFromText($this->text);
                $this->fileName = $fileName;
            }
        } else {
            echo $fileName . ' - Invalid file type' . "\n";
        }
        return $this;
    }

    public function save($text = null): FB2Informer
    {
        if (!$text) {
            $text = $this->text;
        }
        if ($this->fileName) {
            switch ($this->fileType) {
                case self::FILE_TYPE_FB2:
                    file_put_contents($this->fileName, $text);
                    break;
                case self::FILE_TYPE_FB2_ZIP:
                    $zip = new ZipArchive();
                    $zip->open($this->fileName);
                    $zip->addFromString($this->fileNameZip, $text);
                    $zip->close();
                    break;
                default :
            }
        }
        return $this;
    }

    /**
     * @param string $text
     * @return FB2Informer
     */
    public function loadFromText(string $text): FB2Informer
    {
        $this->xml = null;
        $this->fileName = '';
        $this->text = $text;
        $this->xml = simplexml_load_string($text);
        $doc = new DOMDocument();
        $doc->loadXML($text);
        $this->encoding = $doc->encoding;
        $this->version = $doc->xmlVersion;
        $doc = null;
        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function getEncoding(): ?string
    {
        return $this->encoding;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function getOutEncoding(): string
    {
        return $this->outEncoding;
    }

    public function setOutEncoding($encoding): FB2Informer
    {
        $this->outEncoding = $encoding;
        return $this;
    }

    /**
     *
     * @return FB2TitleInfo
     */
    public function titleInfo(): FB2TitleInfo
    {
        return FB2TitleInfo::create($this->xml);
    }

    /**
     *
     * @return FB2PublishInfo
     */
    public function publishInfo(): FB2PublishInfo
    {
        return FB2PublishInfo::create($this->xml);
    }

    /**
     *
     * @param string $path
     * @return boolean
     */
    public function has(string $path): bool
    {
        if (!$this->xml) {
            return false;
        }
        $ret = true;
        $path = explode('/', $path);
        for ($i = 0, $iMax = count($path); $i < $iMax; $i++) {
            if (!trim($path[$i])) {
                unset($path[$i]);
            }
        }
        $path = array_values($path);
        $element = $this->xml;
        foreach ($path as $tag) {
            if (isset($element->$tag)) {
                $element = $element->$tag;
            } else {
                $ret = false;
                break;
            }
        }
        return $ret;
    }

    public function genres($conversion = false): array
    {
        $ret = array();
        foreach ($this->xml->description->{'title-info'}->genre as $genre) {
            $ret[] = $genre->__toString();
        }
        if ($conversion && (strtolower($this->encoding) == strtolower($this->outEncoding))) {
            for ($i = 0, $iMax = count($ret); $i < $iMax; $i++) {
                $ret[$i] = iconv($this->encoding, $this->outEncoding, $ret[$i]);
            }
        }
        return $ret;
    }

    public function bookTitle($conversion = false)
    {
        $bookTitle = $this->xml->description->{'title-info'}->{'book-title'};
        $ret = trim($bookTitle->__toString());
        if ($conversion && (strtolower($this->encoding) != strtolower($this->outEncoding))) {
            $ret = iconv($this->encoding, $this->outEncoding, $ret);
        }
        return $ret;
    }

    public function authors($conversion = false): array
    {
        /* @var $author FB2Author */
        $ret = array();
        foreach ($this->xml->description->{'title-info'}->author as $author) {
//			list(, $node) = each($author);
            $ret[] = FB2Author::createFromSXML($author);
        }
        if ($conversion && (strtolower($this->encoding) != strtolower($this->outEncoding))) {
            for ($i = 0, $iMax = count($ret); $i < $iMax; $i++) {
                $author = $ret[$i];
                $author->setFirstName(iconv($this->encoding, $this->outEncoding, $author->getFirstName()));
                $author->setMiddleName(iconv($this->encoding, $this->outEncoding, $author->getMiddleName()));
                $author->setLastName(iconv($this->encoding, $this->outEncoding, $author->getLastName()));
                $ret[$i] = $author;
            }
        }
        return $ret;
    }

    /**
     * @return FB2Informer
     */
    public function clearAuthors(): FB2Informer
    {
        if ($this->checkFileName()) {
            $file = $this->text;
            $startAuthor = strpos($file, '<author>');
            while ($startAuthor !== false) {
                $endAuthors = strpos($file, '</author>', $startAuthor) + strlen('</author>');
                $file = substr($file, 0, $startAuthor) . substr($file, $endAuthors);
                $startAuthor = strpos($file, '<author>');
            }
            $this->
            save($file)->
            loadFromFile($this->fileName);
        }
        return $this;
    }

    /**
     * @param FB2Author $author
     * @return FB2Informer
     */
    public function addAuthor(FB2Author $author): FB2Informer
    {
        if ($this->checkFileName()) {
            $file = $this->text;
            $titleInfo = '<title-info>';
            $genres = $this->genres();
            $genresCount = count($genres);
            if ($genresCount) {
                $begin = strpos($file, $titleInfo) + strlen($titleInfo);
            } else {
                $begin = 0;
                for ($i = 1; $i < $genresCount; $i++) {
                    $begin = strpos($file, '</genre>', $begin);
                }
            }

            $s = '<author>';
            if ($author->getFirstName()) {
                $s .= '<first-name>' . $author->getFirstName() . '</first-name>';
            }
            if ($author->getMiddleName()) {
                $s .= '<middle-name>' . $author->getMiddleName() . '</middle-name>';
            }
            if ($author->getLastName()) {
                $s .= '<last-name>' . $author->getLastName() . '</last-name>';
            }
            if ($author->getId()) {
                $s .= '<id>' . $author->getId() . '</id>';
            }
            if ($author->getNickName()) {
                $s .= '<nickname>' . $author->getNickName() . '</nickname>';
            }
            if ($author->getHomePage()) {
                $s .= '<homepage>' . $author->getHomePage() . '</homepage>';
            }
            if ($author->getEmail()) {
                $s .= '<email>' . $author->getEmail() . '</email>';
            }
            $s .= '</author>';
            $file = substr($file, 0, $begin) . $s . substr($file, $begin);
            $this->
            save($file)->
            loadFromFile($this->fileName);
        }
        return $this;
    }

    public function coverName(): ?string
    {
        try {
            $titleInfo = $this->xml->description->{'title-info'};
        } catch (Exception $e) {
            echo $this->fileName . "\n";
            echo $e;
        }
        $coverName = null;
        if (isset($titleInfo->coverpage)) {
            if (isset($titleInfo->coverpage)) {
                if (isset($titleInfo->coverpage->image)) {
                    $image = $titleInfo->coverpage->image;
                    $coverName = trim(ltrim($image->attributes('l', true)['href']->__toString(), '#'));
                }
            }
        }
        return $coverName;
    }

    public function cover()
    {
        try {
            $titleInfo = $this->xml->description->{'title-info'};
        } catch (Exception $e) {
            echo $this->fileName . "\n";
            echo $e;
        }
        $cover = null;
        if (isset($titleInfo->coverpage)) {
            if (isset($titleInfo->coverpage)) {
                if (isset($titleInfo->coverpage->image)) {
                    $image = $titleInfo->coverpage->image;
                    $coverName = trim(ltrim($image->attributes('l', true)['href']->__toString(), '#'));
                    if ($coverName) {
                        foreach ($this->xml->binary as $binary) {
                            $id = trim($binary->attributes()['id']);
                            if ($id == $coverName) {
                                $body = $binary->__toString();
                                $cover = base64_decode($body);
                            }
                        }
                    }
                }
            }
        }
        return $cover;
    }

    public function sequence($conversion = false): FB2Sequence
    {
        try {
            $titleInfo = $this->xml->description->{'title-info'};
        } catch (Exception $e) {
            echo $this->fileName . "\n";
            echo $e;
        }
        if (isset($titleInfo->sequence)) {
            $sequence = $this->xml->description->{'title-info'}->sequence;
        } else {
            $sequence = null;
        }
        $ret = array();
        if ($sequence) {
            foreach ($sequence->attributes() as $attr => $value) {
                $ret[$attr] = $value->__toString();
            }
        }
        if (!isset($ret['name'])) {
            $ret['name'] = null;
        }
        if (!isset($ret['number'])) {
            $ret['number'] = null;
        }
        if ($conversion && (strtolower($this->encoding) != strtolower($this->outEncoding))) {
            for ($i = 0, $iMax = count($ret); $i < $iMax; $i++) {
                $ret[$i] = iconv($this->encoding, $this->outEncoding, $ret[$i]);
            }
        }
        return FB2Sequence::create($ret);
    }

    public function sequencePublish($conversion = false): FB2Sequence
    {
        $sequence = $this->xml->description->{'publish-info'}->sequence;
        $ret = array();
        if ($sequence) {
            foreach ($sequence->attributes() as $attr => $value) {
                $ret[$attr] = $value->__toString();
            }
        }
        if ($conversion && (strtolower($this->encoding) != strtolower($this->outEncoding))) {
            for ($i = 0, $iMax = count($ret); $i < $iMax; $i++) {
                $ret[$i] = iconv($this->encoding, $this->outEncoding, $ret[$i]);
            }
        }
        return FB2Sequence::create($ret);
    }

    /**
     *
     * @param FB2Sequence $sequence
     * @param bool $forceNumber
     * @param string $encoding
     * @return FB2Informer
     */
    public function setSequencePublish(FB2Sequence $sequence, $forceNumber = false, $encoding = 'utf-8'): FB2Informer
    {
        $this->normalyzeSequence();
        if ($this->checkFileName() && $sequence->getName()) {
            $file = $this->text;
            $name = $sequence->getRawName();

            if (strtolower($this->encoding) != strtolower($encoding)) {
                $name = iconv($encoding, $this->encoding, $name);
            }
            $s = '<sequence name="' . $name;
            $s .= (($forceNumber || $sequence->getNumber()) ? '" number="' . ($sequence->getNumber() ?: '') : '');
            $s .= '" />';
            $g = '#<sequence\s*(\s*name=".*"|\s*number=".*")\s*/>#';
            $ts = '<publish-info>';
            $ti = '</publish-info>';
            if ($this->has('description/publish-info')) {
                $start = strpos($file, $ts);
                $stop = strpos($file, $ti) + strlen($ti);
                if (!$start) {
                    $start = strpos($file, '<publish-info');
                    $stop = strpos($file, '>', $start);
                }
            } else {
                $ds = '</document-info>';
                $start = strpos($file, $ds) + strlen($ds);
                $stop = $start;
            }
            $before = substr($file, 0, $start);
            $publish = substr($file, $start, $stop - $start + 1);
            $after = substr($file, $stop + 1);
            if (!$publish) {
                $publish = $ts . $ti;
            }
            if ($this->has('description/publish-info/sequence')) {
                $publish = preg_replace($g, $s, $publish);
            } else {
                $publish = $ts . $s . $ti;
            }
            $file = $before . $publish . $after;
            $this->
            save($file)->
            loadFromFile($this->fileName);
        }
        return $this;
    }

    /**
     *
     * @param array $sequenceList
     * @param bool $forceNumber
     * @param string $encoding
     * @return FB2Informer
     */
    public function setMultySequencePublish(array $sequenceList, $forceNumber = false, $encoding = 'utf-8'): FB2Informer
    {
        $this->normalyzeSequence();
        if ($this->checkFileName()) {
            $this->removePublishSequence();
            $file = $this->text;
            $pie = '</publish-info>';
            if ($this->has('description/publish-info')) {
                $start = strpos($file, $pie);
            } else {
                $ds = '</document-info>';
                $start = strpos($file, $ds) + strlen($ds);
            }
            $s = '';
            foreach ($sequenceList as $sequence) {
                if ($sequence && ($sequence instanceof FB2Sequence)) {
                    $name = $sequence->getName();
                    if (strtolower($this->encoding) != strtolower($encoding)) {
                        $name = iconv($encoding, $this->encoding, $name);
                    }
                    $s = '<sequence name="' . $name . '"';
                    $s .= (($forceNumber || $sequence->getNumber()) ? ' number="' . ($sequence->getNumber() ?: '') : '');
                    $s .= ' />';
                }
            }
            $file = substr($file, 0, $start) . $s . substr($file, $start);
        }
        return $this;
    }

    /**
     *
     * @param $year
     * @return FB2Informer
     */
    public function setPublishYear($year): FB2Informer
    {
        $file = $this->text;
        $ts = '<publish-info>';
        $ti = '</publish-info>';
        $insTag = '<year>' . $year . '</year>';
        if ($this->has('description/publish-info/year')) {
            $publishInfoStart = strpos($file, $ts);
            $start = strpos($file, '<year>', $publishInfoStart);
            $stop = strpos($file, '</year>', $start) + strlen('</city>');
            $insert = $insTag;
        } elseif ($this->has('description/publish-info/city')) {
            $start0 = strpos($file, '<city>', strpos($file, $ts));
            $start = $stop = strpos($file, '</city>', $start0) + strlen('</city>');
            $insert = $insTag;
        } elseif ($this->has('description/publish-info/publisher')) {
            $start0 = strpos($file, '<publisher>', strpos($file, $ts));
            $start = $stop = strpos($file, '</publisher>', $start0) + strlen('</publisher>');
            $insert = $insTag;
        } elseif ($this->has('description/publish-info/book-name')) {
            $start0 = strpos($file, '<book-name>', strpos($file, $ts));
            $start = $stop = strpos($file, '</book-name>', $start0) + strlen('</book-name>');
            $insert = $insTag;
        } elseif ($this->has('description/publish-info')) {
            $start = $stop = strpos($file, $ts) + strlen($ts);
            $insert = $insTag;
        } else {
            $ds = '</document-info>';
            $start = $stop = strpos($file, $ds) + strlen($ds);
            $insert = $ts . $insTag . $ti;
        }
        $before = substr($file, 0, $start);
        $after = substr($file, $stop);
        $file = $before . $insert . $after;
        $this->
        save($file)->
        loadFromFile($this->fileName);
        return $this;
    }

    protected function checkFileName(): bool
    {
        return
            $this->fileName &&
            file_exists($this->fileName) &&
            is_file($this->fileName) &&
            is_writable($this->fileName);
    }

    protected function normalyzeSequence()
    {
        $this->text = str_replace('></sequence>', '/>', $this->text);
        if ($this->checkFileName()) {
            $this->
            save()->
            loadFromFile($this->fileName);
        }
    }

    /**
     *
     * @param string $genres
     * @return FB2Informer
     */
    public function removeGenre($genres = '*'): FB2Informer
    {
        if ($this->checkFileName()) {
            $file = $this->text;
            if (!is_array($genres)) {
                $genres = array($genres);
            }
            if (in_array('*', $genres)) {
                $g = '|\s*<genre>\s*[a-z0-9_]*\s*</genre>|im';
                $file = preg_replace($g, '', $file);
            } else {
                foreach ($genres as $genre) {
                    $g = '|\s*<genre>\s*' . $genre . '\s*</genre>|im';
                    $file = preg_replace($g, '', $file);
                }
            }
            $this->
            save($file)->
            loadFromFile($this->fileName);
        }
        return $this;
    }

    /**
     *
     * @param string $genres
     * @return FB2Informer
     */
    public function addGenre($genres): FB2Informer
    {
        if ($this->checkFileName()) {
            $file = $this->text;
            $titleInfo = '<title-info>';
            if (!is_array($genres)) {
                $genres = array($genres);
            }

            $oldGenres = $this->genres();
            foreach ($genres as $k => $genre) {
                if (in_array($genre, $oldGenres)) {
                    unset($genres[$k]);
                }
            }
            $genres = array_unique($genres);
            sort($genres);
            $genres = array_values($genres);

            $s = array();
            foreach ($genres as $genre) {
                $s[] = '<genre>' . $genre . '</genre>';
            }
            if ($s) {
                $begin = strpos($file, $titleInfo) + strlen($titleInfo);
                $file = substr($file, 0, $begin) . implode("\n", $s) . substr($file, $begin);
                $this->
                save($file)->
                loadFromFile($this->fileName);
            }
        }
        return $this;
    }

    /**
     *
     * @param string $genres
     * @return FB2Informer
     */
    public function replaceGenre($genres): FB2Informer
    {
        if ($this->checkFileName()) {
            $file = $this->text;
            $g = '|\s*<genre>\s*[a-z0-9_]*\s*</genre>|im';
            $file = preg_replace($g, '', $file);
            $titleInfo = '<title-info>';
            if (!is_array($genres)) {
                $genres = array($genres);
            }

            $genres = array_unique($genres);
            sort($genres);
            $genres = array_values($genres);

            $s = array();
            foreach ($genres as $genre) {
                $s[] = '<genre>' . $genre . '</genre>';
            }
            if ($s) {
                $begin = strpos($file, $titleInfo) + strlen($titleInfo);
                $file = substr($file, 0, $begin) . implode("\n", $s) . substr($file, $begin);
            }
            $this->
            save($file)->
            loadFromFile($this->fileName);
        }
        return $this;
    }

    /**
     *
     * @return FB2Informer
     */
    public function removeSequence(): FB2Informer
    {
        $this->normalyzeSequence();
        if ($this->checkFileName()) {
            $file = $this->text;
            $g = '#\s*<sequence\s*(\s*name=".*"|\s*number=".*")\s*/>#';
            $ts = '<title-info>';
            $ti = '</title-info>';
            $start = strpos($file, $ts);
            $stop = strpos($file, $ti);
            $before = substr($file, 0, $start);
            $titleInfo = substr($file, $start, $stop - $start);
            $after = substr($file, $stop);
            $titleInfo = preg_replace($g, '', $titleInfo);
            $file = $before . $titleInfo . $after;
            $this->
            save($file)->
            loadFromFile($this->fileName);
        }
        return $this;
    }

    /**
     *
     * @return FB2Informer
     */
    public function removePublishSequence(): FB2Informer
    {
        $this->normalyzeSequence();
        if ($this->checkFileName()) {
            if ($this->has('description/publish-info')) {
                $file = $this->text;
                $g = '#\s*<sequence\s*(\s*name=".*"|\s*number=".*")\s*/>#';
                $ts = '<publish-info>';
                $ti = '</publish-info>';
                $start = strpos($file, $ts);
                $stop = strpos($file, $ti);
                $before = substr($file, 0, $start);
                $titleInfo = substr($file, $start, $stop - $start);
                $after = substr($file, $stop);
                $titleInfo = preg_replace($g, '', $titleInfo);
                $file = $before . $titleInfo . $after;
                $this->
                save($file)->
                loadFromFile($this->fileName);
            }
        }
        return $this;
    }

    /**
     *
     * @param FB2Sequence $sequence
     * @param bool $forceNumber
     * @param string $encoding
     * @return FB2Informer
     */
    public function setSequence(FB2Sequence $sequence, $forceNumber = false, $encoding = 'utf-8'): FB2Informer
    {
        $this->normalyzeSequence();
        if ($this->checkFileName() && $sequence->getName()) {
            $file = $this->text;
            $name = $sequence->getName();
            if (strtolower($this->encoding) != strtolower($encoding)) {
                $name = iconv($encoding, $this->encoding, $name);
            }
            $s = '<sequence name="' . $name . '"';
            $s .= (($forceNumber || $sequence->getNumber()) ? ' number="' . ($sequence->getNumber() ? $sequence->getNumber() . '"' : '') : '');
            $s .= ' />';
            $g = '#<sequence\s*(\s*name=".*"|\s*number=".*")\s*/>#';
            $ti = '</title-info>';
            $stop = strpos($file, $ti);
            if (preg_match($g, substr($file, 0, $stop))) {
                $file = preg_replace($g, $s, substr($file, 0, $stop)) . substr($file, $stop);
            } else {
                $file = substr($file, 0, $stop) . $s . substr($file, $stop);
            }

            $this->
            save($file)->
            loadFromFile($this->fileName);
        }
        return $this;
    }

    /**
     *
     * @param array $sequenceList
     * @param bool $forceNumber
     * @param string $encoding
     * @return FB2Informer
     */
    public function setMultySequence(array $sequenceList, $forceNumber = false, $encoding = 'utf-8'): FB2Informer
    {
        $this->normalyzeSequence();
        if ($this->checkFileName()) {
            $this->removeSequence();
            $file = $this->text;
            $ti = '</title-info>';
            $stop = strpos($file, $ti);
            $s = '';
            foreach ($sequenceList as $sequence) {
                if ($sequence && ($sequence instanceof FB2Sequence)) {
                    $name = $sequence->getName();
                    if (strtolower($this->encoding) != strtolower($encoding)) {
                        $name = iconv($encoding, $this->encoding, $name);
                    }
                    $s = '<sequence name="' . $name . '"';
                    $s .= (($forceNumber || $sequence->getNumber()) ? ' number="' . ($sequence->getNumber() ?: '') : '');
                    $s .= ' />';
                }
            }
            $file = substr($file, 0, $stop) . $s . substr($file, $stop);
        }
        return $this;
    }

    public function images($collapse = false): array
    {
        $images = array();
        if (isset($this->xml->binary)) {
            foreach ($this->xml->binary as $binary) {
                $params = array();
                if ($binary) {
                    foreach ($binary->attributes() as $attr => $value) {
                        $params[$attr] = $value->__toString();
                    }
                }
                if (isset($params['content-type'])) {
                    $contentType = $params['content-type'];
                    if (strpos($contentType, 'image/') === 0) {
                        $image = $binary->__toString();
                        if ($collapse) {
                            $image = str_replace(array("\n", "\r", ' '), '', $image);
                        }
                        $images[] = array(
                            'name' => $params['id'] ?? 'pic' . count($images),
                            'type' => $contentType,
                            'image' => $image
                        );
                    }
                }
            }
        }
        return $images;
    }

    public function initNew(): FB2Informer
    {
        $fb2 = FB2Document::create()->makeRoot()->initNew();
        $this->xml = simplexml_import_dom($fb2->getFirstNode(FB2Document::PATH_TO_ROOT));
        return $this;
    }

}
