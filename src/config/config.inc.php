<?php
/*
 * First authored by Brian Cray
 * License: http://creativecommons.org/licenses/by/3.0/
 * Contact the author at http://briancray.com/
 */

return [
// db options
    'db_name' => 'shorten',
    'db_user' => 'root',
    'db_password' => 'root',
    'db_host' => 'localhost',
    'db_table' => 'shortenedurls',
    // change to limit short url creation to a single IP
    'limit_to_ips' => '',
    // change to TRUE to start tracking referrals
    'track' => FALSE,
    // check if URL exists first
    'check_url' => FALSE,
    // do you want to cache?
    'cache' => TRUE,
];


// change the shortened URL allowed characters
define('ALLOWED_CHARS', '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

?>
