<?php

$n = 17;
$i = $r = 0;
while ($r<$n) {
	$r = fact($i++);
}
echo $r;

function fact($n)
{
	$r = 1;
	for ($i=1; $i <= $n; $i++) { 
		$r *= $i;
	}
	return $r;
}

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
exit;
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
array_walk($q, function ($q) {
	file_put_contents($q,str_replace(
		[
			'$that->buildMarketUrl'
		],
		[
			'\App\Http\Controllers\HomeController::buildMarketUrl'
		],
		file_get_contents($q),
		$n
	));
	print $q . ' => ' . $n . PHP_EOL;
});