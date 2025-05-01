<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\TestDox;

include __DIR__ . "/../color/parseHslString.php";

final class ColorTest extends CustomTestCase
{
    #[TestDox("parseHslString()")]
    public function test_parseHslString(): void
    {
        $cases = [
            [
                "args" => ["hsl(120,50%,75%)"],
                "expected" => [
                    "hue" => 120,
                    "sat" => 50,
                    "lum" => 75,
                    "alpha" => 1.0,
                ],
            ],
        ];
        $this->handleCases($cases, "parseHslString");
    }
}
