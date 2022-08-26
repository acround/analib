<?php

namespace analib\Core\Form\FormField;

/* * *************************************************************************
 *   @author acround
 * ************************************************************************* */

abstract class FiltrableFormField extends BaseFormField
{

    abstract protected function filter();

    public function import(array $scope): BaseFormField
    {
        if (isset($scope[$this->name])) {
            $this->raw = $scope[$this->name];
            if ($this->checkRequired()) {
                if (($this->raw !== '') && ($this->raw !== null)) {
                    $this->filter();
                }
                $this->imported = true;
                if (!$this->error) {
                    $this->value = $this->raw;
                }
            } else {
                $this->clean();
                $this->setError(self::MISSING);
            }
        }
        return $this;
    }

}
