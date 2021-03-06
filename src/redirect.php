<?php

require_once(__DIR__.'/lib/functions.common.inc.php');
require_once(__DIR__.'/lib/functions.urlshorten.inc.php');

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
