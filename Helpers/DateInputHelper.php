<?php

namespace analib\Helpers;

/* * *************************************************************************
 *   @author acround
 * ************************************************************************* */

class DateInputHelper extends InputHelper
{

    protected $date = null;

    public function __construct()
    {
        $this->class = array(
            'date_input',
        );
        parent::__construct();
    }

    public static function create($date = null)
    {
        $helper = new self($date);
        return $helper->masked()->noFutureDate();
    }

    /**
     *
     * @param type $date
     * @return DateInputHelper
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    /**
     *
     * @param boolean $key
     * @return DateInputHelper
     */
    public function masked($key = true)
    {
        if ($key) {
            $this->addClass('datemask');
        } else {
            $this->dropClass('datemask');
        }
        return $this;
    }

    public function isMasked()
    {
        return $this->hasClass('datemask');
    }

    /**
     *
     * @param boolean $key
     * @return DateInputHelper
     */
    public function noFutureDate($key = true)
    {
        if ($key) {
            $this->addClass('nofuturedate');
        } else {
            $this->dropClass('nofuturedate');
        }
        return $this;
    }

    public function isNoFutureDate()
    {
        return $this->hasClass('nofuturedate');
    }

    /**
     *
     * @param boolean $key
     * @return DateInputHelper
     */
    public function required($key = true)
    {
        if ($key) {
            $this->addClass('req');
        } else {
            $this->dropClass('req');
        }
        return $this;
    }

    public function isRequired()
    {
        return $this->hasClass('req');
    }

}
