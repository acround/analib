<?php

namespace analib\Util;

/**
 * Description of FileUtils
 *
 * @author acround
 */
class FileUtils
{

    const SIGNATURE_FILE_ZIP        = '504b0304';
    const SIGNATURE_FILE_XML        = '<?xml';
    const FILE_TYPE_CHECK_SIGNATURE = 0;
    const FILE_TYPE_CHECK_EXTENSION = 1;

    public static $fileTypeCheck = self::FILE_TYPE_CHECK_EXTENSION;

    public static function getExtension($filename)
    {
        $tmp = explode(".", $filename);
        if (count($tmp) > 1) {
            return end($tmp);
        } else {
            return '';
        }
    }

    public static function getName($filename)
    {
        $tmp = explode(".", $filename);
        if (count($tmp) > 1) {
            unset($tmp[count($tmp) - 1]);
            return implode('.', $tmp);
        } else {
            return $filename;
        }
    }

    public static function checkSignature($fileName, $signature, $hex = false)
    {
        $f = fopen($fileName, 'r');
        if ($hex) {
            $ls = strlen($signature) / 2;
            $s  = unpack('H*', fread($f, $ls))[1];
        } else {
            $ls = strlen($signature);
            $s  = fread($f, $ls);
        }
        fclose($f);
        return strtolower($s) == strtolower($signature);
    }

    public static function checkExtension($fileName, $extension)
    {
        $le = strlen($extension);
        $f  = substr($fileName, -$le);
        return strtolower($f) == strtolower($extension);
    }

    public static function isZipFile($fileName)
    {
        if (self::$fileTypeCheck == self::FILE_TYPE_CHECK_SIGNATURE) {
            $r = self::checkSignature($fileName, self::SIGNATURE_FILE_ZIP, true);
        } elseif (self::$fileTypeCheck = self::FILE_TYPE_CHECK_EXTENSION) {
            $r = self::checkExtension($fileName, '.zip');
        }
        return $r;
    }

    public static function isXmlFile($fileName)
    {
        if (self::$fileTypeCheck == self::FILE_TYPE_CHECK_SIGNATURE) {
            $r = self::checkSignature($fileName, self::SIGNATURE_FILE_XML);
        } elseif (self::$fileTypeCheck = self::FILE_TYPE_CHECK_EXTENSION) {
            $r = self::checkExtension($fileName, '.xml');
        }
        return $r;
    }

    public static function isFb2File($fileName)
    {
        if (self::$fileTypeCheck == self::FILE_TYPE_CHECK_SIGNATURE) {
            if (self::isXmlFile($fileName)) {
                $r = true;
            } else {
                $r = false;
            }
        } elseif (self::$fileTypeCheck = self::FILE_TYPE_CHECK_EXTENSION) {
            $r = self::checkExtension($fileName, '.fb2');
        }
        return $r;
    }

}
