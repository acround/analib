<?php

namespace analib\Helpers;

/**
 * Description of NoticePrint
 *
 * @author acround
 */
class NoticePrint extends BaseHelper
{

    const NAME = 'errors';

    /**
     * @return NoticePrint
     */
    public static function create()
    {
        return new self();
    }

    /**
     *
     * @param string $message
     * @param int $code
     * @return NoticePrint
     */
    public function addNotice($message, $code = 0)
    {
        $this->variables[self::NAME][] = array(
            'time'    => time(),
            'message' => $message,
            'code'    => $code
        );
        return $this;
    }

    /**
     *
     * @param array $notice
     * @return NoticePrint
     */
    public function addNoticeArray(array $notice)
    {
        $this->variables[self::NAME] = $notice;
        return $this;
    }

}
