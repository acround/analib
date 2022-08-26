<?php

namespace analib\Core\Form\FormField;

/* * *************************************************************************
 *   @author acround
 * ************************************************************************* */

class FormFieldString extends FiltrableFormField
{

    const FILTER_GPS          = '/^\d\d\d-\d\d\d-\d\d\d[- ]\d\d$/';
    const FILTER_GPS_NOSS     = '/^\d\d\d-\d\d\d-\d\d\d-\d\d$/';
    const FILTER_GPS_STANDARD = '/^\d\d\d-\d\d\d-\d\d\d \d\d$/';
    const FILTER_REGION_ID    = '/^\d\d$/';
    const FILTER_KLADR        = '/^[(\d{13})(\d{17})]$/';

    private $filter = null;

    /**
     *
     * @param string $name
     * @return FormFieldString
     */
    public static function create(string $name): FormFieldString
    {
        return new self($name);
    }

    /**
     *
     * @param string $filter
     * @return FormFieldString
     */
    public function setFilter(string $filter): FormFieldString
    {
        $this->filter = $filter;
        return $this;
    }

    public function getFilter()
    {
        return $this->filter;
    }

    public function filter(): FormFieldString
    {
        if ($this->filter) {
            if (preg_match($this->getFilter(), $this->getRawValue())) {
                $this->dropError();
            } else {
                $this->clean();
                $this->setError(self::WRONG);
            }
        }
        return $this;
    }

}
