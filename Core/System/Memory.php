<?php

namespace analib\Core\System;

/**
 * Description of Memory
 *
 * @author acround
 */
class Memory
{

    public static function getSystemMemoryInfo(): array
    {
        exec('free', $free);
        preg_match_all('/([\d.*]+)/', $free[1], $memory);
        preg_match_all('/([\d.*]+)/', $free[count($free) - 1], $swap);

        $arMemory = array(
            'total'   => $memory[0][0],
            'used'    => $memory[0][1],
            'free'    => $memory[0][2],
            'shared'  => $memory[0][3],
            'buffers' => $memory[0][4],
            'cached'  => $memory[0][5],
        );
        $arSwap   = array(
            'total' => $swap[0][0],
            'used'  => $swap[0][1],
            'free'  => $swap[0][2],
        );
        return array('memory' => $arMemory, 'swap' => $arSwap);
    }

}
