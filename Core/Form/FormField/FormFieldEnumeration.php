<?php

namespace analib\Core\Form\FormField;

/* * *************************************************************************
 *   @author acround
 * ************************************************************************* */

class FormFieldEnumeration extends FiltrableFormField
{

    private array $list = [];

    /**
     *
     * @param string $name
     * @return FormFieldEnumeration
     */
    public static function create(string $name): FormFieldEnumeration
    {
        return new self($name);
    }

    /**
     *
     * @param array $list
     * @return FormFieldEnumeration
     */
    public function setList(array $list): FormFieldEnumeration
    {
        $this->list = $list;
        return $this;
    }

    /**
     *
     * @param string $item
     * @return FormFieldEnumeration
     */
    public function addToList(string $item): FormFieldEnumeration
    {
        if (!in_array($item, $this->list)) {
            $this->list[] = $item;
        }
        return $this;
    }

    public function getList(): array
    {
        return $this->list;
    }

    /**
     *
     * @return FormFieldEnumeration
     */
    public function clean():BaseFormField
    {
        parent::clean();
        $this->list = array();
        return $this;
    }

    public function filter(): FormFieldEnumeration
    {
        if (count($this->list)) {
            $value = $this->getRawValue();
            $r     = in_array($value, $this->list);
            if ($r) {
                $this->dropError();
            } else {
                $this->setError(self::WRONG);
            }
        } else {
            $this->dropError();
        }
        return $this;
    }

}
