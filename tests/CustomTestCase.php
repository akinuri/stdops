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
            if ($case["throws"] ?? null) {
                $throws = $case["throws"];
                $thrown = false;
                $result = undefined;
                $exception = null;
                try {
                    $result = $callback(...$case["args"]);
                } catch (Throwable $th) {
                    $exception = $th;
                    $thrown = true;
                }
                if ($thrown) {
                    if ($throws === true) {
                        $this->assertTrue($thrown, "success");
                    } else {
                        $this->assertSame($throws, $exception::class, $caseName);
                    }
                } else {
                    $this->fail(
                        sprintf(
                            "%s\nResult: %s\n%s",
                            $caseName,
                            $result === null
                                ? "null"
                                : ($result === false ? "false" : (string) $result),
                            "Expected exception was not thrown.",
                        )
                    );
                }
            } else {
                $result = $callback(...$case["args"]);
                if ($case["compare"] ?? null) {
                    $this->assertTrue(
                        $case["compare"](
                            $result,
                            $case["expected"] ?? null,
                            $case["args"]
                        ),
                        $caseName,
                    );
                } else {
                    if (!array_key_exists("expected", $case)) {
                        throw new Exception("Expected value is required.");
                    }
                    $this->assertSame($case["expected"], $result, $caseName);
                }
            }
        }
    }
}
