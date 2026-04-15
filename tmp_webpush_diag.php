<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
var_dump(function_exists('openssl_pkey_new'));
var_dump(defined('OPENSSL_KEYTYPE_EC'));
var_dump(in_array('prime256v1', openssl_get_curve_names(), true));
$key = openssl_pkey_new([
    'curve_name' => 'prime256v1',
    'private_key_type' => OPENSSL_KEYTYPE_EC,
]);
var_dump($key);
while ($msg = openssl_error_string()) {
    echo $msg, PHP_EOL;
}
