<?php

namespace analib\System\Console;

/**
 * Константы цветов для консоли
 * @author acround
 */
class Colors
{

    const ALL_DEFAULT        = "\033[0m";
    const FONT_BOLD          = "\033[1m";
    const FONT_SHADOW        = "\033[2m";
    const FONT_UNDERLINE     = "\033[4m";
    const FONT_BLINK         = "\033[5m";
    const FONT_REVERSE       = "\033[7m";
    const FONT_BOLD_OFF      = "\033[21m";
    const FONT_SHADOW_OFF    = "\033[22m";
    const FONT_UNDERLINE_OFF = "\033[24m";
    const FONT_BLINK_OFF     = "\033[25m";
    const FONT_REVERSE_OFF   = "\033[27m";
    const COLOR_BLACK        = "\033[30m";
    const COLOR_RED          = "\033[31m";
    const COLOR_GREEN        = "\033[32m";
    const COLOR_YELLOW       = "\033[33m";
    const COLOR_BLUE         = "\033[34m";
    const COLOR_MAGENTA      = "\033[35m";
    const COLOR_CYAN         = "\033[36m";
    const COLOR_GREY         = "\033[37m";
    const BACK_BLACK         = "\033[40m";
    const BACK_RED           = "\033[41m";
    const BACK_GREEN         = "\033[42m";
    const BACK_YELLOW        = "\033[43m";
    const BACK_BLUE          = "\033[44m";
    const BACK_MAGENTA       = "\033[45m";
    const BACK_CYAN          = "\033[46m";
    const BACK_GREY          = "\033[47m";

    /*
      \033[0m		все атрибуты по умолчанию
      \033[1m		жирный шрифт (интенсивный цвет)
      \033[2m		полу яркий цвет (тёмно-серый, независимо от цвета)
      \033[4m		подчеркивание
      \033[5m		мигающий
      \033[7m		реверсия (знаки приобретают цвет фона, а фон -- цвет знаков)

      \033[22m	установить нормальную интенсивность
      \033[24m	отменить подчеркивание
      \033[25m	отменить мигание
      \033[27m	отменить реверсию

      \033[30m	чёрный цвет знаков
      \033[31m	красный цвет знаков
      \033[32m	зелёный цвет знаков
      \033[33m	желтый цвет знаков
      \033[34m	синий цвет знаков
      \033[35m	фиолетовый цвет знаков
      \033[36m	цвет морской волны знаков
      \033[37m	серый цвет знаков

      \033[40m	чёрный цвет фона
      \033[41m	красный цвет фона
      \033[42m	зелёный цвет фона
      \033[43m	желтый цвет фона
      \033[44m	синий цвет фона
      \033[45m	фиолетовый цвет фона
      \033[46m	цвет морской волны фона
      \033[47m	серый цвет фона
     *
     *
      Таблица цветов и фонов:

      Цвет			код			код фона

      black	30	40	\033[30m	\033[40m
      red		31	41	\033[31m	\033[41m
      green	32	42	\033[32m	\033[42m
      yellow	33	43	\033[33m	\033[43m
      blue	34	44	\033[34m	\033[44m
      magenta	35	45	\033[35m	\033[45m
      cyan	36	46	\033[36m	\033[46m
      grey	37	47	\033[37m	\033[47m
     */

    //put your code here
    public static function colorPut($colors)
    {
        if (!is_array($colors)) {
            $colors = [$colors];
        }
        foreach ($colors as $color) {
            echo $color;
        }
    }

    public static function colorsDefault()
    {
        self::colorPut(self::ALL_DEFAULT);
    }

    public static function backBlack()
    {
        self::colorPut(self::BACK_BLACK);
    }

    public static function backBlue()
    {
        self::colorPut(self::BACK_BLUE);
    }

    public static function backCyan()
    {
        self::colorPut(self::BACK_CYAN);
    }

    public static function backGray()
    {
        self::colorPut(self::BACK_GREY);
    }

    public static function backGreen()
    {
        self::colorPut(self::BACK_GREEN);
    }

    public static function backMagenta()
    {
        self::colorPut(self::BACK_MAGENTA);
    }

    public static function backRed()
    {
        self::colorPut(self::BACK_RED);
    }

    public static function backYellow()
    {
        self::colorPut(self::BACK_YELLOW);
    }

    public static function colorBlack()
    {
        self::colorPut(self::COLOR_BLACK);
    }

    public static function colorBlue()
    {
        self::colorPut(self::COLOR_BLUE);
    }

    public static function colorCyan()
    {
        self::colorPut(self::COLOR_CYAN);
    }

    public static function colorGray()
    {
        self::colorPut(self::COLOR_GREY);
    }

    public static function colorGreen()
    {
        self::colorPut(self::COLOR_GREEN);
    }

    public static function colorMagenta()
    {
        self::colorPut(self::COLOR_MAGENTA);
    }

    public static function colorRed()
    {
        self::colorPut(self::COLOR_RED);
    }

    public static function colorYellow()
    {
        self::colorPut(self::COLOR_YELLOW);
    }

    public static function fontBold($on = true)
    {
        $on ? self::colorPut(self::FONT_BOLD) : self::colorPut(self::FONT_BOLD_OFF);
    }

    public static function fontShadow($on = true)
    {
        $on ? self::colorPut(self::FONT_SHADOW) : self::colorPut(self::FONT_SHADOW_OFF);
    }

    public static function fontUnderline($on = true)
    {
        $on ? self::colorPut(self::FONT_UNDERLINE) : self::colorPut(self::FONT_UNDERLINE_OFF);
    }

    public static function fontBlink($on = true)
    {
        $on ? self::colorPut(self::FONT_BLINK) : self::colorPut(self::FONT_BLINK_OFF);
    }

    public static function fontReverse($on = true)
    {
        $on ? self::colorPut(self::FONT_REVERSE) : self::colorPut(self::FONT_REVERSE_OFF);
    }

}
