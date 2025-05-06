<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\TestDox;

include __DIR__ . "/../color/parseHslString.php";
include __DIR__ . "/../color/buildHslString.php";
include __DIR__ . "/../color/hslOps.php";

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
    public function test_buildHslString(): void
    {
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

    #[TestDox("hslSet()")]
    public function test_hslSet(): void
    {
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
                "args" => ["hsl" => ["hue" => 120, "sat" => 50, "lum" => 75], "hue" => 45],
                "expected" => [
                    "hue" => 45,
                    "sat" => 50,
                    "lum" => 75,
                ],
            ],
            [
                "args" => ["hsl" => ["hue" => 120, "sat" => 50, "lum" => 75], "sat" => 90],
                "expected" => [
                    "hue" => 120,
                    "sat" => 90,
                    "lum" => 75,
                ],
            ],
            [
                "args" => ["hsl" => ["hue" => 120, "sat" => 50, "lum" => 75], "lum" => 35],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 35,
                ],
            ],
            [
                "args" => ["hsl" => ["hue" => 120, "sat" => 50, "lum" => 75], "alpha" => 0.65],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                    "alpha" => 0.65,
                ],
            ],
            [
                "args" => ["hsl" => ["hue" => 120, "sat" => 50, "lum" => 75, "alpha" => 0.25], "alpha" => 0.65],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                    "alpha" => 0.65,
                ],
            ],
        ];
        $this->handleCases($cases, "hslSet");
    }

    #[TestDox("hslAdd()")]
    public function test_hslAdd(): void
    {
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
                "args" => ["hsl" => ["hue" => 120, "sat" => 50, "lum" => 75], "hue" => 30],
                "expected" => [
                    "hue" => 150,
                    "sat" => 50,
                    "lum" => 75,
                ],
            ],
            [
                "args" => ["hsl" => ["hue" => 120, "sat" => 50, "lum" => 75], "sat" => 20],
                "expected" => [
                    "hue" => 120,
                    "sat" => 70,
                    "lum" => 75,
                ],
            ],
            [
                "args" => ["hsl" => ["hue" => 120, "sat" => 50, "lum" => 75], "lum" => 15],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 90,
                ],
            ],
            [
                "args" => ["hsl" => ["hue" => 120, "sat" => 50, "lum" => 75], "alpha" => 0.25],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                    "alpha" => 1,
                ],
                // "isolate" => true,
            ],
            [
                "args" => ["hsl" => ["hue" => 120, "sat" => 50, "lum" => 75], "alpha" => -0.25],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                    "alpha" => 0.75,
                ],
            ],
            [
                "args" => ["hsl" => ["hue" => 120, "sat" => 50, "lum" => 75, "alpha" => 0.25], "alpha" => 0.25],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                    "alpha" => 0.5,
                ],
            ],
        ];
        $this->handleCases($cases, "hslAdd");
    }

    #[TestDox("hslMul()")]
    public function test_hslhslMul(): void
    {
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
                "args" => ["hsl" => ["hue" => 120, "sat" => 50, "lum" => 75], "hue" => 1],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                ],
            ],
            [
                "args" => ["hsl" => ["hue" => 120, "sat" => 50, "lum" => 75], "hue" => 1.11],
                "expected" => [
                    "hue" => 133, // 132.2
                    "sat" => 50,
                    "lum" => 75,
                ],
            ],
            [
                "args" => ["hsl" => ["hue" => 120, "sat" => 50, "lum" => 75], "sat" => 1.25],
                "expected" => [
                    "hue" => 120,
                    "sat" => 62, // 62.5
                    "lum" => 75,
                ],
            ],
            [
                "args" => ["hsl" => ["hue" => 120, "sat" => 50, "lum" => 75], "lum" => 1.5],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 100, // 112.5
                ],
            ],
            [
                "args" => ["hsl" => ["hue" => 120, "sat" => 50, "lum" => 75], "alpha" => 0.5],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                    "alpha" => 0.5,
                ],
            ],
            [
                "args" => ["hsl" => ["hue" => 120, "sat" => 50, "lum" => 75, "alpha" => 0.75], "alpha" => 0.5],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                    "alpha" => 0.375,
                ],
            ],
            [
                "args" => ["hsl" => ["hue" => 120, "sat" => 50, "lum" => 75, "alpha" => 0.25], "alpha" => 0],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                    "alpha" => 0,
                ],
            ],
            [
                "args" => ["hsl" => ["hue" => 120, "sat" => 50, "lum" => 75], "alpha" => 2],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                    "alpha" => 1,
                ],
            ],
        ];
        $this->handleCases($cases, "hslMul");
    }

    #[TestDox("parseHslOp()")]
    public function test_parseHslOp(): void
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
                "expected" => null,
            ],
            [
                "name" => "unexpected string",
                "args" => ["lorem"],
                "expected" => null,
            ],
            [
                "args" => ["hue + 20"],
                "expected" => [
                    "channel" => "hue",
                    "operator" => "+",
                    "value" => (float) 20,
                ],
            ],
            [
                "args" => ["hue+20"],
                "expected" => [
                    "channel" => "hue",
                    "operator" => "+",
                    "value" => (float) 20,
                ],
            ],
            [
                "args" => [" hue + 20 "],
                "expected" => null,
            ],
            [
                "args" => ["sat = 50"],
                "expected" => [
                    "channel" => "sat",
                    "operator" => "=",
                    "value" => (float) 50,
                ],
            ],
            [
                "args" => ["lum * 0.5"],
                "expected" => [
                    "channel" => "lum",
                    "operator" => "*",
                    "value" => 0.5,
                ],
            ],
            [
                "args" => ["alpha / 2"],
                "expected" => [
                    "channel" => "alpha",
                    "operator" => "/",
                    "value" => (float) 2,
                ],
            ],
        ];
        $this->handleCases($cases, "parseHslOp");
    }
}
