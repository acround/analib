<?php

namespace analib\Util;

/**
 * @author acround
 */
class StringUtilsUtf8 extends StaticFactory
{

    const LOCALE_CHARSET = 'utf-8';

    static protected $lowerRuSymbols = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюя';
    static protected $upperRuSymbols = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ';

    public static function htmlspecialchars($string)
    {
        return htmlspecialchars($string, ENT_COMPAT, self::LOCALE_CHARSET);
    }

    public static function normalyzeName($name)
    {
        $nameParts = explode('-', $name);
        foreach ($nameParts as $k => $namePart) {
            $namePart = trim($namePart);
            $nameN    = '';
            $len      = mb_strlen($namePart, self::LOCALE_CHARSET);
            for ($i = 0; $i < $len; $i++) {
                $symbol = mb_substr($namePart, $i, 1, self::LOCALE_CHARSET);
                if ($i) {
                    $pos = mb_strpos(self::$lowerRuSymbols, $symbol, 0, self::LOCALE_CHARSET);
                    if ($pos !== false) {
                        $nameN .= $symbol;
                    } else {
                        $pos = mb_strpos(self::$upperRuSymbols, $symbol, 0, self::LOCALE_CHARSET);
                        if ($pos !== false) {
                            $nameN .= mb_substr(self::$lowerRuSymbols, $pos, 1, self::LOCALE_CHARSET);
                        } else {
                            $nameN .= $symbol;
                        }
                    }
                } else {
                    $pos = mb_strpos(self::$upperRuSymbols, $symbol, 0, self::LOCALE_CHARSET);
                    if ($pos !== false) {
                        $nameN .= $symbol;
                    } else {
                        $pos = mb_strpos(self::$lowerRuSymbols, $symbol, 0, self::LOCALE_CHARSET);
                        if ($pos !== false) {
                            $nameN .= mb_substr(self::$upperRuSymbols, $pos, 1, self::LOCALE_CHARSET);
                        } else {
                            $nameN .= $symbol;
                        }
                    }
                }
            }
            $nameParts[$k] = $nameN;
        }
        return implode('-', $nameParts);
    }

}
