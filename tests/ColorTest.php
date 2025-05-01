<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

include __DIR__ . "/../color/parseHslString.php";

final class ColorTest extends TestCase
{
    public function test_parseHslString(): void
    {
        $this->assertSame([
            "hue" => 120,
            "sat" => 50,
            "lum" => 75,
            "alpha" => 1.0,
        ], parseHslString("hsl(120,50%,75%)"));
    }
}
