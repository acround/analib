<?php

namespace analib\Core\Form\FormField;

/* * *************************************************************************
 *   @author acround
 * ************************************************************************* */

class FormFieldLogical extends BaseFormField
{

    protected string $defaultValue = '';
    protected $trueValue = '1';
    protected $falseValue = '0';

    public function __construct($name)
    {
        parent::__construct($name);
        $this->setFalse();
    }

    /**
     *
     * @param string $name
     * @return FormFieldLogical
     */
    public static function create(string $name): FormFieldLogical
    {
        return new self($name);
    }

    /**
     *
     * @param string $value
     * @return FormFieldLogical
     */
    public function setTrueValue(string $value): FormFieldLogical
    {
        $tmp = $this->value === $this->trueValue;
        $this->trueValue = $value;
        if ($tmp) {
            $this->value = $this->trueValue;
        }
        return $this;
    }

    public function getTrueValue(): int
    {
        return $this->trueValue;
    }

    /**
     *
     * @param string $value
     * @return FormFieldLogical
     */
    public function setFalseValue(string $value): FormFieldLogical
    {
        $tmp = $this->value === $this->falseValue;
        $this->falseValue = $value;
        if ($tmp) {
            $this->value = $this->falseValue;
        }
        return $this;
    }

    public function getFalseValue(): string
    {
        return $this->falseValue;
    }

    /**
     *
     * @param string $value
     * @return FormFieldLogical
     */
    public function setValue(string $value):BaseFormField
    {
        if (
            $value === false ||
            $value === '' ||
            $value === null
        ) {
            $this->setFalse();
        } else {
            $this->setTrue();
        }
        return $this;
    }

    public function getBoolean(): ?bool
    {
        if ($this->value === $this->trueValue) {
            return true;
        } elseif ($this->value === $this->falseValue) {
            return false;
        } else {
            return null;
        }
    }

    /**
     *
     * @return FormFieldLogical
     */
    public function setFalse(): FormFieldLogical
    {
        parent::setValue($this->falseValue);
        return $this;
    }

    /**
     *
     * @return FormFieldLogical
     */
    public function setTrue(): FormFieldLogical
    {
        parent::setValue($this->trueValue);
        return $this;
    }

}
