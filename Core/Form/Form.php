<?php

namespace analib\Core\Form;

/* * *************************************************************************
 *   @author acround
 * ************************************************************************* */

use analib\Core\Exceptions\FormException;
use analib\Core\Exceptions\MissingElementException;
use analib\Core\Form\FormField\BaseFormField;

class Form
{

    private array $fields = array();

    /**
     * @return Form
     * */
    public static function create(): Form
    {
        return new self;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     *
     * @param BaseFormField $field
     * @return Form
     * @throws \analib\Core\Exceptions\FormException
     */
    public function add(BaseFormField $field): Form
    {
        $name = $field->getName();
        if (isset($this->fields[$name])) {
            throw new FormException('[' . $name . '] already exists in Form', 1);
        } else {
            $this->fields[$field->getName()] = $field;
        }
        return $this;
    }

    /**
     *
     * @param BaseFormField $field
     * @return Form
     */
    public function set(BaseFormField $field): Form
    {
        $this->fields[$field->getName()] = $field;
        return $this;
    }

    public function exists($name): bool
    {
        return isset($this->fields[$name]);
    }

    /**
     *
     * @param string $name
     * @return Form
     */
    public function drop(string $name /* , ... */): Form
    {
        foreach (func_get_args() as $name) {
            if ($this->exists($name)) {
                unset($this->fields[$name]);
            }
        }
        return $this;
    }

    /**
     *
     * @param string $name
     * @return BaseFormField
     * @throws MissingElementException
     */
    public function get(string $name): BaseFormField
    {
        if ($this->exists($name)) {
            return $this->fields[$name];
        } else {
            throw new MissingElementException("knows nothing about '$name'");
        }
    }

    /**
     * @throws \analib\Core\Exceptions\MissingElementException
     */
    public function getValue($name): string
    {
        return $this->get($name)->getValue();
    }

    /**
     * @param $name
     * @param $value
     * @return Form
     *
     * @throws \analib\Core\Exceptions\MissingElementException
     */
    public function setValue($name, $value): Form
    {
        $this->get($name)->setValue($value);
        return $this;
    }

    /**
     * @throws \analib\Core\Exceptions\MissingElementException
     */
    public function getRawValue($name): string
    {
        return $this->get($name)->getRawValue();
    }

    /**
     * @throws \analib\Core\Exceptions\MissingElementException
     */
    public function getFormValue($name): ?string
    {
        return $this->get($name)->getFormValue();
    }

    public function getNameList()
    {
        return array_keys($this->fields);
    }

    public function getErrors(): array
    {
        $errors = array();

        foreach ($this->fields as $name => $prm)
            if ($error         = $prm->getError())
                $errors[$name] = $error;

        return $errors;
    }

    public function getTextualErrors(): array
    {
        $errors        = array();
        /* @var $prm BaseFormField */
        foreach ($this->fields as $name => $prm)
            if ($prm->getError())
                $errors[$name] = $prm->getErrorMessage();

        return $errors;
    }

    /**
     * @return Form
     * */
    public function dropAllErrors(): Form
    {
        foreach ($this->fields as $prm)
            $prm->dropError();

        return $this;
    }

    /**
     * @throws \analib\Core\Exceptions\MissingElementException
     */
    public function getError($name)
    {
        return $this->get($name)->getError();
    }

    /**
     * @param $name
     * @return Form
     *
     * @throws \analib\Core\Exceptions\MissingElementException
     */
    public function dropError($name): Form
    {
        $this->get($name)->dropError();
        return $this;
    }

    /**
     * @param $name
     * @return Form
     * @throws \analib\Core\Exceptions\MissingElementException
     */
    public function markMissing($name): Form
    {
        return $this->markCustom($name, FormField\BaseFormField::MISSING);
    }

    /**
     * @param $name
     * @return Form
     * @throws \analib\Core\Exceptions\MissingElementException
     */
    public function markWrong($name): Form
    {
        return $this->markCustom($name, FormField\BaseFormField::WRONG);
    }

    /**
     * @param $name
     * @return Form
     *
     * @throws \analib\Core\Exceptions\MissingElementException
     */
    public function markGood($name): Form
    {
        $this->get($name)->dropError();

        return $this;
    }

    /**
     * Set's custom error mark for primitive.
     *
     * @param $name
     * @param $customMark
     * @return Form
     *
     * @throws \analib\Core\Exceptions\MissingElementException
     */
    public function markCustom($name, $customMark): Form
    {
        $this->get($name)->setError($customMark);

        return $this;
    }

    /**
     * @param array $scope
     * @return Form
     */
    public function import(array $scope): Form
    {
        foreach ($this->fields as $prm)
            $this->importField($scope, $prm);

        return $this;
    }

    /**
     * @param array $scope
     * @return Form
     */
    public function importMore(array $scope): Form
    {
        foreach ($this->fields as $prm) {
            if (!$prm->isImported())
                $this->importField($scope, $prm);
        }

        return $this;
    }

    /**
     * @param $fieldName
     * @param array $scope
     * @return Form
     *
     * @throws \analib\Core\Exceptions\MissingElementException
     */
    public function importOne($fieldName, array $scope): Form
    {
        return $this->importField($scope, $this->get($fieldName));
    }

    /**
     * @param $fieldName
     * @param $value
     * @return Form
     *
     * @throws \analib\Core\Exceptions\MissingElementException
     */
    public function importValue($fieldName, $value): Form
    {
        $prm = $this->get($fieldName);
        $prm->importValue($value);
        return $this;
    }

    /**
     * @param $scope
     * @param \analib\Core\Form\FormField\BaseFormField $prm
     * @return Form
     */
    private function importField($scope, BaseFormField $prm): Form
    {
        $prm->import($scope);
        return $this;
    }

    public function getValues(): array
    {
        $out = array();
        /* @var $field BaseFormField */
        foreach ($this->fields as $name => $field) {
            $out[$name] = $field->getValue();
        }
        return $out;
    }

}
