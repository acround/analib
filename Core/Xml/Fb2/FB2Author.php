<?php

/* * *************************************************************************
 *   @author acround
 * ************************************************************************* */

namespace analib\Core\Xml\Fb2;

use DOMDocument;
use DOMElement;
use SimpleXMLElement;

class FB2Author
{

    const TAG_NAME = 'author';
    const COMPARE_EQUALS = 0;
    const COMPARE_NOT_EQUALS = 1;
    const COMPARE_MAY_BY = -1;
    const NODENAME_FIRSTNAME = 'first-name';
    const NODENAME_MIDDLENAME = 'middle-name';
    const NODENAME_LASTNAME = 'last-name';
    const NODENAME_NICKNAME = 'nick-name';
    const NODENAME_HOMEPAGE = 'home-page';
    const NODENAME_EMAIL = 'email';
    const NODENAME_ID = 'id';

    private ?string $firstName = null;
    private ?string $middleName = null;
    private ?string $lastName = null;
    private ?string $nickName = null;
    private ?string $homePage = null;
    private ?string $email = null;
    private ?string $id = null;
    private ?string $encoding = null;

    /**
     *
     * @param null $lastName
     * @param null $firstName
     * @param null $middleName
     * @param string $encoding
     * @return FB2Author
     */
    public static function create($lastName = null, $firstName = null, $middleName = null, $encoding = FB2Informer::DEFAUILT_ENCODING): FB2Author
    {
        $author = new self();
        $author->
        setEncoding($encoding)->
        setLastName($lastName)->
        setFirstName($firstName)->
        setMiddleName($middleName);
        return $author;
    }

    /**
     *
     * @param \SimpleXMLElement $node
     * @param string $encoding
     * @return FB2Author
     */
    public static function createFromSXML(SimpleXMLElement $node, $encoding = FB2Informer::DEFAUILT_ENCODING): FB2Author
    {
        $author = new self();
        $author->
        setEncoding($encoding)->
        setLastName($node->{'last-name'}->__toString())->
        setFirstName($node->{'first-name'}->__toString())->
        setMiddleName($node->{'middle-name'}->__toString())->
        setNickName($node->{'nickname'}->__toString());
        return $author;
    }

    /**
     *
     * @param string $encoding
     * @return FB2Author
     */
    public function setEncoding(string $encoding): FB2Author
    {
        $this->encoding = $encoding;
        return $this;
    }

    public function getEncoding(): ?string
    {
        return $this->encoding;
    }

    protected function protect($text): string
    {
        $r = trim($text);
        return trim($r, '.');
    }

    /**
     *
     * @param string $value
     * @return FB2Author
     */
    public function setFirstName(string $value): FB2Author
    {
        switch ($this->encoding) {
//			case FB2Informer::UTF8_ENCODING:
//				$this->firstName = StringUtilsUtf8::normalyzeName($value);
//				break;
//			case FB2Informer::CP1251_ENCODING:
//				$this->firstName = StringUtils::normalyzeName($value);
//				break;
            default :
                $this->firstName = $value;
                break;
        }
        return $this;
    }

    public function getFirstName($protect = true): ?string
    {
        if ($protect) {
            return $this->protect($this->firstName);
        } else {
            return $this->firstName;
        }
    }

    /**
     *
     * @param string $value
     * @return FB2Author
     */
    public function setMiddleName(string $value): FB2Author
    {
        switch ($this->encoding) {
//			case FB2Informer::UTF8_ENCODING:
//				$this->middleName	 = StringUtilsUtf8::normalyzeName($value);
//				break;
//			case FB2Informer::CP1251_ENCODING:
//				$this->middleName	 = StringUtils::normalyzeName($value);
//				break;
            default :
                $this->middleName = $value;
                break;
        }
        return $this;
    }

    public function getMiddleName($protect = true): ?string
    {
        if ($protect) {
            return $this->protect($this->middleName);
        } else {
            return $this->middleName;
        }
    }

    /**
     *
     * @param string $value
     * @return FB2Author
     */
    public function setLastName(string $value): FB2Author
    {
        switch ($this->encoding) {
//			case FB2Informer::UTF8_ENCODING:
//				$this->lastName	 = StringUtilsUtf8::normalyzeName($value);
//				break;
//			case FB2Informer::CP1251_ENCODING:
//				$this->lastName	 = StringUtils::normalyzeName($value);
//				break;
            default :
                $this->lastName = $value;
                break;
        }
        return $this;
    }

