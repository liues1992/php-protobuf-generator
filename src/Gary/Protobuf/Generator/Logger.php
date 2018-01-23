<?php
namespace Gary\Protobuf\Generator;


class Logger
{
    const ERROR_LEVEL = "ERROR";
    const WARN_LEVEL = "WARN";

    /**
     * @param string $message
     *
     * @return null
     */
    public static function error($message)
    {
        self::log(self::ERROR_LEVEL, date('Y-m-d H:i:s') . ": " . $message);
    }

    /**
     * @param string $message
     *
     * @return null
     */
    public static function warn($message)
    {
        self::log(self::WARN_LEVEL, $message);
    }

    private static function log($level, $message)
    {
        fputs(STDERR, "$level: $message" . PHP_EOL);
    }
}