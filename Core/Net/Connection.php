<?php

namespace analib\Core\Net;

/**
 * Description of Connection
 *
 * @author aarbenev
 */
class Connection
{

    const TYPE_RAW = 'raw';
    const TYPE_JSON = 'json';

    protected $url = null;
    protected $login = null;
    protected $password = null;
    protected $type = self::TYPE_RAW;
    protected $errorNum = 0;
    protected $errorMessage = '';

    public function __construct($url, $login = null, $password = null)
    {
        $this->url = $url;
        $this->login = $login;
        $this->password = $password;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type = self::TYPE_RAW)
    {
        $this->type = $type;
    }

    /**
     *
     * @return \api\v1\components\Connection
     */
    public static function create()
    {
        return new Connection();
    }

    public function getErrno()
    {
        return $this->errorNum;
    }

    public function getError()
    {
        return $this->errorMessage;
    }

    public function send($area, $function, $params)
    {
        $url = $this->url;
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['User-Agent: customer/' . $this->login],
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTPAUTH => CURLAUTH_ANY,
            CURLOPT_USERPWD => $this->login . ':' . $this->password,
        ];
        if ($params) {
            $body = new \stdClass();
            $body->jsonrpc = '2.0';
            $body->method = $function;
            $body->params = $params;
            $body->id = 1;
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = json_encode($body);
        } else {
            $options[CURLOPT_POST] = false;
        }
        $sender = curl_init();
        curl_setopt_array($sender, $options);
        $result = curl_exec($sender);
        if ($result) {
            switch ($this->type) {
                case self::TYPE_JSON:
                    $resultObj = json_decode($result);
                    return $resultObj;
                default:
                    return $result;
            }
        } else {
            $this->errorNum = curl_errno($sender);
            $this->errorMessage = curl_error($sender);
            $resultObj = false;
        }
        return $resultObj;
    }

}
