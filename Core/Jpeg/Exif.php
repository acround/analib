<?php

namespace analib\Core\Jpeg;

/**
 * Работа с Exif
 *
 * @author acround
 */
class Exif
{

    const EXIF_VERSION           = 'ExifVersion';
    const EXIF_DATETIME_ORIGINAL = 'DateTimeOriginal';
    const EXIF_COLORSPACE        = 'ColorSpace';

    private string $filename;
    private array $exif = array();

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    private function checkExif()
    {
        if (!$this->exif) {
            $exif       = exif_read_data($this->filename, 0, true);
            if (isset($exif['EXIF']))
                $this->exif = $exif['EXIF'];
        }
    }

    public function getAllExif(): array
    {
        $this->checkExif();
        return $this->exif;
    }

    public function getExif($exifKey)
    {
        $this->checkExif();
        if (isset($this->exif[$exifKey])) {
            return $this->exif[$exifKey];
        } else {
            return array();
        }
    }

}
