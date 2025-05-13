<?php

function isValidHslArray(array $hsl): bool
{
    if (!isHslArray($hsl)) {
        return false;
    }
    $hsl["hue"] = intval($hsl["hue"]);
    $hsl["sat"] = intval($hsl["sat"]);
    $hsl["lum"] = intval($hsl["lum"]);
    $hsl["alpha"] = floatval($hsl["alpha"] ?? 1);
    return (0 <= $hsl["hue"] && $hsl["hue"] <= 360)
        && (0 <= $hsl["sat"] && $hsl["sat"] <= 100)
        && (0 <= $hsl["lum"] && $hsl["lum"] <= 100)
        && (0 <= $hsl["alpha"] && $hsl["alpha"] <= 1);
}

function isHslArray(array $hsl): bool
{
    return isset($hsl["hue"])
        && isset($hsl["sat"])
        && isset($hsl["lum"]);
}

function hslSet(
    array $hsl,
    int|null $hue = null,
    int|null $sat = null,
    int|null $lum = null,
    float|null $alpha = null
): array {
    if (!isValidHslArray($hsl)) {
        throw new \InvalidArgumentException("Invalid HSL array format.");
    }
    if ($hue !== null)   $hsl["hue"]   = $hue;
    if ($sat !== null)   $hsl["sat"]   = $sat;
    if ($lum !== null)   $hsl["lum"]   = $lum;
    if ($alpha !== null) $hsl["alpha"] = $alpha;
    $hsl = hslClamp($hsl);
    return $hsl;
}

function hslAdd(
    array $hsl,
    int|null $hue = null,
    int|null $sat = null,
    int|null $lum = null,
    float|null $alpha = null,
): array {
    if (!isValidHslArray($hsl)) {
        throw new \InvalidArgumentException("Invalid HSL array format.");
    }
    if ($hue !== null)   $hsl["hue"]   += $hue;
    if ($sat !== null)   $hsl["sat"]   += $sat;
    if ($lum !== null)   $hsl["lum"]   += $lum;
    if ($alpha !== null) {
        $hsl["alpha"] = $hsl["alpha"] ?? 1;
        $hsl["alpha"] += $alpha;
    }
    $hsl = hslClamp($hsl);
    return $hsl;
}

function hslMul(
    array $hsl,
    float|null $hue = null,
    float|null $sat = null,
    float|null $lum = null,
    float|null $alpha = null
): array {
    if (!isValidHslArray($hsl)) {
        throw new \InvalidArgumentException("Invalid HSL array format.");
    }
    if ($hue !== null)   $hsl["hue"]   *= $hue;
    if ($sat !== null)   $hsl["sat"]   *= $sat;
    if ($lum !== null)   $hsl["lum"]   *= $lum;
    if ($alpha !== null) {
        $hsl["alpha"] = $hsl["alpha"] ?? 1;
        $hsl["alpha"] *= $alpha;
    }
    $hsl = hslClamp($hsl);
    return $hsl;
}

function hslClamp(array $hsl): array
{
    if (!isHslArray($hsl)) {
        throw new \InvalidArgumentException("Invalid HSL array format.");
    }
    $hsl["hue"] = intval($hsl["hue"]);
    $hsl["sat"] = intval($hsl["sat"]);
    $hsl["lum"] = intval($hsl["lum"]);
    $hsl["hue"] = (int) max(0, min(360, round($hsl["hue"])));
    $hsl["sat"] = (int) max(0, min(100, round($hsl["sat"])));
    $hsl["lum"] = (int) max(0, min(100, round($hsl["lum"])));
    if (array_key_exists("alpha", $hsl)) {
        $hsl["alpha"] = floatval($hsl["alpha"] ?? 1);
        $hsl["alpha"] = max(0, min(1, round($hsl["alpha"], 3)));
    }
    return $hsl;
}

function hslOps(array $hsl, string ...$ops): array
{
    if (!isValidHslArray($hsl)) {
        throw new \InvalidArgumentException("Invalid HSL array format.");
    }
    foreach ($ops as $op) {
        $op = parseHslOp($op);
        if (!$op) {
            continue;
        }
        if ($op["operator"] == "=") {
            $args = ["hue" => null, "sat" => null, "lum" => null, "alpha" => null];
            $args[$op["channel"]] = $op["value"];
            $hsl = hslSet($hsl, ...$args);
        } else if (in_array($op["operator"], ["+", "-"])) {
            $args = ["hue" => null, "sat" => null, "lum" => null, "alpha" => null];
            if ($op["operator"] == "-") {
                $op["value"] = -$op["value"];
            }
            $args[$op["channel"]] = $op["value"];
            $hsl = hslAdd($hsl, ...$args);
        } else if (in_array($op["operator"], ["*", "/"])) {
            $args = ["hue" => null, "sat" => null, "lum" => null, "alpha" => null];
            if ($op["operator"] == "/") {
                $op["value"] = 1 / $op["value"];
            }
            $args[$op["channel"]] = $op["value"];
            $hsl = hslMul($hsl, ...$args);
        }
    }
    $hsl = hslClamp($hsl);
    return $hsl;
}

function parseHslOp(string $op): array|null
{
    if (preg_match("/^(hue|sat|lum|alpha)\s*([+\-*\/=])\s*(\-?\d+(?:\.\d+)?)/", $op, $matches)) {
        return [
            "channel" => $matches[1],
            "operator" => $matches[2],
            "value" => floatval($matches[3]),
        ];
    }
    return null;
}
