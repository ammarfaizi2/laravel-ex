<?php
session_start();
if (isset($_GET["destroy"])) {
	session_destroy();
	header("location:?");
	exit;
}
if (! empty($_GET) && is_array($_GET)) {
	foreach($_GET as $k => $w) {
		$_SESSION[$k] = $w;
	}
	header("location:?");
	exit;
}
header("Content-type:application/json");
print json_encode($_SESSION, 128);