/**
 * Builds an HSL(A) color string from its components.
 *
 * @param {Object} hsl - An object containing the HSL(A) components:
 *   - hue {number}: The hue value (0-360).
 *   - sat {number}: The saturation value (0-100).
 *   - lum {number}: The lightness value (0-100).
 *   - alpha {number} [optional]: The alpha value (0-1), default is 1.
 * @param {boolean} [omitAlphaWhenOpaque=true] - Whether to omit alpha if it's 1.
 * @param {boolean} [isLegacy=false] - Whether to use legacy comma-separated format.
 * @returns {string} The formatted HSL(A) color string.
 */
function buildHslString(hsl, omitAlphaWhenOpaque = true, isLegacy = false) {
    validation: {
        if (Object.keys(hsl).length < 3) {
            throw new Error("Invalid HSL object format: " + JSON.stringify(hsl));
        }
        const requiredKeys = ["hue", "sat", "lum"];
        const missingKeys = requiredKeys.filter((key) => !(key in hsl));
        if (missingKeys.length > 0) {
            throw new Error(
                "Missing HSL components in object: " + JSON.stringify(hsl) + ". Missing keys: " + missingKeys.join(", ")
            );
        }
        if (!Number.isInteger(hsl.hue) || hsl.hue < 0 || hsl.hue > 360) {
            throw new Error("Invalid hue value: " + hsl.hue);
        }
        if (!Number.isInteger(hsl.sat) || hsl.sat < 0 || hsl.sat > 100) {
            throw new Error("Invalid saturation value: " + hsl.sat);
        }
        if (!Number.isInteger(hsl.lum) || hsl.lum < 0 || hsl.lum > 100) {
            throw new Error("Invalid lightness value: " + hsl.lum);
        }
        hsl = { ...hsl };
        hsl.alpha = hsl.alpha !== undefined ? parseFloat(hsl.alpha) : 1;
        if (typeof hsl.alpha !== "number" || hsl.alpha < 0 || hsl.alpha > 1) {
            throw new Error("Invalid alpha value: " + hsl.alpha);
        }
    }
    const omitAlpha = omitAlphaWhenOpaque && hsl.alpha === 1;
    if (isLegacy) {
        const alphaStr = sprintf("%.3f", hsl.alpha);
        return sprintf(
            omitAlpha ? "hsl(%d, %d%%, %d%%)" : "hsl(%d, %d%%, %d%%, %s)",
            hsl.hue,
            hsl.sat,
            hsl.lum,
            alphaStr
        );
    } else {
        const alphaStr = sprintf("%.1f", Math.round(hsl.alpha * 1000) / 10);
        return sprintf(omitAlpha ? "hsl(%d %d %d)" : "hsl(%d %d %d / %s%%)", hsl.hue, hsl.sat, hsl.lum, alphaStr);
    }
}
