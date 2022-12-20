<?php

/**
 * @param int $min
 * @param int $max
 * @param int $decimals
 * @return float|int
 *
 * @since 2022-12-17
 * @author Roberto Cannella
 *
 * Returns a random float with the specified decimal spaces\
 * Example:
 *
 *
 */

function randFloat(int $min  /* The minimum number */, int $max /* The maximum number */, int $decimals = 0 /* How many decimal places? */):float {
    $scale = pow(10, $decimals);
    return mt_rand($min * $scale, $max * $scale) / $scale;
}


