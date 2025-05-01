<?php

/**
 * Builds an HSL(A) color string from its components.
 *
 * This function supports both legacy HSL(A) formats (comma-separated) and modern formats (space-separated).
 *
 * @param array $hsl An associative array containing the HSL(A) components:
 *                   - "hue" (int): The hue value (0-360).
 *                   - "sat" (int): The saturation value (0-100).
 *                   - "lum" (int): The lightness value (0-100).
 *                   - "alpha" (float): The alpha value (0-1).
 * @param bool $omitAlphaWhenOpaque Whether to omit the alpha value if it is 1 (default: true).
 * @param bool $isLegacy Whether to use the legacy format (default: false).
 *
 * @return string The formatted HSL(A) color string.
 */
function buildHslString(array $hsl, bool $omitAlphaWhenOpaque = true, bool $isLegacy = false): string
{
    validation: {
        if (count($hsl) < 3) {
            throw new \InvalidArgumentException("Invalid HSL array format: " . json_encode($hsl));
        }
        if (!isset($hsl["hue"], $hsl["sat"], $hsl["lum"])) {
            $missingKeys = array_diff(["hue", "sat", "lum"], array_keys($hsl));
            throw new \InvalidArgumentException("Missing HSL components in array: " . json_encode($hsl) . ". Missing keys: " . implode(", ", $missingKeys));
        }
        if (!is_int($hsl["hue"]) || $hsl["hue"] < 0 || $hsl["hue"] > 360) {
            throw new \InvalidArgumentException("Invalid hue value: " . $hsl["hue"]);
        }
        if (!is_int($hsl["sat"]) || $hsl["sat"] < 0 || $hsl["sat"] > 100) {
            throw new \InvalidArgumentException("Invalid saturation value: " . $hsl["sat"]);
        }
        if (!is_int($hsl["lum"]) || $hsl["lum"] < 0 || $hsl["lum"] > 100) {
            throw new \InvalidArgumentException("Invalid lightness value: " . $hsl["lum"]);
        }
        $hsl["alpha"] = $hsl["alpha"] ?? 1;
        $hskl["alpha"] = (float) $hsl["alpha"];
        if ($hsl["alpha"] < 0 || $hsl["alpha"] > 1) {
            throw new \InvalidArgumentException("Invalid alpha value: " . $hsl["alpha"]);
        }
    }
    $omitAlpha = $omitAlphaWhenOpaque && $hsl["alpha"] == 1;
    if ($isLegacy) {
        $hsl["alpha"] = (float) sprintf("%.3f", $hsl["alpha"]);
        return sprintf(
            $omitAlpha ? "hsl(%d, %d%%, %d%%)" : "hsl(%d, %d%%, %d%%, %s)",
            $hsl["hue"],
            $hsl["sat"],
            $hsl["lum"],
            $hsl["alpha"],
        );
    }
    $hsl["alpha"] = (float) sprintf("%.1f", round($hsl["alpha"] * 100, 1));
    return sprintf(
        $omitAlpha ? "hsl(%d %d %d)" : "hsl(%d %d %d / %s%%)",
        $hsl["hue"],
        $hsl["sat"],
        $hsl["lum"],
        $hsl["alpha"],
    );
}
