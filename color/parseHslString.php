<?php

/**
 * Parses an HSL (Hue, Saturation, Lightness) color string and returns its components.
 *
 * This function supports both legacy HSL(A) formats (comma-separated) and modern formats (space-separated).
 * 
 * Examples of supported formats:
 * - Legacy: `hsl(120, 100%, 50%)`, `hsla(120, 100%, 50%, 0.5)`
 * - Modern: `hsl(120 100% 50%)`, `hsl(120 100% 50% / 50%)`
 *
 * @param string $hslString The HSL(A) color string to parse.
 * 
 * @return array An associative array containing the parsed HSL(A) components:
 *               - "hue" (int): The hue value (0-360).
 *               - "sat" (int): The saturation value (0-100).
 *               - "lum" (int): The lightness value (0-100).
 *               - "alpha" (float): The alpha value (0-1 for legacy format, 0-100 for modern format).
 * 
 * @throws \Exception If the input string is not a valid HSL(A) format.
 * @throws \Exception If any of the HSL(A) components are out of their valid ranges.
 * @link https://developer.mozilla.org/en-US/docs/Web/CSS/color_value/hsl
 */
function parseHslString(string $hslString): array
{
    $isLegacy = str_contains($hslString, ",");
    if ($isLegacy) {
        $match = preg_match("/^hsl[a]?\(\s*([0-9]+)(?:deg)?\s*,\s*([0-9]+)%?\s*,\s*([0-9]+)%?(?:\s*,\s*(0|1|0\.\d+))?\s*\)$/", $hslString, $matches);
    } else {
        $match = preg_match("/^hsl[a]?\(\s*([0-9]+)(?:deg)?\s*([0-9]+)%?\s*([0-9]+)%?\s*(?:\/\s*([0-9]+)%?)?\s*\)$/", $hslString, $matches);
    }
    if (!$match) {
        throw new \InvalidArgumentException("Invalid HSL color format: $hslString");
    }
    $hue = intval($matches[1]);
    $sat = intval($matches[2]);
    $lum = intval($matches[3]);
    $alpha = floatval($matches[4] ?? ($isLegacy ? "1" : "100"));
    if (!(0 <= $hue && $hue <= 360)) {
        throw new \InvalidArgumentException("Hue value out of range: $hue");
    }
    if (!(0 <= $sat && $sat <= 100)) {
        throw new \InvalidArgumentException("Saturation value out of range: $sat");
    }
    if (!(0 <= $lum && $lum <= 100)) {
        throw new \InvalidArgumentException("Lightness value out of range: $lum");
    }
    if ($isLegacy) {
        if (!(0 <= $alpha && $alpha <= 1)) {
            throw new \InvalidArgumentException("Alpha value out of range: $alpha");
        }
    } else {
        if (!(0 <= $alpha && $alpha <= 100)) {
            throw new \InvalidArgumentException("Alpha value out of range: $alpha");
        }
    }
    return [
        "hue" => $hue,
        "sat" => $sat,
        "lum" => $lum,
        "alpha" => $alpha,
    ];
}
