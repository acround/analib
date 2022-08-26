<?php

namespace analib\Util;

/* * *************************************************************************
 *   @author acround
 * ************************************************************************* */

class DebugUtils extends StaticFactory
{

    const DEFAULT_SHIFT  = "\t";
    const DEFAULT_RETURN = "\n";

    public static function dump($var)
    {
        echo '<pre class="debugout">';
        if ($var) {
            print_r($var);
        } else {
            var_dump($var);
        }
        echo '</pre>';
    }

    static function dumpFormat($var)
    {
        echo '<pre class="debugout">';
        self::dumpVarFormat($var);
        echo '</pre>';
    }

    static protected function dumpVarFormat($var, $shift = '')
    {
        if (is_array($var)) {
//			echo $shift.'Array ('.  count($var).')';
            echo $shift . 'Array (' . self::DEFAULT_RETURN;
            foreach ($var as $key => $value) {
                echo $shift . self::DEFAULT_SHIFT . '"' . $key . '" => ';
                self::dumpVarFormat($value, $shift . self::DEFAULT_SHIFT);
            }
            echo $shift . ')' . self::DEFAULT_RETURN;
        } elseif (is_object($var)) {
            echo $shift . 'Object {' . self::DEFAULT_RETURN;
            $vars = get_class_vars(get_class($var)) + get_object_vars($var);
            foreach ($vars as $name => $value) {
                echo $shift . self::DEFAULT_SHIFT . '"' . $name . '" => ';
                self::dumpVarFormat($value, $shift . self::DEFAULT_SHIFT);
            }
            echo $shift . '}' . self::DEFAULT_RETURN;
        } elseif (is_bool($var)) {
            if ($var) {
                echo $shift . 'TRUE' . self::DEFAULT_RETURN;
            } else {
                echo $shift . 'FALSE' . self::DEFAULT_RETURN;
            }
        } elseif (is_null($var)) {
            echo $shift . 'NULL' . self::DEFAULT_RETURN;
        } elseif (is_numeric($var)) {
            echo $shift . $var . self::DEFAULT_RETURN;
        } elseif (is_string($var)) {
            echo $shift . $var . self::DEFAULT_RETURN;
        }
    }

    public static function putVisible($value)
    {
        if (is_string($value)) {
            $ret = 'String:' . $value;
        } elseif (is_numeric($value)) {
            $ret = 'Numeric:' . $value;
        } elseif (is_bool($value)) {
            $ret = 'Boolean:' . ($value ? 'TRUE' : 'FALSE');
        } elseif ($value === null) {
            $ret = 'NULL';
        } else {
            $ret = 'Other:' . $value;
        }
        return $ret;
    }

    public static function iterateVisible($object)
    {
        $ret = array();
        if (is_object($object) || is_array($object)) {
            foreach ($object as $key => $value) {
                if (is_object($value)) {
                    $ret[$key] = 'Class: ' . get_class($value);
                } elseif (is_array($value)) {
                    $ret[$key] = self::iterateVisible($value);
                } else {
                    $ret[$key] = self::putVisible($value);
                }
            }
        }
        return $ret;
    }

}
