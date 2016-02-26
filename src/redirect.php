<?php
/*
 * First authored by Brian Cray
 * License: http://creativecommons.org/licenses/by/3.0/
 * Contact the author at http://briancray.com/
 */

require_once("url-shorten-functions.php");

if(!preg_match('|^[0-9a-zA-Z]{1,6}$|', $_GET['url'])) {
	die('That is not a valid short url');
}

$config = require('config.php');

$shortened_id = getIDFromShortenedURL($_GET['url']);

$long_url = getURLFromDatabase($shortened_id, $config);

if ($config["track"]) {
	updateRedirections($shortened_id, $config);
}

header('HTTP/1.1 301 Moved Permanently');
header('Location: ' .  $long_url);

?>