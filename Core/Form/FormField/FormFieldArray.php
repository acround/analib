<?php

namespace analib\Core\Form\FormField;

/* * *************************************************************************
 *   @author acround
 * ************************************************************************* */

class FormFieldArray extends FiltrableFormField
{

    /**
     *
     * @var BaseFormField
     */
    private BaseFormField $proto;

    /**
     *
     * @param string $name
     * @return FormFieldArray
     */
    public static function create(string $name): FormFieldArray
    {
        return new self($name);
    }

    /**
     *
     * @param BaseFormField $proto
     * @return FormFieldArray
     */
    public function setProto(BaseFormField $proto): FormFieldArray
    {
        $this->proto = $proto;
        return $this;
    }

    /**
     *
     * @return BaseFormField
     */
    public function getProto(): BaseFormField
    {
        return $this->proto;
    }

    protected function filter()
    {
        if (is_array($this->raw)) {
            $this->dropError();
            $field = $this->proto;
            if ($field) {
                foreach ($this->raw as $value) {
                    $field->importValue($value);
                    if ($field->getError()) {
                        $this->setError($field->getError());
                        break;
                    }
                }
            }
        } else {
            $this->setError(self::WRONG);
        }
    }

    protected function checkRequired(): bool
    {
        $error = $this->required &&
            (
            !is_array($this->raw) ||
            (count($this->raw) == 0)
            );
        if ($error)
            $this->setError(self::MISSING);
        return !$error;
    }

}
