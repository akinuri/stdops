## Color

- [`buildHslString(array $hsl): string`](https://github.com/akinuri/stdops/blob/6b3208589338edbce45949fc97cfe8ee23aa409a/color/buildHslString.php#L18)
- [`parseHslString(string $hslString): array`](https://github.com/akinuri/stdops/blob/6b3208589338edbce45949fc97cfe8ee23aa409a/color/parseHslString.php#L24)
- Operations
  - [`hslSet(array $hsl, $hue/$sat/$lum/$alpha = ...): array`](https://github.com/akinuri/stdops/blob/625ae0441242b026afd4cf7d8e19823bfe81b084/color/hslOps.php#L25)
  - [`hslAdd(array $hsl, $hue/$sat/$lum/$alpha = ...): array`](https://github.com/akinuri/stdops/blob/625ae0441242b026afd4cf7d8e19823bfe81b084/color/hslOps.php#L43)
  - [`hslMul(array $hsl, $hue/$sat/$lum/$alpha = ...): array`](https://github.com/akinuri/stdops/blob/625ae0441242b026afd4cf7d8e19823bfe81b084/color/hslOps.php#L64)
  - [`hslOps(array $hsl, string ...$ops): array`](https://github.com/akinuri/stdops/blob/cd9dedb8c2994350502f460e71e95150298d4966/color/hslOps.php#L103)
- Other
  - [`isValidHslArray(array $hsl): bool`](https://github.com/akinuri/stdops/blob/625ae0441242b026afd4cf7d8e19823bfe81b084/color/hslOps.php#L3)
  - [`isHslArray(array $hsl): bool`](https://github.com/akinuri/stdops/blob/625ae0441242b026afd4cf7d8e19823bfe81b084/color/hslOps.php#L18)
  - [`hslClamp(array $hsl): array`](https://github.com/akinuri/stdops/blob/625ae0441242b026afd4cf7d8e19823bfe81b084/color/hslOps.php#L85)
  - [`parseHslOp(string $op): array|null`](https://github.com/akinuri/stdops/blob/cd9dedb8c2994350502f460e71e95150298d4966/color/hslOps.php#L137)
