<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\TestDox;

include __DIR__ . "/../color/parseHslString.php";
include __DIR__ . "/../color/buildHslString.php";

final class ColorTest extends CustomTestCase
{
    #[TestDox("parseHslString()")]
    public function test_parseHslString(): void
    {
        $cases = [
            [
                "name" => "no arg",
                "args" => [],
                "throws" => ArgumentCountError::class,
            ],
            [
                "name" => "invalid arg",
                "args" => [[]],
                "throws" => TypeError::class,
            ],
            [
                "name" => "empty string",
                "args" => [""],
                "throws" => InvalidArgumentException::class,
            ],
            [
                "name" => "unexpected string",
                "args" => ["lorem ipsum"],
                "throws" => InvalidArgumentException::class,
            ],


            [
                "args" => ["hsl(120,50%,75%)"],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                    "alpha" => 1.0,
                ],
            ],
            [
                "args" => ["hsl(120, 50%, 75%)"],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                    "alpha" => 1.0,
                ],
            ],
            [
                "args" => ["hsl(120deg, 50%, 75%)"],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                    "alpha" => 1.0,
                ],
            ],
            [
                "args" => ["hsl(120deg 50% 75%)"],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                    "alpha" => 1.0,
                ],
            ],
            [
                "args" => ["hsl(120 50 75)"],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                    "alpha" => 1.0,
                ],
            ],


            [
                "args" => ["hsl(120.12, 50.23%, 75.34%)"],
                "throws" => InvalidArgumentException::class,
            ],


            [
                "args" => ["hsl(120,50%,75%,0.5)"],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                    "alpha" => 0.5,
                ],
            ],
            [
                "args" => ["hsl(120, 50%, 75%, 0.5)"],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                    "alpha" => 0.5,
                ],
            ],
            [
                "args" => ["hsl(120deg, 50%, 75%, 0.5)"],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                    "alpha" => 0.5,
                ],
            ],
            [
                "args" => ["hsl(120deg 50% 75% / 50%)"],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                    "alpha" => 0.5,
                ],
            ],
            [
                "args" => ["hsl(120 50 75 / 50%)"],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                    "alpha" => 0.5,
                ],
            ],


            [
                "args" => ["hsla(120,50%,75%,0.5)"],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                    "alpha" => 0.5,
                ],
            ],
            [
                "args" => ["hsla(120, 50%, 75%, 0.5)"],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                    "alpha" => 0.5,
                ],
            ],
            [
                "args" => ["hsla(120deg, 50%, 75%, 0.5)"],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                    "alpha" => 0.5,
                ],
            ],
            [
                "args" => ["hsla(120deg 50% 75% / 50%)"],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                    "alpha" => 0.5,
                ],
            ],
            [
                "args" => ["hsla(120 50 75 / 50%)"],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                    "alpha" => 0.5,
                ],
            ],


            [
                "args" => ["hsla(120,50%,75%,.5)"],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                    "alpha" => 0.5,
                ],
            ],
            [
                "args" => ["hsla(120, 50%, 75%, .5)"],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                    "alpha" => 0.5,
                ],
            ],
            [
                "args" => ["hsla(120deg, 50%, 75%, .5)"],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                    "alpha" => 0.5,
                ],
            ],


            [
                "args" => ["rgb(120,50,75)"],
                "throws" => InvalidArgumentException::class,
            ],
            [
                "args" => ["hsl(444,50%,75%)"],
                "throws" => InvalidArgumentException::class,
            ],
            [
                "args" => ["hsl(120,555%,75%)"],
                "throws" => InvalidArgumentException::class,
            ],
            [
                "args" => ["hsl(120,50%,666%)"],
                "throws" => InvalidArgumentException::class,
            ],
            [
                "args" => ["hsl(120,50%,75%,2)"],
                "throws" => InvalidArgumentException::class,
            ],
            [
                "args" => ["hsl(120 50% 75% / 0.5)"],
                "throws" => InvalidArgumentException::class,
            ],
        ];
        $this->handleCases($cases, "parseHslString");
    }

    #[TestDox("parseHslString()")]
    public function test_buildHslString(): void {
        $cases = [
            [
                "name" => "no arg",
                "args" => [],
                "throws" => ArgumentCountError::class,
            ],
            [
                "name" => "invalid arg",
                "args" => [123],
                "throws" => TypeError::class,
            ],
            [
                "name" => "empty array",
                "args" => [[]],
                "throws" => InvalidArgumentException::class,
            ],
            [
                "name" => "unexpected array",
                "args" => [["lorem" => "ipsum"]],
                "throws" => InvalidArgumentException::class,
            ],
            [
                "name" => "incomplete hsl array",
                "args" => [["hue" => 120]],
                "throws" => InvalidArgumentException::class,
            ],
            [
                "name" => "incomplete hsl array",
                "args" => [["hue" => 120, "sat" => 50]],
                "throws" => InvalidArgumentException::class,
            ],


            [
                "args" => [["hue" => 120, "sat" => 50, "lum" => 75]],
                "expected" => "hsl(120 50 75)",
            ],
            [
                "args" => [["hue" => 120, "sat" => 50, "lum" => 75, "alpha" => 0.5]],
                "expected" => "hsl(120 50 75 / 50%)",
            ],
            [
                "args" => [["hue" => 120, "sat" => 50, "lum" => 75, "alpha" => 0.512]],
                "expected" => "hsl(120 50 75 / 51.2%)",
            ],
        ];
        $this->handleCases($cases, "buildHslString");
    }
}
