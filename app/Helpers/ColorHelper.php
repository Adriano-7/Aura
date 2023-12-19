<?php
namespace App\Helpers;

class ColorHelper {
    public static function adjustBrightness($hex, $steps) {
        $steps = max(-255, min(255, $steps));

        $hex = str_replace('#', '', $hex);
        if (strlen($hex) == 3) {
            $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
        }

        $color_parts = str_split($hex, 2);
        $finaLColor = '#';

        foreach ($color_parts as $color) {
            $color   = hexdec($color);
            $color   = max(0,min(255,$color + $steps));
            $finaLColor .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT);
        }
        return $finaLColor;
    }
}