<?php
/*
 * First authored by Brian Cray
 * License: http://creativecommons.org/licenses/by/3.0/
 * Contact the author at http://briancray.com/
 */
require_once("url-shorten-functions.php");
$url_to_shorten = get_magic_quotes_gpc() ? stripslashes(trim($_REQUEST['longurl'])) : trim($_REQUEST['longurl']);

if(!empty($url_to_shorten) && preg_match('|^https?://|', $url_to_shorten)) {
	$config = require('config.php');

	// check if the client IP is allowed to shorten
	if ($_SERVER['REMOTE_ADDR'] != LIMIT_TO_IP) {
		die('You are not allowed to shorten URLs with this service.');
	}
	
	// check if the URL is valid
	if ($config["check_url"]) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url_to_shorten);
		curl_setopt($ch,  CURLOPT_RETURNTRANSFER, TRUE);
		$response = curl_exec($ch);
		$response_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if ($response_status == '404') {
			die('Not a valid URL');
		}
	}
	
	$shortened_url = insertURL($url_to_shorten, $config);

	echo BASE_HREF . $shortened_url;
}

?>