<?php

namespace console\models;

use yii\helpers\Console;

/**
 * Class ConsoleHelper
 * @package console\models
 */
class ConsoleHelper
{

    /**
     * printMessage print custom messages on console
     *
     * @param string $string
     * @access private
     * @return integer|null
     */
    public static function printMessage($string)
    {
        if (!empty($string)) {
            $args = func_get_args();
            array_shift($args);
            $string = Console::ansiFormat($string, $args);
            return Console::stdout($string);
        }
    }
}