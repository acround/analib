<?php

namespace analib\Util;

/* * *************************************************************************
 *   @author acround
 * ************************************************************************* */

class BaseUtils extends StaticFactory
{

    public static function listToString($delim = '')
    {
        $args = func_get_args();
        array_shift($args);
        return implode($delim, $args);
    }

    public static function checkArrayElements(array &$array, array $elements, $defaultValue = null)
    {
        foreach ($elements as $value) {
            if (!isset($array[$value])) {
                $array[$value] = $defaultValue;
            }
        }
    }

    public static function getArrayElement(array $array, $index)
    {
        if (isset($array[$index])) {
            return $array[$index];
        } else {
            return null;
        }
    }

    /**
     *
     * @param array $array
     * @param string $idField
     * @param string $valueField
     * @param int $checked
     */
    public static function array2Li(array $array, $selectedId = null, $valueField = 'NAME', $idField = 'ID')
    {
        $tmp = array();
        foreach ($array as $key => $value) {
            if ($idField) {
                $id      = $value[$idField];
                $caption = $value[$valueField];
            } else {
                $id      = $key;
                $caption = $value;
            }
            $s     = ($id == $selectedId) ? ' selected="selected"' : '';
            $tmp[] = '<option value="' . $id . '"' . $s . '>' . $caption . '</option>';
        }
        return implode('', $tmp);
    }

    public static function isAjaxMode()
    {
        return
            filter_has_var(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH') &&
            (filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH') == 'XMLHttpRequest');
    }

    public static function getErrorByCode($errorCode)
    {
        return null;
    }

    public static function isConsole()
    {
        return PHP_SAPI == 'cli' || (!filter_has_var(INPUT_SERVER, 'DOCUMENT_ROOT') && !filter_has_var(INPUT_SERVER, 'REQUEST_URI'));
    }

}
