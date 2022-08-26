<?php

namespace analib\Core\Form\FormField;

/* * *************************************************************************
 *   @author acround
 * ************************************************************************* */

use analib\Core\Filters\IntegerFilter;

class FormFieldInteger extends FiltrableFormField
{
//	const FILTER_INTEGER = '/^\d+$/';

    /**
     *
     * @param string $name
     * @return FormFieldInteger
     */
    public static function create(string $name): FormFieldInteger
    {
        return new self($name);
    }

    protected function filter(): FormFieldInteger
    {
        $value = $this->getRawValue();
        if (IntegerFilter::test($value)) {
            $this->dropError();
        } else {
            $this->setError(self::WRONG);
        }
        return $this;
    }

}
