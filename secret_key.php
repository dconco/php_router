<?php

/**
 * GENERATE NEW SECRET KEY
 */
function secret_key_generate(int $length) {
    $key = base64_encode(random_bytes($length));
    return $key;
}