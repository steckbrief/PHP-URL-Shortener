<?php
/*
 * First authored by Brian Cray
 * License: http://creativecommons.org/licenses/by/3.0/
 * Contact the author at http://briancray.com/
 */

return [
// db options
    'db_name' => 'your db name',
    'db_user' => 'your db usernae',
    'db_password' => 'your db password',
    'db_host' => 'localhost',
    'db_table' => 'shortenedurls',
    // change to limit short url creation to a single IP
    'limit_to_ip' => '',
    // change to TRUE to start tracking referrals
    'track' => FALSE,
    // check if URL exists first
    'check_url' => FALSE,
    // do you want to cache?
    'cache' => TRUE,
];


// base location of script (include trailing slash)
define('BASE_HREF', 'http://' . $_SERVER['HTTP_HOST'] . '/');

// change to limit short url creation to a single IP
define('LIMIT_TO_IP', $_SERVER['REMOTE_ADDR']);

// change to TRUE to start tracking referrals
define('TRACK', FALSE);

// check if URL exists first
define('CHECK_URL', FALSE);

// change the shortened URL allowed characters
define('ALLOWED_CHARS', '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

// do you want to cache?
define('CACHE', TRUE);

// if so, where will the cache files be stored? (include trailing slash)
define('CACHE_DIR', dirname(__FILE__) . '/cache/');
?>