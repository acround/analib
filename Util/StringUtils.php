<?php

namespace analib\Util;

/**
 * @author acround
 */
class StringUtils extends StaticFactory
{

    const LOCALE_CHARSET = 'utf-8';
    const CHARSET_AJAX   = 'utf-8';

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
            for ($i = 0, $iMax = strlen($namePart); $i < $iMax; $i++) {
                $symbol = substr($namePart, $i, 1);
                if ($i) {
                    $pos = strpos(self::$lowerRuSymbols, $symbol);
                    if ($pos !== false) {
                        $nameN .= $symbol;
                    } else {
                        $pos = strpos(self::$upperRuSymbols, $symbol);
                        if ($pos !== false) {
                            $nameN .= substr(self::$lowerRuSymbols, $pos, 1);
                        } else {
                            $nameN .= $symbol;
                        }
                    }
                } else {
                    $pos = strpos(self::$upperRuSymbols, $symbol);
                    if ($pos !== false) {
                        $nameN .= $symbol;
                    } else {
                        $pos = strpos(self::$lowerRuSymbols, $symbol);
                        if ($pos !== false) {
                            $nameN .= substr(self::$upperRuSymbols, $pos, 1);
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

    public static function camel2c($text)
    {
        $bigLetters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers    = '0123456789';
        $letnums    = $bigLetters . $numbers;
        $flag       = PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE;
        $r          = preg_split('/([A-Z0-9])/', $text, null, $flag);
        $i          = 0;
        $splits     = array();
        while ($i < count($r)) {
            if (strpos($letnums, $r[$i]) === false) {
                $splits[] = $r[$i];
                $i++;
            } else {
                $s = $r[$i++];
                while (($i < count($r)) && (strpos($bigLetters, $r[$i]) === false)) {
                    $s .= $r[$i++];
                }
                $splits[] = strtolower($s);
            }
        }
        return implode('_', $splits);
    }

    public static function c2camel($text, $firstBig = false)
    {
        $r = explode('_', $text);
        $k = $firstBig ? 0 : 1;
        for ($i = $k, $iMax = count($r); $i < $iMax; $i++) {
            $r[$i] = ucfirst($r[$i]);
        }
        return implode('', $r);
    }

    /**
     * Converts a CamelCase name into an ID in lowercase.
     * Words in the ID may be concatenated using the specified character (defaults to '-').
     * For example, 'PostTag' will be converted to 'post-tag'.
     * @param string $name the string to be converted
     * @param string $separator the character used to concatenate the words in the ID
     * @param bool|string $strict whether to insert a separator between two consecutive uppercase chars, defaults to false
     * @return string the resulting ID
     */
    public static function camel2id($name, $separator = '-', $strict = false)
    {
        $regex = $strict ? '/[A-Z]/' : '/(?<![A-Z])[A-Z]/';
        if ($separator === '_') {
            return strtolower(trim(preg_replace($regex, '_\0', $name), '_'));
        }

        return strtolower(trim(str_replace('_', $separator, preg_replace($regex, $separator . '\0', $name)), $separator));
    }

    /**
     * Converts an ID into a CamelCase name.
     * Words in the ID separated by `$separator` (defaults to '-') will be concatenated into a CamelCase name.
     * For example, 'post-tag' is converted to 'PostTag'.
     * @param string $id the ID to be converted
     * @param string $separator the character used to separate the words in the ID
     * @return string the resulting CamelCase name
     */
    public static function id2camel($id, $separator = '-')
    {
        return str_replace(' ', '', ucwords(implode(' ', explode($separator, $id))));
    }

    /**
     * Converts a CamelCase name into space-separated words.
     * For example, 'PostTag' will be converted to 'Post Tag'.
     * @param string $name the string to be converted
     * @param bool $ucwords whether to capitalize the first letter in each word
     * @return string the resulting words
     */
    public static function camel2words($name, $ucwords = true)
    {
        $label = strtolower(trim(str_replace([
            '-',
            '_',
            '.',
                    ], ' ', preg_replace('/(?<![A-Z])[A-Z]/', ' \0', $name))));

        return $ucwords ? ucwords($label) : $label;
    }

    /**
     * Returns given word as CamelCased.
     *
     * Converts a word like "send_email" to "SendEmail". It
     * will remove non alphanumeric character from the word, so
     * "who's online" will be converted to "WhoSOnline".
     * @see variablize()
     * @param string $word the word to CamelCase
     * @return string
     */
    public static function camelize($word)
    {
        return str_replace(' ', '', ucwords(preg_replace('/[^A-Za-z0-9]+/', ' ', $word)));
    }

    /**
     * Converts any "CamelCased" into an "underscored_word".
     * @param string $words the word(s) to underscore
     * @return string
     */
    public static function underscore($words)
    {
        return strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $words));
    }

    /**
     * Same as camelize but first char is in lowercase.
     *
     * Converts a word like "send_email" to "sendEmail". It
     * will remove non alphanumeric character from the word, so
     * "who's online" will be converted to "whoSOnline".
     * @param string $word to lowerCamelCase
     * @return string
     */
    public static function variablize($word)
    {
        $word = static::camelize($word);

        return strtolower($word[0]) . substr($word, 1);
    }

}
