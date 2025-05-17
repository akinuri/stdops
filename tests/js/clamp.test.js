const { load } = require("./test-utils");

load("math/js/clamp.js");

test("clamp", () => {
    expect(clamp(2, 0, 10)).toBe(2);
});
