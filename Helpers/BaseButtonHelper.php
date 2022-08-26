<?php

namespace analib\Helpers;

/* * *************************************************************************
 *   @author acround
 * ************************************************************************* */

abstract class BaseButtonHelper extends InputHelper
{

    const DEFAULT_VALUE = 'on';

    protected $disabled = false;
    protected $caption  = null;
    protected $onclick  = null;
    protected $value    = null;
    protected $confirm  = null;

    public function __construct()
    {
        parent::__construct();
        $this->setValue(self::DEFAULT_VALUE);
    }

    /**
     *
     * @param boolean $disabled
     * @return BaseButtonHelper
     */
    public function disable($disabled = true)
    {
        $this->disabled = (boolean) $disabled;
        return $this;
    }

    /**
     *
     * @param boolean $disabled
     * @return BaseButtonHelper
     */
    public function enable($enabled = true)
    {
        $this->disabled = !(boolean) $enabled;
        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function isDisable()
    {
        return $this->disabled;
    }

    /**
     *
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     *
     * @param string $caption
     * @return BaseButtonHelper
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getClick()
    {
        return $this->onclick;
    }

    /**
     *
     * @param string $click
     * @return BaseButtonHelper
     */
    public function setClick($click)
    {
        $this->onclick = $click;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     *
     * @param string $value
     * @return BaseButtonHelper
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getConfirm()
    {
        return $this->confirm;
    }

    /**
     *
     * @param string $confirm
     * @return BaseButtonHelper
     */
    public function setConfirm($confirm)
    {
        $this->confirm = $confirm;
        return $this;
    }

    /**
     *
     * @param string $name
     * @param string $value
     * @return BaseButtonHelper
     */
    public function addParam($name, $value)
    {
        switch ($name) {
            case 'confirm':
                $this->setConfirm($value);
                break;
            case 'value':
                $this->setValue($value);
                break;
            case 'onclick':
                $this->setClick($value);
                break;
            default :
                parent::addParam($name, $value);
        }

        return $this;
    }

    public function run()
    {
        $this->setVariable('name', $this->name);
        $this->setVariable('caption', $this->caption);
        $this->setVariable('onclick', $this->onclick);
        parent::run();
    }

}
