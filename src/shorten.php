<?php

require_once(__DIR__.'/common-functions.php');
require_once(__DIR__.'/url-shorten-functions.php');

// Check request method, only post is allowed
$method = $_SERVER['REQUEST_METHOD'];
if ('POST' != $method) {
  header('Location: index.php');
}

$urlToShorten = getMandatoryPostParameter('longurl');
$baseUrl = getServerProtocol()."://".getRequestHostname();

// Validate the url
if (validateUrl($urlToShorten)) {
  $config = require(__DIR__.'/config/config.inc.php');

  // check if the client IP is allowed to shorten
  if (is_array($config["limit_to_ips"]) && count($config["limit_to_ips"]) > 0 && !in_array($_SERVER['REMOTE_ADDR'], $config["limit_to_ips"])) {
    sendHttpReturnCodeAndMessage(403, 'You are not allowed to shorten URLs with this service.');
  }
  // check if the URL is reachable
  if (!$config["check_url"] || checkUrl($urlToShorten)) {
    $slug = createSlugFromUrl($urlToShorten);
    insertUrlAndSlug($urlToShorten, $slug, $config);
  } else {
    sendHttpReturnCodeAndMessage(400, "URL not valid");
  }
	
  echo $baseUrl."/".$slug;
}

?>
