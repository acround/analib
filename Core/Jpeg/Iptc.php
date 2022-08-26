<?php

namespace analib\Core\Jpeg;

/**
 * Работа с Iptc
 *
 * @author acround
 */
class Iptc
{

    const IPTC_HEADLINE    = '2#105';
    const IPTC_TITLE       = '2#005';
    const IPTC_KEYWORDS    = '2#025';
    const IPTC_DESCRIPTION = '2#120';
    const IPTC_DATE        = '2#055';
    const IPTC_TIME        = '2#060';
    const IPTC_CITY        = '2#090';
    const IPTC_REGION      = '2#095';
    const IPTC_SUBLOCATION = '2#092';

    private array $iptcTags = [
        ['key' => '2#005', 'name' => 'Название'],
        ['key' => '2#025', 'name' => 'Ключевые слова'],
        ['key' => '2#040', 'name' => 'Инструкции'],
        ['key' => '2#055', 'name' => 'Дата'],
        ['key' => '2#060', 'name' => 'Время'],
        ['key' => '2#080', 'name' => 'Автор'],
        ['key' => '2#085', 'name' => 'Автор:Должность'],
        ['key' => '2#090', 'name' => 'Город'],
        ['key' => '2#092', 'name' => 'Район'],
        ['key' => '2#095', 'name' => 'Край/область'],
//		['key' => '2#100', 'name' => 'К'],
        ['key' => '2#101', 'name' => 'Страна'],
        ['key' => '2#103', 'name' => 'JobID'],
        ['key' => '2#105', 'name' => 'Заголовок'],
        ['key' => '2#110', 'name' => 'Благодарности'],
        ['key' => '2#115', 'name' => 'Источник'],
        ['key' => '2#116', 'name' => 'Авторские права'],
        ['key' => '2#120', 'name' => 'Описание'],
        ['key' => '2#122', 'name' => 'Автор описания'],
    ];
    private string $filename;
    private array $ipts     = array();

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    private function checkIptc()
    {
        if (!$this->ipts) {
            $iptcData = array();
            getimagesize($this->filename, $info);
            if (isset($info['APP13'])) {
                $iptc     = iptcparse($info['APP13']);
                foreach ($iptc as $key => $tag) {
                    if (count($tag) == 1) {
                        $iptcData[$key] = $tag[0];
                    } else {
//						$iptcData[$key] = implode('; ', $tag);
                        $iptcData[$key] = $tag;
                    }
                }
            }
            $this->ipts = $iptcData;
        }
    }

    public function getAllIptc(): array
    {
        $this->checkIptc();
        return $this->ipts;
    }

    public function getIptc($iptcKey)
    {
        $this->checkIptc();
        if (isset($this->ipts[$iptcKey])) {
            return $this->ipts[$iptcKey];
        } else {
            return array();
        }
    }

// iptc_make_tag() функция от Thies C. Arntzen
    public static function iptc_make_tag($rec, $data, $value): string
    {
        $length = strlen($value);
        $retval = chr(0x1C) . chr($rec) . chr($data);

        if ($length < 0x8000) {
            $retval .= chr($length >> 8) . chr($length & 0xFF);
        } else {
            $retval .= chr(0x80) .
                chr(0x04) .
                chr(($length >> 24) & 0xFF) .
                chr(($length >> 16) & 0xFF) .
                chr(($length >> 8) & 0xFF) .
                chr($length & 0xFF);
        }

        return $retval . $value;
    }

    public function putIptc($iptc)
    {
// Путь к jpeg файлу
        $path = $this->filename;

// установка IPTC тэгов
//		$iptc = array(
//			'2#120'	 => 'Тестовое изображение',
//			'2#116'	 => 'Copyright 2008-2009, The PHP Group'
//		);
// Преобразование IPTC тэгов в двоичный код
        $data = '';

        foreach ($iptc as $tag => $string) {
            $tag  = substr($tag, 2);
            $data .= iptc_make_tag(2, $tag, $string);
        }

// Встраивание IPTC данных
        $content = iptcembed($data, $path);

// запись нового изображения в файл
        $fp = fopen($path, "wb");
        fwrite($fp, $content);
        fclose($fp);
    }

}
