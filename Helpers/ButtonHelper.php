<?php

namespace analib\Helpers;

/* * *************************************************************************
 *   @author acround
 * ************************************************************************* */

class ButtonHelper extends BaseButtonHelper
{

    const TYPE_DEFAULT    = 0;
    const TYPE_SUBMIT     = 1;
    const TYPE_RESET      = 2;
    const TYPE_BUTTON     = 3;
    const STATUS_DEFAULT  = 0;
    const STATUS_EXCEL    = 1;
    const STATUS_TICK     = 2;
    const STATUS_ERROR    = 3;
    const STATUS_CANCEL   = 4;
    const STATUS_CLOSE    = 5;
    const STATUS_SAVE     = 6;
    const DEFAULT_CONFIRM = 'Are you sure??';

    static $statusList = array(
        self::STATUS_EXCEL  => 'status-excel',
        self::STATUS_TICK   => 'status-tick',
        self::STATUS_ERROR  => 'status-error',
        self::STATUS_CANCEL => 'status-cancel',
        self::STATUS_CLOSE  => 'status-close',
        self::STATUS_SAVE   => 'status-save',
    );
    static $typeList   = array(
        self::TYPE_DEFAULT => 'default',
        self::TYPE_SUBMIT  => 'submit',
        self::TYPE_RESET   => 'reset',
        self::TYPE_BUTTON  => 'button',
    );
    protected $type    = 0;
    protected $skin    = null;
    protected $status  = self::STATUS_DEFAULT;

    /**
     *
     * @param string $name
     * @param string $caption
     * @param int $type
     * @param string $onclick
     * @return ButtonHelper
     */
    public static function create($name, $caption, $type = self::TYPE_SUBMIT, $onclick = '')
    {
        /* @var $out ButtonHelper */
        $out = new self();
        $out->
            setName($name)->
            setCaption($caption)->
            setType($type)->
            setClick($onclick);
        return $out;
    }

    /**
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     *
     * @param int $type
     * @return ButtonHelper
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     *
     * @return int
     */
    public function getSkin()
    {
        return $this->skin;
    }

    /**
     *
     * @param int $skin
     * @return ButtonHelper
     */
    public function setSkin($skin)
    {
        $this->skin = $skin;
        return $this;
    }

    /**
     *
     * @param int $status
     * @return ButtonHelper
     */
    public function setStatus($status)
    {
        if (isset(self::$statusList[$status])) {
            $this->status = $status;
        } else {
            $this->status = self::STATUS_DEFAULT;
        }
        return $this;
    }

    /**
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     *
     * @param string $name
     * @param string $value
     * @return ButtonHelper
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
        $this->setVariable('type', $this->type);
        $this->setVariable('skin', $this->skin);
        parent::run();
    }

}
