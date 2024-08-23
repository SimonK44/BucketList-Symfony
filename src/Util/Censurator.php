<?php

namespace App\Util;

class Censurator
{
    const UNWANTED_WORDS = ["casino", "viagra", "bad", "banana"];

    public function purify(?string $text): string
    {
        foreach (self::UNWANTED_WORDS as $unwantedWord) {
            $replacement = str_repeat("*", mb_strlen($unwantedWord));
            $text = str_ireplace($unwantedWord, $replacement, $text);
        }
        return $text;
    }

}