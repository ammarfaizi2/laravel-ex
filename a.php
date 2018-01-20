<?php

/*$r = [];
foreach (scandir("app/Models") as $key => $value) {
	if ($value !== "." and $value !== "..") {
		$a = explode("\n", file_get_contents("app/Models/".$value));
		if (isset($a[3])) {
			if (trim($a[3]) === "") {
				$a[3] = "use DB;";
			}
			file_put_contents("app/Models/{$value}", implode("\n", $a));
			print $value.PHP_EOL;;
		}
	}
}
*/

$r = [];


function scanner($dir)
{
	$r = [];
	$dir = scandir($puredir = $dir);
	foreach ($dir as $val) {
		if ($val !== '.' && $val !== '..') {
			$val = $puredir.'/'.$val;
			if (is_dir($val)) {
				// $r[] = $val;
				foreach (scanner($val) as $val) {
					$r[] = $val;
				}
			} else {
				$r[] = $val;
			}
		}
	}
	return $r;
}


$q = scanner(__DIR__.'/resources/views');
var_dump($q);