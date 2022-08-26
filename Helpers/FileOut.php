<?php

namespace analib\Helpers;

/**
 * @author acround
 */
class FileOut extends BaseHelper
{
    /* @var $attachment boolean */

    protected $attachment = true;

    /**
     *
     * @return FileOut
     */
    public static function create($fileBody, $fileName = null, $fileType = null)
    {
        /* @var $out FileOut */
        $out = new self();
        $out->setVariable('body', $fileBody);
        $out->setVariable('name', $fileName);
        $out->setVariable('type', $fileType);
        return $out;
    }

    /**
     *
     * @param boolean $attachment
     * @return FileOut
     */
    public function setAttachment($attachment = true)
    {
        $this->attachment = (boolean) $attachment;
        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

}
