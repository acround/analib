<?php

namespace analib\Util;

/**
 * Консольный ввод-вывод
 *
 * @author acround
 */
class StdIo
{

    /**
     * @param string $request
     */
    public static function put($request = '')
    {
        echo trim($request);
    }

    /**
     * @param string $request
     */
    public static function putLn($request = '')
    {
        echo trim($request) . "\n";
    }

    /**
     * @param string $request
     * @return string
     */
    public static function ask($request = '')
    {
        self::put($request);
        $response = trim(fgets(STDIN));
        return $response;
    }

    /**
     * @param string $request
     * @return string
     */
    public static function askLn($request = '')
    {
        self::putLn($request) . "\n";
        $response = trim(fgets(STDIN));
        return $response;
    }

    /**
     *
     * @param string $request
     * @param array $list
     * @return string
     */
    public static function askChoice($request, array $list)
    {
        $keys   = array_keys($list);
        $values = array_values($list);
        foreach ($values as $n => $choice) {
            self::putLn($n . '. ' . $choice);
        }
        $r = self::ask($request);
        if (isset($keys[$r])) {
            return $keys[$r];
        } else {
            return null;
        }
    }

}
