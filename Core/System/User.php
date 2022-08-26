<?php

namespace analib\Core\System;

/**
 * Description of SystemUser
 *
 * @author acround
 */
class User
{

    const ERRNO_OK = 0;
    const ERROR_OK = 'OK';

    private int $errno = 0;
    private string $error = '';

    /**
     *
     * @return \analib\Core\System\User
     */
    public static function create(): User
    {
        $class = __CLASS__;
        return new $class();
    }

    public static function userInfo()
    {
        return posix_getpwuid(posix_getuid());
    }

    public function errno(): int
    {
        return $this->errno;
    }

    public function error(): string
    {
        return $this->error;
    }

    public function userAdd($userName): bool
    {
        $command = 'useradd -m -d "/home/' . $userName . '" ' . $userName;
        system($command, $return_var);
        $this->errno = $return_var;
        switch ($return_var) {
            case 0:
                $this->error = self::ERROR_OK;
                break;
            case 1:
                $this->error = 'Не удалось изменить файл паролей';
                break;
            case 2:
                $this->error = 'Ошибка в параметрах команды';
                break;
            case 3:
                $this->error = 'Недопустимое значение параметра';
                break;
            case 4:
                $this->error = 'Такой UID уже существует (и не задан параметр -o)';
                break;
            case 6:
                $this->error = 'Указанная группа не существует';
                break;
            case 9:
                $this->error = 'Имя пользователя уже существует';
                break;
            case 10:
                $this->error = 'Не удалось изменить файл групп';
                break;
            case 12:
                $this->error = 'Не удалось создать домашний каталог';
                break;
            case 13:
                $this->error = 'Не удалось создать почтовый ящик';
                break;
        }
        return !(boolean) $this->errno;
    }

    public function getUserGroups($userName)
    {
        $command = 'groups ' . $userName;
        exec($command, $out, $return_var);
        $this->errno = $return_var;
        $this->error = self::ERROR_OK;
        return explode(' ', trim(explode(' : ', $out[0])[1]));
    }

    public function userPasswd($userName): bool
    {
        $command = 'passwd ' . $userName;
        system($command, $return_var);
        $this->errno = $return_var;
        switch ($return_var) {
            case 0:
                $this->error = self::ERROR_OK;
                break;
            case 1:
                $this->error = 'Не удалось задать пароль';
                break;
        }
        return !(boolean) $this->errno;
    }

    public function userAddToGroup($userName, $groupName): bool
    {
        $groups = $this->getUserGroups($userName);
        if (in_array($groupName, $groups)) {
            $this->errno = self::ERRNO_OK;
            $this->error = self::ERROR_OK;
        } else {
            $command = 'usermod -G ' . $groupName . ' -a ' . $userName;
            system($command, $return_var);
            $this->errno = $return_var;
            switch ($return_var) {
                case 0:
                    $this->error = self::ERROR_OK;
                    break;
                case 1:
                    $this->error = 'Не удалось включить в группу ' . $groupName;
                    break;
            }
        }
        return !(boolean) $this->errno;
    }

    public function userLock($userName): bool
    {
        $command = 'usermod -L ' . $userName;
        system($command, $return_var);
        $this->errno = $return_var;
        switch ($return_var) {
            case 0:
                $this->error = self::ERROR_OK;
                break;
            case 1:
                $this->error = 'Не удалось блокировать пользователя';
                break;
        }
        return !(boolean) $this->errno;
    }

    public function userUnlock($userName): bool
    {
        $command = 'usermod -U ' . $userName;
        system($command, $return_var);
        $this->errno = $return_var;
        switch ($return_var) {
            case 0:
                $this->error = self::ERROR_OK;
                break;
            case 1:
                $this->error = 'Не удалось разблокировать пользователя';
                break;
        }
        return !(boolean) $this->errno;
    }

}
