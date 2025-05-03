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
    return hslClamp($hsl);
}

function hslAdd(
    array $hsl,
    int|null $hue = null,
    int|null $sat = null,
    int|null $lum = null,
    float|null $alpha = null,
): array {
    if ($hue !== null)   $hsl["hue"]   += $hue;
    if ($sat !== null)   $hsl["sat"]   += $sat;
    if ($lum !== null)   $hsl["lum"]   += $lum;
    if ($alpha !== null) $hsl["alpha"] += $alpha;
    return hslClamp($hsl);
}

function hslMul(
    array $hsl,
    int|null $hue = null,
    int|null $sat = null,
    int|null $lum = null,
    float|null $alpha = null
): array {
    if ($hue !== null)   $hsl["hue"]   *= $hue;
    if ($sat !== null)   $hsl["sat"]   *= $sat;
    if ($lum !== null)   $hsl["lum"]   *= $lum;
    if ($alpha !== null) $hsl["alpha"] *= $alpha;
    return hslClamp($hsl);
}

function hslClamp(array $hsl): array
{
    if (isset($hsl["hue"]))   $hsl["hue"]   = (int) max(0, min(360, round($hsl["hue"])));
    if (isset($hsl["sat"]))   $hsl["sat"]   = (int) max(0, min(100, round($hsl["sat"])));
    if (isset($hsl["lum"]))   $hsl["lum"]   = (int) max(0, min(100, round($hsl["lum"])));
    if (isset($hsl["alpha"])) $hsl["alpha"] = max(0, min(1, round($hsl["alpha"], 3)));
    return $hsl;
}
