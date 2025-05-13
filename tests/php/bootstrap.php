<?php

require_once __DIR__ . "/CustomTestCase.php";

/**
 * Sometimes the NULL is a valid value,
 * and can't be used as an invalid value.
 */
define("undefined", "__undefined__");
