/**
 * Parses an HSL (Hue, Saturation, Lightness) color string and returns its components.
 *
 * This function supports both legacy HSL(A) formats (comma-separated) and modern formats (space-separated).
 *
 * Examples of supported formats:
 * - Legacy: `hsl(120, 100%, 50%)`, `hsla(120, 100%, 50%, 0.5)`
 * - Modern: `hsl(120 100% 50%)`, `hsl(120 100% 50% / 50%)`
 *
 * @param {string} hsl The HSL(A) color string to parse.
 *
 * @return {Object} An object containing the parsed HSL(A) components:
 *                  - "hue" (number): The hue value (0-360).
 *                  - "sat" (number): The saturation value (0-100).
 *                  - "lum" (number): The lightness value (0-100).
 *                  - "alpha" (number): The alpha value (0-1 for legacy format, 0-100 for modern format).
 *
 * @throws {Error} If the input string is not a valid HSL(A) format.
 * @throws {Error} If any of the HSL(A) components are out of their valid ranges.
 */
function parseHslString(hsl) {
    const isLegacy = hsl.includes(",");
    let matches;
    validateMatch: {
        if (isLegacy) {
            const regex = /^hsl[a]?\(\s*(\d+)(?:deg)?\s*,\s*(\d+)%?\s*,\s*(\d+)%?(?:\s*,\s*(0|1|0?\.\d+))?\s*\)$/i;
            matches = hsl.match(regex);
        } else {
            const regex = /^hsl[a]?\(\s*(\d+)(?:deg)?\s*(\d+)%?\s*(\d+)%?\s*(?:\/\s*(\d+)%?)?\s*\)$/i;
            matches = hsl.match(regex);
        }
        if (!matches) {
            throw new Error(`Invalid HSL color format: ${hsl}`);
        }
    }
    const hue = parseInt(matches[1]);
    const sat = parseInt(matches[2]);
    const lum = parseInt(matches[3]);
    let alpha = parseFloat(matches[4] ?? (isLegacy ? "1" : "100"));
    if (!isLegacy) {
        alpha /= 100;
    }
    validateValues: {
        if (hue < 0 || hue > 360) {
            throw new Error(`Hue value out of range: ${hue}`);
        }
        if (sat < 0 || sat > 100) {
            throw new Error(`Saturation value out of range: ${sat}`);
        }
        if (lum < 0 || lum > 100) {
            throw new Error(`Lightness value out of range: ${lum}`);
        }
        if (alpha < 0 || alpha > 1) {
            throw new Error(`Alpha value out of range: ${alpha}`);
        }
    }
    return {
        hue,
        sat,
        lum,
        alpha: round(alpha * 1000) / 1000, // round to 3 decimal places
    };
}
