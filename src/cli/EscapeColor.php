<?php declare(strict_types=1);

namespace Flux\cli;

/**
 * Color Escapes for Bash
 */
final class EscapeColor {
    
    private static array $background = [
        "red" => "41",
        "green" => "42",
    ];

    private static array $foreground = [
        "red" => "0;31",
        "boldRed" => "1;31",
        "green" => "0;32",
    ];

    private static function escape(string $color, string $data): string {
        return "\33[" . $color . "m" . $data . "\33[0m";
    }

    public static function red(string $data): string {
        return EscapeColor::escape(EscapeColor::$foreground["red"], $data);
    }

    public static function boldRed(string $data): string {
        return EscapeColor::escape(EscapeColor::$foreground["boldRed"], $data);
    }

    public static function green(string $data): string {
        return EscapeColor::escape(EscapeColor::$foreground["green"], $data);
    }

    public static function bgRed(string $data): string {
        return EscapeColor::escape(EscapeColor::$background["red"], $data);
    }

    public static function bgGreen(string $data): string {
        return EscapeColor::escape(EscapeColor::$background["green"], $data);
    }
}
