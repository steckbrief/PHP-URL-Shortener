<?php

function createSlugFromUrl($url) {
  return substr(base64_encode(md5($url)), 0, 6);
}

function getSlugFromUri($uri) {
  $lastSlash = strrpos($uri, '/') + 1;
  return substr($uri, $lastSlash);
}

function validateUrl($urlToShorten) {
  return !empty($urlToShorten) && preg_match('|^https?://|', $urlToShorten);
}

function checkUrl($url) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  $response = curl_exec($ch);
  $responseStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);
  return $responseStatus == '200' || $responseStatus == '201' || $responseStatus == '301' || $responseStatus == '303' || $responseStatus == '304' || $responseStatus == '307' || $responseStatus == '308' || $responseStatus == '401';
}

function updateRedirections($slug, $config) {
  mysql_query('UPDATE '.$config["db_table"].' SET referrals=referrals+1 WHERE slug="'.mysql_real_escape_string($slug).'"');
}

function getURLFromDatabase($slug, $config) {
  connectToDatabase($config["db_host"], $config["db_name"], $config["db_user"], $config["db_password"]);
  $url = mysql_result(mysql_query('SELECT url FROM '.$config["db_table"].' WHERE slug="'.mysql_real_escape_string($slug).'"'), 0, 0);
  if ($config["track"]) {
    updateRedirections($slug, $config);
  }
  closeDatabaseConnection();
  
  return $url;
}

function insertUrlAndSlug($url, $slug, $config) {
  connectToDatabase($config["db_host"], $config["db_name"], $config["db_user"], $config["db_password"]);
  mysql_query('LOCK TABLES '.$config["db_table"].' WRITE;');
  mysql_query('INSERT INTO '.$config["db_table"].' (slug, url, created, creator) VALUES ("'.mysql_real_escape_string($slug).'", "'.mysql_real_escape_string($url).'", "'.time().'", "'.mysql_real_escape_string($_SERVER['REMOTE_ADDR']).'")');
  mysql_query('UNLOCK TABLES');
  closeDatabaseConnection();
}

function connectToDatabase($dbHost, $dbName, $dbUser, $dbPass) {
  // connect to database
  mysql_connect($dbHost, $dbUser, $dbPass);
  mysql_select_db($dbName);
}

function closeDatabaseConnection() {
    mysql_close();
}

?>
