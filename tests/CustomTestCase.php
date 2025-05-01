<?php

use PHPUnit\Framework\TestCase;

class CustomTestCase extends TestCase
{
    public function handleCases(array $cases, callable $callback)
    {
        foreach ($cases as $key => $case) {
            $caseName = "Case: {$key}";
            if ($case["name"] ?? null) {
                $caseName .= ", Name: {$case["name"]}";
            }
            $result = $callback(...$case["args"]);
            if ($case["compare"] ?? null) {
                $this->assertTrue(
                    $case["compare"]($result, $case["expected"]),
                    $caseName,
                );
            } else {
                $this->assertSame($case["expected"], $result, $caseName);
            }
        }
    }
}
