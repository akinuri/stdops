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
 * @param string $hsl The HSL(A) color string to parse.
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
function parseHslString(string $hsl): array
{
    $isLegacy = str_contains($hsl, ",");
    validateMatch: {
        if ($isLegacy) {
            $match = preg_match(
                "/^hsl[a]?\(\s*(\d+)(?:deg)?\s*,\s*(\d+)%?\s*,\s*(\d+)%?(?:\s*,\s*(0|1|0?\.\d+))?\s*\)$/i",
                $hsl,
                $matches
            );
        } else {
            $match = preg_match(
                "/^hsl[a]?\(\s*(\d+)(?:deg)?\s*(\d+)%?\s*(\d+)%?\s*(?:\/\s*(\d+)%?)?\s*\)$/i",
                $hsl,
                $matches
            );
        }
        if (!$match) {
            throw new \InvalidArgumentException("Invalid HSL color format: $hsl");
        }
    }
    $hue = intval($matches[1]);
    $sat = intval($matches[2]);
    $lum = intval($matches[3]);
    $alpha = floatval($matches[4] ?? ($isLegacy ? "1" : "100"));
    if (!$isLegacy) {
        $alpha /= 100;
    }
    validateValues: {
        if (!(0 <= $hue && $hue <= 360)) {
            throw new \InvalidArgumentException("Hue value out of range: $hue");
        }
        if (!(0 <= $sat && $sat <= 100)) {
            throw new \InvalidArgumentException("Saturation value out of range: $sat");
        }
        if (!(0 <= $lum && $lum <= 100)) {
            throw new \InvalidArgumentException("Lightness value out of range: $lum");
        }
        if (!(0 <= $alpha && $alpha <= 1)) {
            throw new \InvalidArgumentException("Alpha value out of range: $alpha");
        }
    }
    return [
        "hue" => $hue,
        "sat" => $sat,
        "lum" => $lum,
        "alpha" => round($alpha, 3),
    ];
}
