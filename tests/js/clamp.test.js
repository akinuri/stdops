const { load } = require("./test-utils");

load("math/js/clamp.js");

test("clamp", () => {
    expect(clamp(-1, 0, 10)).toBe(0);
    expect(clamp(0, 0, 10)).toBe(0);
    expect(clamp(3, 0, 10)).toBe(3);
    expect(clamp(10, 0, 10)).toBe(10);
    expect(clamp(11, 0, 10)).toBe(10);
});
