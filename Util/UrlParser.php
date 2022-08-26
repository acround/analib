<?php

namespace analib\Util;

/* * *************************************************************************
 *   Copyright (C) by Arbenyev Alexander                                   *
 *   acround@gmail.com                                                     *
 * ************************************************************************* */
/* $Id: UrlParser.class.php 1497 2011-03-04 15:06:26Z acround $ */

/**
 * Description of UrlParser
 *
 * @author acround
 */
class UrlParser
{

    const DEFAULT_PORT = 80;

    protected string $url;
    protected string $protocol;
    protected string $host;
    protected $port = self::DEFAULT_PORT;
    protected string $path;
    protected $params = array();
    protected string $anchor;

    public function __construct($url = '')
    {
        $this->url = $url;
        if ($url) {
            $tmp = explode('://', $url, 2);
            if (count($tmp) == 2) {
                $this->protocol = array_shift($tmp);
            }
            $tmp = explode('/', $tmp[0], 2);
            $host = explode(':', array_shift($tmp));
            $this->host = array_shift($host);
            if (count($host)) {
                $this->port = array_shift($host);
            }
            if (count($tmp)) {
                $tmp = explode('#', $tmp[0], 2);
                $tmp2 = array_shift($tmp);
                if (count($tmp)) {
                    $this->anchor = $tmp[0];
                }

                $tmp = explode('?', $tmp2, 2);
                $this->path = '/' . array_shift($tmp);
                if (count($tmp)) {
                    $params = explode('&', $tmp[0]);
                    foreach ($params as $param) {
                        $ps = explode('=', $param);
                        if (count($ps) > 1) {
                            $this->params[$ps[0]] = $ps[1];
                        } else {
                            $this->params[$ps[0]] = '';
                        }
                    }
                }
            }
        }
    }

    /**
     *
     * @param string $url
     * @return UrlParser
     */
    public static function create($url = '')
    {
        return new self($url);
    }

    public static function isUrl($text)
    {
        $reg = '/(https?:\/\/)?(www\.)?([-а-яa-z0-9_\.]{2,}\.)(рф|[a-z]{2,6})((\/[-а-яa-z0-9_]{1,})?\/?([a-z0-9_-]{2,}\.[a-z]{2,6})?(\?[a-z0-9_]{2,}=[-0-9]{1,})?((\&[a-z0-9_]{2,}=[-0-9]{1,}){1,})?)/iu';
        return preg_match($reg, $text);
    }

    /**
     *
     * @param string $value
     * @return \UrlParser
     */
    public function setProtocol($value = 'http')
    {
        $this->protocol = $value;
        return $this;
    }

    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     *
     * @param string $value
     * @return \UrlParser
     */
    public function setHost($value = '')
    {
        $this->host = $value;
        return $this;
    }

    public function getHost()
    {
        return $this->host;
    }

    /**
     *
     * @param string $value
     * @return \UrlParser
     */
    public function setPort($value = '')
    {
        $this->port = $value;
        return $this;
    }

    public function getPort()
    {
        return $this->port;
    }

    /**
     *
     * @param string $value
     * @return \UrlParser
     */
    public function setPath($value = '/')
    {
        $this->path = $value;
        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     * @return UrlParser
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    public function getParam($key)
    {
        return $this->params[$key];
    }

    /**
     *
     * @param string $key
     * @param string $value
     * @return UrlParser
     */
    public function setParam($key, $value)
    {
        $this->params[$key] = $value;
        $this->buildUrl();
        return $this;
    }

    /**
     *
     * @param string $key
     * @return UrlParser
     */
    public function dropParam($key)
    {
        if (isset($this->params[$key])) {
            unset($this->params[$key]);
        }
        $this->buildUrl();
        return $this;
    }

    public function getAnchor()
    {
        return $this->anchor;
    }

    public function getUrl()
    {
        $this->buildUrl();
        return $this->url;
    }

    public function getFullUrl()
    {
        $s = $this->path;
        if ($this->params) {
            $p = array();
            foreach ($this->params as $key => $param) {
                $p[] = $key . '=' . $param;
            }
            $s .= '?' . implode('&', $p);
        }
        if ($this->anchor) {
            $s .= '#' . $this->anchor;
        }
        return $s;
    }

    /**
     *
     * @return UrlParser
     */
    protected function buildUrl()
    {
        if ($this->protocol) {
            $s = $this->protocol . '://';
        } else {
            $s = '';
        }
        if ($this->host) {
            $s .= $this->host;
            if ($this->port && ($this->port != self::DEFAULT_PORT)) {
                $s .= ':' . $this->port;
            }
        }
        if ($this->path) {
            $s .= $this->path;
        }
        if ($this->params) {
            $p = array();
            foreach ($this->params as $key => $param) {
                $p[] = $key . '=' . $param;
            }
            $s .= '?' . implode('&', $p);
        }
        if ($this->anchor) {
            $s .= '#' . $this->anchor;
        }
        $this->url = $s;
        return $this;
    }

    public function isLiveJournal()
    {
        $r = preg_match('/[a-z0-9_\-]+\.livejournal\.com/', $this->host);
        return $r;
    }

    public function __toString()
    {
        return $this->getUrl();
    }

}
