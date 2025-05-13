// #region ==================== OPS

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

function hslSet(hsl, hue = null, sat = null, lum = null, alpha = null) {
    if (!isValidHslObject(hsl)) {
        throw new Error("Invalid HSL object format.");
    }
    hsl = { ...hsl };
    if (hue !== null) hsl.hue = hue;
    if (sat !== null) hsl.sat = sat;
    if (lum !== null) hsl.lum = lum;
    if (alpha !== null) hsl.alpha = alpha;
    return hslClamp(hsl);
}

function hslAdd(hsl, hue = null, sat = null, lum = null, alpha = null) {
    if (!isValidHslObject(hsl)) {
        throw new Error("Invalid HSL object format.");
    }
    hsl = { ...hsl };
    if (hue !== null) hsl.hue += hue;
    if (sat !== null) hsl.sat += sat;
    if (lum !== null) hsl.lum += lum;
    if (alpha !== null) {
        hsl.alpha = hsl.alpha ?? 1;
        hsl.alpha += alpha;
    }
    return hslClamp(hsl);
}

function hslMul(hsl, hue = null, sat = null, lum = null, alpha = null) {
    if (!isValidHslObject(hsl)) {
        throw new Error("Invalid HSL object format.");
    }
    hsl = { ...hsl };
    if (hue !== null) hsl.hue *= hue;
    if (sat !== null) hsl.sat *= sat;
    if (lum !== null) hsl.lum *= lum;
    if (alpha !== null) {
        hsl.alpha = hsl.alpha ?? 1;
        hsl.alpha *= alpha;
    }
    return hslClamp(hsl);
}

function hslOps(hsl, ...ops) {
    if (!isValidHslObject(hsl)) {
        throw new Error("Invalid HSL object format.");
    }
    ops.forEach((op) => {
        const parsedOp = parseHslOp(op);
        if (!parsedOp) {
            return;
        }
        const { channel, operator, value } = parsedOp;
        let args = { hue: null, sat: null, lum: null, alpha: null };
        args[channel] = value;
        if (operator === "=") {
            hsl = hslSet(hsl, args.hue, args.sat, args.lum, args.alpha);
        } else if (["+", "-"].includes(operator)) {
            if (operator === "-") {
                args[channel] = -value;
            } else {
                args[channel] = value;
            }
            hsl = hslAdd(hsl, args.hue, args.sat, args.lum, args.alpha);
        } else if (["*", "/"].includes(operator)) {
            if (operator === "/") {
                args[channel] = 1 / value;
            } else {
                args[channel] = value;
            }
            hsl = hslMul(hsl, args.hue, args.sat, args.lum, args.alpha);
        }
    });
    hsl = hslClamp(hsl);
    return hsl;
}

// #endregion

// #region ==================== UTILS

function isHslObject(hsl) {
    return hsl !== null && typeof hsl === "object" && "hue" in hsl && "sat" in hsl && "lum" in hsl;
}

function isValidHslObject(hsl) {
    if (!isHslObject(hsl)) {
        return false;
    }
    const hue = parseInt(hsl.hue, 10);
    const sat = parseInt(hsl.sat, 10);
    const lum = parseInt(hsl.lum, 10);
    const alpha = hsl.alpha !== undefined ? parseFloat(hsl.alpha) : 1;
    return isInRange(hue, 0, 360) && isInRange(sat, 0, 100) && isInRange(lum, 0, 100) && isInRange(alpha, 0, 1);
}

function hslClamp(hsl) {
    if (!isHslObject(hsl)) {
        throw new Error("Invalid HSL object format.");
    }
    hsl = { ...hsl };
    hsl.hue = round(parseInt(hsl.hue, 10));
    hsl.sat = round(parseInt(hsl.sat, 10));
    hsl.lum = round(parseInt(hsl.lum, 10));
    hsl.hue = clamp(hsl.hue, 0, 360);
    hsl.sat = clamp(hsl.sat, 0, 100);
    hsl.lum = clamp(hsl.lum, 0, 100);
    if ("alpha" in hsl) {
        hsl.alpha = parseFloat(hsl.alpha ?? 1);
        hsl.alpha = clamp(round(hsl.alpha * 1000) / 1000, 0, 1);
    }
    return hsl;
}

function parseHslOp(op) {
    const match = op.match(/^(hue|sat|lum|alpha)\s*([+\-*\/=])\s*(-?\d+(?:\.\d+)?)/);
    if (match) {
        return {
            channel: match[1],
            operator: match[2],
            value: parseFloat(match[3]),
        };
    }
    return null;
}

// #endregion
