<?php
/**
 * Inspired by https://github.com/owncloud/core/blob/master/lib/private/appframework/http/request.php#L523
 */
function getServerProtocol() {
  if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
      if (strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], ',') !== false) {
          $parts = explode(',', $_SERVER['HTTP_X_FORWARDED_PROTO']);
          $proto = strtolower(trim($parts[0]));
      } else {
          $proto = strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']);
      }
      // Verify that the protocol is always HTTP or HTTPS
      // default to http if an invalid value is provided
      return $proto === 'https' ? 'https' : 'http';
  }
  if (isset($_SERVER['HTTPS'])
      && $_SERVER['HTTPS'] !== null
      && $_SERVER['HTTPS'] !== 'off'
      && $_SERVER['HTTPS'] !== '') {
      return 'https';
  }
  return 'http';
}

function getRequestHostname() {
  if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
    return strtolower($_SERVER['HTTP_X_FORWARDED_HOST']);
  }
  return strtolower($_SERVER['HTTP_HOST']);
}

function updateRedirections($id, $config) {
    mysql_query('UPDATE ' . $config["db_table"] . ' SET referrals=referrals+1 WHERE id="' . mysql_real_escape_string($$id) . '"');
    closeDatabaseConnection();
}

function getURLFromDatabase($id, $config) {
    connectToDatabase($config["db_host"], $config["db_name"], $config["db_user"], $config["db_pass"]);
    $long_url = mysql_result(mysql_query('SELECT long_url FROM ' . $config["db_table"] . ' WHERE id="' . mysql_real_escape_string($id) . '"'), 0, 0);
    if (!$config["track"]) { // In case the redirections should be counted, the db connection should stay open to update the count
        closeDatabaseConnection();
    }
    
    return $long_url;
}

function insertURL($url, $config) {
    connectToDatabase($config["db_host"], $config["db_name"], $config["db_user"], $config["db_pass"]);
    mysql_query('LOCK TABLES ' . $config["db_table"] . ' WRITE;');
	mysql_query('INSERT INTO ' . $config["db_table"] . ' (long_url, created, creator) VALUES ("' . mysql_real_escape_string($url_to_shorten) . '", "' . time() . '", "' . mysql_real_escape_string($_SERVER['REMOTE_ADDR']) . '")');
	$shortened_url = getShortenedURLFromID(mysql_insert_id());
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

function getShortenedURLFromID($integer, $base = ALLOWED_CHARS) {
	$length = strlen($base);
	while ($integer > $length - 1) {
		$out = $base[fmod($integer, $length)] . $out;
		$integer = floor( $integer / $length );
	}
	return $base[$integer] . $out;
}

function getIDFromShortenedURL ($string, $base = ALLOWED_CHARS) {
	$length = strlen($base);
	$size = strlen($string) - 1;
	$string = str_split($string);
	$out = strpos($base, array_pop($string));
	foreach($string as $i => $char) {
		$out += strpos($base, $char) * pow($length, $size - $i);
	}
	return $out;
}
?>