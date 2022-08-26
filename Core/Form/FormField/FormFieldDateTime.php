<?php

namespace analib\Core\Form\FormField;

/* * *************************************************************************
 *   @author acround
 * ************************************************************************* */

class FormFieldDateTime extends FiltrableFormField
{

    const FILTER_DATE_RUS                = 'd.m.Y';
    const FILTER_DATE_USA                = 'm-d-Y';
    const FILTER_DATE_INTERNATIONAL      = 'd-m-Y';
    const FILTER_DATE_UK                 = 'd/m/Y';
    const FILTER_DATE_SORTABLE           = 'Y.m.d';
    const FILTER_DATE_DEFAULT            = self::FILTER_DATE_RUS;
    const FILTER_TIME                    = 'H:i:s';
    const FILTER_DATE_TIME_RUS           = 'd.m.Y H:i:s';
    const FILTER_DATE_TIME_USA           = 'm-d-Y H:i:s';
    const FILTER_DATE_TIME_INTERNATIONAL = 'd-m-Y H:i:s';
    const FILTER_DATE_TIME_UK            = 'd/m/Y H:i:s';
    const FILTER_DATE_TIME_SORTABLE      = 'Y.m.d H:i:s';
    const FILTER_DATE_TIME_DEFAULT       = self::FILTER_DATE_TIME_RUS;

    private $format    = null;
    private int $timestamp = 0;

    public function __construct($name)
    {
        parent::__construct($name);
        $this->setFormat(self::FILTER_DATE_TIME_DEFAULT);
    }

    /**
     *
     * @param string $name
     * @return FormFieldDateTime
     */
    public static function create(string $name): FormFieldDateTime
    {
        return new self($name);
    }

    /**
     *
     * @param string $format
     * @return FormFieldDateTime
     */
    public function setFormat(string $format): FormFieldDateTime
    {
        $this->format = $format;
        return $this;
    }

    public function getFormat()
    {
        return $this->format;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     *
     * @return FormFieldDateTime
     */
    public function filter(): FormFieldDateTime
    {
        $error          = false;
        $value          = trim($this->getRawValue());
        $format         = $this->getFormat();
        $timestampArray = array(
            'year'   => 0,
            'month'  => 0,
            'day'    => 0,
            'hour'   => 0,
            'minute' => 0,
            'second' => 0,
        );
        while ((strlen($format) > 0) && (strlen($value) > 0)) {
            $currentF = substr($format, 0, 1);
            $format   = substr($format, 1);
            switch ($currentF) {
                case 'Y':
                    $currentV = substr($value, 0, 4);
                    $value    = substr($value, 4);
                    if (!preg_match('/^\d{4}$/', $currentV)) {
                        $error = true;
                    }
                    $timestampArray['year'] = $currentV;
                    break;
                case 'm':
                    $currentV               = substr($value, 0, 2);
                    $value                  = substr($value, 2);
                    if (preg_match('/^\d{2}$/', $currentV)) {
                        $error = $currentV > 12;
                    } else {
                        $error = true;
                    }
                    $timestampArray['month'] = $currentV;
                    break;
                case 'd':
                    $currentV                = substr($value, 0, 2);
                    $value                   = substr($value, 2);
                    if (preg_match('/^\d{2}$/', $currentV)) {
                        $error = $currentV > 31;
                    } else {
                        $error = true;
                    }
                    $timestampArray['day'] = $currentV;
                    break;
                case 'H':
                    $currentV              = substr($value, 0, 2);
                    $value                 = substr($value, 2);
                    if (preg_match('/^\d{2}$/', $currentV)) {
                        $error = $currentV > 23;
                    } else {
                        $error = true;
                    }
                    $timestampArray['hour'] = $currentV;
                    break;
                case 'i':
                    $currentV               = substr($value, 0, 2);
                    $value                  = substr($value, 2);
                    if (preg_match('/^\d{2}$/', $currentV)) {
                        $error = $currentV > 59;
                    } else {
                        $error = true;
                    }
                    $timestampArray['minute'] = $currentV;
                    break;
                case 's':
                    $currentV                 = substr($value, 0, 2);
                    $value                    = substr($value, 2);
                    if (preg_match('/^\d{2}$/', $currentV)) {
                        $error = $currentV > 59;
                    } else {
                        $error = true;
                    }
                    $timestampArray['second'] = $currentV;
                    break;
                default :
                    $currentV                 = substr($value, 0, 1);
                    $value                    = substr($value, 1);
                    $error                    = $currentF !== $currentV;
            }
            if ($error)
                break;
        }

        if ($error) {
            $this->setError(self::WRONG);
        } else {
            $this->dropError();
        }
        if (!$this->getError()) {
            $this->timestamp = mktime(
                $timestampArray['hour'], $timestampArray['minute'], $timestampArray['second'], $timestampArray['month'], $timestampArray['day'], $timestampArray['year']
            );
            $this->setRawValue(date($this->getFormat(), $this->timestamp));
        }
        return $this;
    }

}
