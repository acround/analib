<?php

namespace analib\Util;

/**
 * Description of Notification
 *
 * @author acround
 */
class Notification extends StaticFactory
{

    const FIELD_NAME = 'system_notifications';

    public static function add($messages, $code = 0)
    {
        if (!is_array($messages)) {
            $messages = array($messages);
        }
        $notif = Session::get(self::FIELD_NAME);
        foreach ($messages as $message) {
            if (!Session::has(self::FIELD_NAME)) {
                Session::set(self::FIELD_NAME, array());
            }
            if ($message instanceof \analib\Core\Exceptions\BaseException) {
                $code = $message->getCode();
                if (Application::me()->getDebug()) {
                    $message = '<table><tbody>' .
                        '<tr><td colspan="4">' . $message->getMessage() . '</td></tr>' .
                        $message->printTrace($message->getTrace()) .
                        '</tbody></table>';
                } else {
                    $message = $message->getMessage();
                }
            }
            $notif[] = array(
                'time'    => time(),
                'message' => $message,
                'code'    => $code
            );
        }
        Session::set(self::FIELD_NAME, $notif);
    }

    public static function get()
    {
        if (!Session::has(self::FIELD_NAME)) {
            Session::set(self::FIELD_NAME, array());
        }
        return Session::get(self::FIELD_NAME);
    }

    public static function clear()
    {
        Session::set(self::FIELD_NAME, array());
    }

    static function length()
    {
        if (Session::has(self::FIELD_NAME)) {
            return count(Session::get(self::FIELD_NAME));
        } else {
            return 0;
        }
    }

}
