<?php

require_once(__DIR__.'/common-functions.php');
require_once(__DIR__.'/url-shorten-functions.php');

if (!preg_match('|^[0-9a-zA-Z]{1,6}$|', $_GET['url'])) {
    die('That is not a valid short url');
}

$config = require(__DIR__.'/config/config.inc.php');

$uri = $_SERVER["REQUEST_URI"];

$slug = getSlugFromUri($uri);

$url = getURLFromDatabase($slug, $config);

header('HTTP/1.1 301 Moved Permanently');
header('Location: ' .  $url);

?>
