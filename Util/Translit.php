<?php

namespace analib\Util;

/**
 * Description of Translit
 *
 * @author acround
 */
class Translit
{

    static protected $translitMap   = array(
        'а' => 'a',
        'б' => 'b',
        'в' => 'w',
        'г' => 'g',
        'д' => 'd',
        'е' => 'e',
        'ё' => 'jo',
        'ж' => 'hz',
        'з' => 'z',
        'и' => 'i',
        'й' => 'j',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'u',
        'ф' => 'f',
        'х' => 'h',
        'ц' => 'c',
        'ч' => 'ch',
        'ш' => 'sh',
        'щ' => 'sch',
        'ъ' => '',
        'ы' => 'y',
        'ь' => '',
        'э' => 'e',
        'ю' => 'ju',
        'я' => 'ja',
        'А' => 'A',
        'Б' => 'B',
        'В' => 'W',
        'Г' => 'G',
        'Д' => 'D',
        'Е' => 'E',
        'Ё' => 'JO',
        'Ж' => 'HZ',
        'З' => 'Z',
        'И' => 'I',
        'Й' => 'J',
        'К' => 'K',
        'Л' => 'L',
        'М' => 'M',
        'Н' => 'N',
        'О' => 'O',
        'П' => 'P',
        'Р' => 'R',
        'С' => 'S',
        'Т' => 'T',
        'У' => 'U',
        'Ф' => 'F',
        'Х' => 'H',
        'Ц' => 'C',
        'Ч' => 'CH',
        'Ш' => 'SH',
        'Щ' => 'SCH',
        'Ъ' => '',
        'Ы' => 'Y',
        'Ь' => '',
        'Э' => 'E',
        'Ю' => 'JU',
        'Я' => 'JA',
    );
    static protected $translitDeny  = array(
        '\'' => '.',
        '"'  => '``',
        '\\' => '.',
        '/'  => '.',
        '|'  => '.',
        '?'  => '',
        ':'  => '-',
    );
    static protected $translitBlank = array(
        ' '  => '_',
        "\n" => '',
        "\r" => '',
        "\t" => '_',
    );
    static protected $translitAllow = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890()!-=+.,[]{}_';

    public static function translite($text)
    {
        $out = '';
        for ($i = 0, $iMax = mb_strlen($text, 'utf-8'); $i < $iMax; $i++) {
            $symbol = mb_substr($text, $i, 1, 'utf-8');
            if (isset(self::$translitMap[$symbol])) {
                $out .= self::$translitMap[$symbol];
            } elseif (mb_strpos(self::$translitAllow, $symbol, null, 'utf-8') !== false) {
                $out .= $symbol;
            }
        }
        $out = trim($out, '_');
        return $out;
    }

    public static function clearDenySymbols($text, $blank = false)
    {
        $out = '';
        for ($i = 0, $iMax = mb_strlen($text, 'utf-8'); $i < $iMax; $i++) {
            $symbol = mb_substr($text, $i, 1, 'utf-8');
            if (isset(self::$translitDeny[$symbol])) {
                $out .= self::$translitDeny[$symbol];
            } elseif ($blank && (isset(self::$translitBlank[$symbol]))) {
                $out .= self::$translitBlank[$symbol];
            } else {
                $out .= $symbol;
            }
        }
        $out = trim($out, '_');
        return $out;
    }

}
