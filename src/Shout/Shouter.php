<?php

namespace App\Shout;

class Shouter
{
    /**
     * @param array $quotes
     *
     * @return array
     */
    public static function shoutAll(array $quotes): array
    {
        return array_map(
            static function (string $quote) {
                return self::shout($quote);
            },
            $quotes
        );
    }

    /**
     * @param string $quote
     *
     * @return string
     */
    private static function shout(string $quote): string
    {
        return mb_strtoupper(rtrim($quote, '.?!')) . '!';
    }
}