    public function getLastName($protect = true): ?string
    {
        if ($protect) {
            return $this->protect($this->lastName);
        } else {
            return $this->lastName;
        }
    }

    /**
     *
     * @param string $value
     * @return FB2Author
     */
    public function setNickName(string $value): FB2Author
    {
        $this->nickName = $value;
        return $this;
    }

    public function getNickName($protect = true): ?string
    {
        if ($protect) {
            return $this->protect($this->nickName);
        } else {
            return $this->nickName;
        }
    }

    /**
     *
     * @param string $value
     * @return FB2Author
     */
    public function setHomePage(string $value): FB2Author
    {
        $this->homePage = $value;
        return $this;
    }

    public function getHomePage(): ?string
    {
        return $this->homePage;
    }

    /**
     *
     * @param string $value
     * @return FB2Author
     */
    public function setEmail(string $value): FB2Author
    {
        $this->email = $value;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setId(string $id): FB2Author
    {
        $this->id = $id;
        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function __toString()
    {
        $ret = array();
        if ($this->firstName) {
            $ret[] = $this->firstName;
        }
        if ($this->middleName) {
            $ret[] = $this->middleName;
        }
        if ($this->lastName) {
            $ret[] = $this->lastName;
        }
        return implode(' ', $ret);
    }

    public function toString($full = true): string
    {
        $ret = array();
        if ($this->lastName) {
            $ret[] = trim($this->lastName);
        }
        if ($this->firstName) {
            $ret[] = trim($this->firstName);
        }
        if ($full && $this->middleName) {
            $ret[] = trim($this->middleName);
        }
        if (count($ret) == 0) {
            if ($this->nickName) {
                $ret[] = trim($this->nickName);
            }
        }
        return implode(' ', $ret);
    }

    /**
     *
     * @param \DOMDocument $parent
     * @return \DOMElement
     */
    public function toXML(DOMDocument $parent): DOMElement
    {
        $author = $parent->createElement(self::TAG_NAME);
        if ($this->firstName) {
            $firstName = $parent->createElement('first-name');
            $text = $parent->createTextNode($this->firstName);
            $firstName->appendChild($text);
            $author->appendChild($firstName);
        }
        if ($this->middleName) {
            $middleName = $parent->createElement('middle-name');
            $text = $parent->createTextNode($this->middleName);
            $middleName->appendChild($text);
            $author->appendChild($middleName);
        }
        if ($this->lastName) {
            $lastName = $parent->createElement('last-name');
            $text = $parent->createTextNode($this->lastName);
            $lastName->appendChild($text);
            $author->appendChild($lastName);
        }
        if ($this->nickName) {
            $nickName = $parent->createElement('nickname');
            $text = $parent->createTextNode($this->nickName);
            $nickName->appendChild($text);
            $author->appendChild($nickName);
        }
        if ($this->homePage) {
            $homePage = $parent->createElement('home-page');
            $text = $parent->createTextNode($this->homePage);
            $homePage->appendChild($text);
            $author->appendChild($homePage);
        }
        if ($this->email) {
            $email = $parent->createElement('email');
            $text = $parent->createTextNode($this->email);
            $email->appendChild($text);
            $author->appendChild($email);
        }

        return $author;
    }

    public function compare(FB2Author $author): int
    {
        $ret = self::COMPARE_EQUALS;
        if ($this->getFirstName() !== $author->getFirstName()) {
            if (!$this->getFirstName() || !$author->getFirstName()) {
                $ret = self::COMPARE_MAY_BY;
            } else {
                $ret = self::COMPARE_NOT_EQUALS;
            }
        }
        if ($this->getMiddleName() !== $author->getMiddleName()) {
            if (!$this->getMiddleName() || !$author->getMiddleName()) {
                $ret = self::COMPARE_MAY_BY;
            } else {
                $ret = self::COMPARE_NOT_EQUALS;
            }
        }
        if ($this->getLastName() !== $author->getLastName()) {
            if (!$this->getLastName() || !$author->getLastName()) {
                $ret = self::COMPARE_MAY_BY;
            } else {
                $ret = self::COMPARE_NOT_EQUALS;
            }
        }
        return $ret;
    }

}
