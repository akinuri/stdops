const fs = require("fs");
const path = require("path");

/**
 * Loads a JS file and evaluates it in the global scope
 * so that top-level functions become available in the test file.
 */
function load(relativePath) {
    const fullPath = path.resolve(__dirname, "../../", relativePath);
    const code = fs.readFileSync(fullPath, "utf8");
    (0, eval)(code); // forces eval in global scope
}

module.exports = {
    load,
};
