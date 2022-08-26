<?php

namespace analib\Core\Filters;

/**
 *
 * @author acround
 */
class StringFilter extends BaseFilter
{

    const FILTER_DEFAULT = '/[.]*/';
    const FILTER_DATE_RUS = 'd.m.Y';
    const FILTER_DATE_USA = 'm-d-Y';
    const FILTER_DATE_INTERNATIONAL = 'd-m-Y';
    const FILTER_DATE_UK = 'd/m/Y';
    const FILTER_DATE_SORTABLE = 'Y.m.d';
    const FILTER_DATE_DEFAULT = self::FILTER_DATE_RUS;
    const FILTER_TIME = 'H:i:s';
    const FILTER_DATE_TIME_RUS = 'd.m.Y H:i:s';
    const FILTER_DATE_TIME_USA = 'm-d-Y H:i:s';
    const FILTER_DATE_TIME_INTERNATIONAL = 'd-m-Y H:i:s';
    const FILTER_DATE_TIME_UK = 'd/m/Y H:i:s';
    const FILTER_DATE_TIME_SORTABLE = 'Y.m.d H:i:s';
    const FILTER_DATE_TIME_DEFAULT = self::FILTER_DATE_TIME_RUS;
    const FILTER_GPS = '/^\d\d\d-\d\d\d-\d\d\d[- ]\d\d$/';
    const FILTER_GPS_NOSS = '/^\d\d\d-\d\d\d-\d\d\d-\d\d$/';
    const FILTER_GPS_STANDARD = '/^\d\d\d-\d\d\d-\d\d\d \d\d$/';
    const FILTER_REGION_ID = '/^\d\d$/';
    const FILTER_KLADR = '/^[(\d{13})(\d{17})]$/';
    const FILTER_EMAIL = '/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/';
    const FILTER_PHONE = '/^\+[\d]{1,2}\(\d{3}\)\d{3}-\d{2}-\d{2}$/';
    const FILTER_URL = '/^((https?|ftp)\:\/\/)?([a-z0-9]{1})((\.[a-z0-9-])|([a-z0-9-]))*\.([a-z]{2,6})(\/?)$/';

    static protected $filterPattern = self::FILTER_DEFAULT;

    public static function test($value): bool
    {
        if (preg_match(self::$filterPattern, (string)$value)) {
            return true;
        } else {
            return false;
        }
    }

}
