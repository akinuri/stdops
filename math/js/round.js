function round(number, precision = 0) {
    let factor = Math.pow(10, precision);
    return Math.round(number * factor) / factor;
}
