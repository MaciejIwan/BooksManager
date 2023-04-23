<?php

namespace App\Enum;

use InvalidArgumentException;

enum BookReviewStars: int
{
    case ONE = 1;
    case TWO = 2;
    case THREE = 3;
    case FOUR = 4;
    case FIVE = 5;
    case SIX = 6;
    case SEVEN = 7;
    case EIGHT = 8;
    case NINE = 9;
    case TEN = 10;

    public function toString(): string
    {
        return match ($this) {
            self::ONE => '1',
            self::TWO => '2',
            self::THREE => '3',
            self::FOUR => '4',
            self::FIVE => '5',
            self::SIX => '6',
            self::SEVEN => '7',
            self::EIGHT => '8',
            self::NINE => '9',
            self::TEN => '10',
        };
    }

    public static function fromValue(int $value): self
    {
        if ($value < 1 || $value > 10) {
            throw new InvalidArgumentException('Invalid rating value: ' . $value);
        }

        return match ($value) {
            1 => self::ONE,
            2 => self::TWO,
            3 => self::THREE,
            4 => self::FOUR,
            5 => self::FIVE,
            6 => self::SIX,
            7 => self::SEVEN,
            8 => self::EIGHT,
            9 => self::NINE,
            10 => self::TEN,
        };
    }
}
