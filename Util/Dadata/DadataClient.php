<?php

namespace analib\Util\Dadata;

//namespace analib\Dadata;

class DadataClient
{

    const URL_REST                    = 'https://dadata.ru/api/v2/clean/address';
    const URL_SUGGESTION_ADDRESS_FREE = 'https://dadata.ru/api/v2/suggest/address';
    const URL_SUGGESTION_ADDRESS_PAY  = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address';
    const URL_LOCATION_FREE           = 'https://dadata.ru/api/v2/detectAddressByIp';
    const URL_LOCATION_PAY            = 'http://suggestions.dadata.ru/suggestions/api/4_1/rs/detectAddressByIp';
    const ACROUND_TOKEN               = '485784f9768cd1e3f3025905ea7bfaf824ad3d9e';
    const ACROUND_SECRET              = '2ae5c3dec1155defead8df8400d588357530a2b1';
    const NOSS_TOKEN                  = 'a21aa221d4deb5044ed0b6454cbe970a059f3911';
    const NOSS_SECRET                 = '823342f0a389d0fa13cfd58e6ee80c86620b94d8';
    const CHANNEL_ACROUND             = 0;
    const CHANNEL_NOSS                = 1;

    static private $channel = self::CHANNEL_ACROUND;

    static protected function getChannel()
    {
        switch (self::$channel) {
            case self::CHANNEL_NOSS:
                $token  = self::NOSS_TOKEN;
                $secret = self::NOSS_SECRET;
                break;
            default :
                $token  = self::ACROUND_TOKEN;
                $secret = self::ACROUND_SECRET;
        }
        return array(
            'token'  => $token,
            'secret' => $secret
        );
    }

    public static function dadataAddress($address)
    {
        $query        = new \stdClass();
        $query->query = $address;
        $query->count = 10;
        $channel      = self::getChannel();
        switch (self::$channel) {
            case self::CHANNEL_ACROUND:
                $url = self::URL_SUGGESTION_ADDRESS_FREE;
                break;
            case self::CHANNEL_NOSS:
                $url = self::URL_SUGGESTION_ADDRESS_PAY;
                break;
        }
        $curlCommand = 'curl '
            . '-X POST '
            . '-H "Content-Type: application/json" '
            . '-H "Accept: application/json" '
            . '-H "Authorization: Token ' . $channel['token'] . '" '
            . '-d \'' . json_encode($query) . '\' '
            . $url;
        $result      = exec($curlCommand);
        $resp        = json_decode($result, true);
        return $resp;
    }

    public static function dadataLocation($ip)
    {
        $channel = self::getChannel();
        switch (self::$channel) {
            case self::CHANNEL_ACROUND:
                $url = self::URL_LOCATION_FREE;
                break;
            case self::CHANNEL_NOSS:
                $url = self::URL_LOCATION_PAY;
                break;
        }
        $curlCommand = 'curl '
            . '-X GET '
            . '-H "Accept: application/json" '
            . '-H "Authorization: Token ' . $channel['token'] . '" '
            . $url . '?'
            . 'ip=' . $ip;
        $result      = exec($curlCommand);
        $resp        = json_decode($result, true);
        return $resp;
    }

    public static function dadataClean($address)
    {
        $channel     = self::getChannel();
        $data        = array(
            'structure' => array('ADDRESS'),
            'data'      => array(array($address))
        );
        $curlCommand = 'curl '
            . '-X POST '
            . '-H "Content-Type: application/json" '
            . '-H "Accept: application/json" '
            . '-H "Authorization: Token ' . $channel['token'] . '" '
            . '-H "X-Secret: ' . $channel['secret'] . '" '
            . '-d \'' . json_encode($data) . '\' '
            . self::URL_REST;
        $resp        = exec($curlCommand);
        $result      = json_decode($resp);
        if (is_object($result)) {
            if (isset($result->detail)) {
                $result->error = $result->detail;
            }
            $result = array($result);
        }
        return $result;
    }

}
