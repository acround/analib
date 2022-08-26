<?php

namespace analib\Core\Form\FormField;

/* * *************************************************************************
 *   @author acround
 * ************************************************************************* */

abstract class BaseFormField
{

    const WRONG = 1;
    const MISSING = 2;
    const WRONG_MESSAGE = 'Incorrect field value';
    const MISSING_MESSAGE = 'Required field is empty';
    const DEFAULT_MESSAGE = 'UNKNOWN ERROR';

    protected string $name = '';
    protected string $title = '';
    protected string $raw = '';
    protected string $value = '';
    protected string $defaultValue = '';
    protected bool $required = false;
    protected bool $imported = false;
    protected $error = null;
    protected array $defaultMessages = [
        self::WRONG => self::WRONG_MESSAGE,
        self::MISSING => self::MISSING_MESSAGE,
    ];
    protected array $errorMessages = array();

    public function __construct($name)
    {
        $this->name = $name;
        $this->value = $this->defaultValue;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     *
     * @param string $name
     * @return BaseFormField
     */
    public function setName(string $name): BaseFormField
    {
        $this->name = $name;
        return $this;
    }

    /**
     *
     * @param string $title
     * @return BaseFormField
     */
    public function setTitle(string $title): BaseFormField
    {
        $this->title = $title;
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getRawValue(): string
    {
        return $this->raw;
    }

    public function getFormValue(): ?string
    {
        if (!$this->imported) {
            if ($this->value === null) {
                return null;
            } else {
                return $this->exportValue();
            }
        } else {
            return $this->raw;
        }
    }

    /**
     *
     * @param string $value
     * @return BaseFormField
     */
    public function setValue(string $value): BaseFormField
    {
        $this->value = $value;
        return $this;
    }

    /**
     *
     * @return BaseFormField
     */
    public function dropValue(): BaseFormField
    {
        $this->value = '';
        return $this;
    }

    /**
     *
     * @param string $raw
     * @return BaseFormField
     */
    public function setRawValue(string $raw): BaseFormField
    {
        $this->raw = $raw;
        return $this;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     *
     * @param string $really
     * @return BaseFormField
     */
    public function setRequired($really = true): BaseFormField
    {
        $this->required = !(false === $really);
        return $this;
    }

    protected function checkRequired(): bool
    {
        $error = $this->required &&
            (
                ($this->raw === '') ||
                ($this->raw === null)
            );
        if ($error)
            $this->setError(self::MISSING);
        return !$error;
    }

    /**
     *
     * @return BaseFormField
     */
    public function required(): BaseFormField
    {
//		if (!$this->imported){
//			$this->setError(self::MISSING);
//		}
        $this->required = true;
        return $this;
    }

    /**
     *
     * @return BaseFormField
     */
    public function optional(): BaseFormField
    {
        $this->required = false;
        return $this;
    }

    /**
     *
     * @return BaseFormField
     */
    public function clean(): BaseFormField
    {
        $this->raw = '';
        $this->value = '';
        $this->error = null;
        $this->imported = false;
        return $this;
    }

    public function importValue($value): BaseFormField
    {
        return $this->import(array($this->getName() => $value));
    }

    public function exportValue(): string
    {
        return $this->value;
    }

    public function getError()
    {
        return $this->error;
    }

    /**
     *
     * @param string $error
     * @return BaseFormField
     */
    public function setError(string $error): BaseFormField
    {
        $this->error = $error;
        return $this;
    }

    /**
     *
     * @return BaseFormField
     */
    public function dropError(): BaseFormField
    {
        $this->error = null;
        return $this;
    }

    /**
     *
     * @param string $error
     * @param string $message
     * @return BaseFormField
     */
    public function setErrorMessage(string $error, string $message): BaseFormField
    {
        $this->errorMessages[$error] = $message;
        return $this;
    }

    /**
     *
     * @param string $message
     * @return BaseFormField
     */
    public function setWrongMessage(string $message): BaseFormField
    {
        $this->setErrorMessage(self::WRONG, $message);
        return $this;
    }

    /**
     *
     * @param string $message
     * @return BaseFormField
     */
    public function setMissingMessage(string $message): BaseFormField
    {
        $this->setErrorMessage(self::MISSING, $message);
        return $this;
    }

    public function getErrorMessage()
    {
        if (isset($this->errorMessages[$this->error])) {
            return $this->errorMessages[$this->error];
        } elseif (isset($this->defaultMessages[$this->error])) {
            return ($this->title ? $this->title . ': ' : '') . $this->defaultMessages[$this->error];
        } elseif ($this->error) {
            return self::DEFAULT_MESSAGE;
        } else {
            return null;
        }
    }

    /**
     *
     * @param array $scope
     * @return BaseFormField
     */
    public function import(array $scope): BaseFormField
    {
        if (isset($scope[$this->name])) {
            $this->setRawValue($scope[$this->name]);
            if ($this->checkRequired()) {
                $this->setValue($this->getRawValue());
                $this->imported = true;
                $this->dropError();
            } else {
                $this->clean();
                $this->setError(self::MISSING);
            }
        } else {
            $this->clean();
        }
        return $this;
    }

}
