<?php

require_once("curl.class.php");

$curl = new Curl();

$curl = $curl->init();

$curl->URL("http://jquepjsaa?ver=4.5.2")->exec();
if ($curl->errno === 6) {
	echo $curl->errno;
}


?>
