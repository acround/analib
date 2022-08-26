<?php

namespace analib\Util;

use analib\Util\FileUtils;
use Exception;

class PhotoRenamer
{

    const IPTC_HEADLINE = '2#105';
    const IPTC_TITLE = '2#005';
    const IPTC_DESCRIPTION = '2#120';
    const IPTC_KEYWORDS = '2#025';
    const IPTC_REGION = '2#095';
    const IPTC_SUBLOCATION = '2#092';
    const IPTC_CITY = '2#090';
    const IPTC_DATE = '2#055';
    const IPTC_TIME = '2#060';
    const DATE_PATTERN = '/([\d]{8})/i';
    const TIME_PATTERN = '/([\d]{6})/i';

    static $finfo;
    static private $fileList;

    private static function getFinfo()
    {
        if (!self::$finfo) {
            self::$finfo = finfo_open(FILEINFO_MIME_TYPE);
        }
        return self::$finfo;
    }

    /**
     * File list to rename
     */
    private static function getFileList($dirName)
    {
        $dir = opendir($dirName);
        $fileList = array();
        while (($file = readdir($dir)) !== false) {
            if (substr($file, 0, 1) !== '.') {
                $fileList[] = $file;
            }
        }
        sort($fileList);
        self::$fileList = $fileList;
    }

    private static function getInfoFromFileName($file): array
    {
        $date = $time = null;
        $ctr = preg_match_all(self::DATE_PATTERN, $file, $match);
        if ($ctr && isset($match[0]) && $match[0]) {
            $date = implode('', explode('-', implode('-', str_split($match[0][0], 2)), 2));
            preg_match_all(self::TIME_PATTERN, $file, $match);
            $time = $match[0][1];
        }
        return [$date, $time];
    }

    private static function prepareFileList($dirName)
    {
        $fileList = self::$fileList;
        $out = [];
        foreach ($fileList as $file) {
            $mime = finfo_file(self::getFinfo(), $dirName . DIRECTORY_SEPARATOR . $file, FILEINFO_MIME_TYPE);
            $fileName = FileUtils::getName($file);
            $fileExt = FileUtils::getExtension($file);
            if (!isset($out[$fileName])) {
                $out[$fileName] = [
                    'jpeg' => false,
                    'video' => false,
                    'exif' => null,
                    'ext' => []
                ];
            }
            if ($mime == 'image/jpeg') {
                $out[$fileName]['jpeg'] = true;
                $fullName = $dirName . DIRECTORY_SEPARATOR . $file;
                $iptcData = [];
                $date = $time = null;
                $exif = null;
                $info = null;
                try {
                    $exif = exif_read_data($fullName, 0, true);
                    getimagesize($fullName, $info);
                } catch (Exception $e) {
                    list($date, $time) = self::getInfoFromFileName($file);
                    if ($date) {
                        list($date, $time) = self::getInfoFromFileName($file);
                    } else {
                        echo $file . " - Renaming has been failed\n";
                    }
                }
                if (isset($info['APP13']) && $iptc = iptcparse($info['APP13'])) {
                    foreach ($iptc as $key => $tag) {
                        if (count($tag) == 1) {
                            $iptcData[$key] = $tag[0];
                        } else {
                            $iptcData[$key] = $tag;
                        }
                    }
                }
                // Date
                if (isset($exif['EXIF']['DateTimeOriginal'])) {
                    $dateTime = explode(' ', $exif['EXIF']['DateTimeOriginal']);
                    if (count($dateTime) > 1) {
                        $date = str_replace(':', '-', $dateTime[0]);
                        $time = str_replace(':', '', $dateTime[1]);
                    }
                }
                if (!$date) {
                    if (isset($iptcData[self::IPTC_DATE]) && strlen($iptcData[self::IPTC_DATE]) == 8) {
                        $date = substr($iptcData[self::IPTC_DATE], 0, 4) . '-' . substr($iptcData[self::IPTC_DATE], 4, 2) . '-' . substr($iptcData[self::IPTC_DATE], 6, 2);
                    }
                    if (isset($iptcData[self::IPTC_TIME])) {
                        if (strlen($iptcData[self::IPTC_TIME]) == 6) {
                            $time = $iptcData[self::IPTC_TIME];
                        } else {
                            $time = substr($iptcData[self::IPTC_TIME], 0, 6);
                        }
                    }
                }

                $out[$fileName]['exif'] = [
                    'date' => $date,
                    'time' => $time,
                    'head' => $iptcData[self::IPTC_HEADLINE] ?? null,
                    'title' => $iptcData[self::IPTC_TITLE] ?? null,
                ];
            } elseif (explode('/', $mime)[0] == 'video') {
                $out[$fileName]['video'] = true;
                list($date, $time) = self::getInfoFromFileName($file);
                if ($date) {
                    $out[$fileName]['exif'] = [
                        'date' => $date,
                        'time' => $time,
                        'head' => null,
                        'title' => null,
                    ];
                } else {
                    echo $file . " - Renaming has been failed\n";
                }
            }
            $out[$fileName]['ext'][strtolower($fileExt)] = [
                'file' => $file,
                'mime' => $mime,
            ];
        }
        self::$fileList = $out;
    }

    private static function makeListToRename()
    {
        $fileList = self::$fileList;
        $out = [];
        foreach ($fileList as $row) {
            if ($row['jpeg'] || $row['video']) {
                $newName = '';
                if ($row['exif']['date']) {
                    $newName .= $row['exif']['date'];
                    if ($row['exif']['time']) {
                        $newName .= '_' . $row['exif']['time'];
                    }
                }
                if ($row['exif']['head']) {
                    $newName .= ($newName ? '.' : '') . $row['exif']['head'];
                }
                if ($row['exif']['title']) {
                    $newName .= ($newName ? '.' : '') . $row['exif']['title'];
                }
                if ($newName) {
                    foreach ($row['ext'] as $ext => $file) {
                        $out[$file['file']] = $newName . '.' . $ext;
                    }
                }
            }
        }
        self::$fileList = $out;
    }

    private static function fileRenamer($dirName)
    {
        foreach (self::$fileList as $oldName => $newName) {
            if ($oldName !== $newName) {
                if (file_exists($dirName . DIRECTORY_SEPARATOR . $newName)) {
                    $fileIndex = 0;
                    $fileName = FileUtils::getName($newName);
                    $fileExt = FileUtils::getExtension($newName);
                    $rename = true;
                    $fullName = '';
                    do {
                        $fileIndex++;
                        $file = $fileName . '_(' . $fileIndex . ').' . $fileExt;
                        if ($file == $newName) {
                            $rename = false;
                            echo $oldName . '==>> does not need to rename' . "\n";
                            continue;
                        }
                        $fullName = $dirName . DIRECTORY_SEPARATOR . $file;
                    } while (file_exists($fullName));
                    if ($rename) {
                        echo $oldName . '==>>' . $newName . "\n";
                        rename($dirName . DIRECTORY_SEPARATOR . $oldName, $fullName);
                    }
                } else {
                    echo $oldName . '==>>' . $newName . "\n";
                    rename($dirName . DIRECTORY_SEPARATOR . $oldName, $dirName . DIRECTORY_SEPARATOR . $newName);
                }
            } else {
                echo $oldName . '==>> does not need to rename' . "\n";
            }
        }
    }

    public static function exec($dirName)
    {
        self::getFileList($dirName);
        self::prepareFileList($dirName);
        self::makeListToRename();
        self::fileRenamer($dirName);
    }

}
