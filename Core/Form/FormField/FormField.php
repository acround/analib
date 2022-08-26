<?php

namespace analib\Core\Form\FormField;

/* * *************************************************************************
 *   @author acround
 * ************************************************************************* */

use analib\Util\StaticFactory;

class FormField extends StaticFactory
{

    const FIELD_TYPE_INTEGER = 1;
    const FIELD_TYPE_STRING  = 2;
    const FIELD_TYPE_ARRAY   = 3;

    /**
     *
     * @param string $name
     * @return FormFieldInteger
     */
    public static function integer(string $name): FormFieldInteger
    {
        return new FormFieldInteger($name);
    }

    /**
     *
     * @param string $name
     * @return FormFieldString
     */
    public static function string(string $name): FormFieldString
    {
        return new FormFieldString($name);
    }

    /**
     *
     * @param string $name
     * @return FormFieldArray
     */
    public static function set(string $name): FormFieldArray
    {
        return new FormFieldArray($name);
    }

    /**
     *
     * @param string $name
     * @return FormFieldLogical
     */
    public static function logical(string $name): FormFieldLogical
    {
        return new FormFieldLogical($name);
    }

    /**
     *
     * @param string $name
     * @return FormFieldEnumeration
     */
    public static function enumeration(string $name): FormFieldEnumeration
    {
        return new FormFieldEnumeration($name);
    }

    /**
     *
     * @param string $name
     * @return FormFieldDateTime
     */
    public static function datetime(string $name): FormFieldDateTime
    {
        return FormFieldDateTime::create($name)->setFormat(FormFieldDateTime::FILTER_DATE_TIME_DEFAULT);
    }

    /**
     *
     * @param string $name
     * @return FormFieldDateTime
     */
    public static function date(string $name): FormFieldDateTime
    {
        return FormFieldDateTime::create($name)->setFormat(FormFieldDateTime::FILTER_DATE_DEFAULT);
    }

    /**
     *
     * @param string $name
     * @return FormFieldDateTime
     */
    public static function time(string $name): FormFieldDateTime
    {
        return FormFieldDateTime::create($name)->setFormat(FormFieldDateTime::FILTER_TIME);
    }

}
