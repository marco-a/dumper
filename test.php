<?php

function dump($value) {
	static $stringify = NULL;

	if ($stringify === NULL) {
		$stringify = require __DIR__."/stringify.php";
	}

	return $stringify($value, true);
}

// needed for multi line outputs
$indent = function($output) {
	$out = [];

	foreach (\explode("\n", $output) as $i => $line) {
		\array_push($out, $i === 0 ? "$line" : "\t$line");
	}

	return \implode("\n", $out);
};

class my_class {
	private $private_var;
	protected $protected_var;

	public $test = 1337;

	public $dict = [
		"a" => [[1, 2]],
		"b" => [[3, 4]]
	];
}

echo "Booleans \n";

echo "\t".dump(true)."\n";
echo "\t".dump(false)."\n";

echo "Integers \n";

echo "\t".dump(1)."\n";
echo "\t".dump(-1)."\n";

echo "Strings \n";

echo "\t".dump("Hello, world!")."\n";
echo "\t".dump("Hello \n world!")."\n";
echo "\t".dump("Non-printable characters: \x88 \x89 \x90")."\n";

echo "Floats \n";

echo "\t".dump(1.3)."\n";
echo "\t".dump(NAN)."\n";
echo "\t".dump(INF)."\n";

echo "Callbacks \n";

echo "\t".dump(function() {})."\n";
echo "\t".dump(function($a, $b) {})."\n";

echo "Resources \n";

echo "\t".dump(\fopen(__FILE__, "rb"))."\n";
echo "\t".dump(\opendir(__DIR__))."\n";

echo "Arrays \n";

echo "\t".$indent(dump([1, 2, 3, 4]))."\n";
echo "\t".$indent(dump([[1, 2], [3, 4]]))."\n";

echo "Associative arrays \n";

echo "\t".$indent(dump(["key" => "value"]))."\n";
echo "\t".$indent(dump(["a" => [1, 2], "b" => [3, 4]]))."\n";
echo "\t".$indent(dump(["1" => new my_class, "2" => new my_class]))."\n";

echo "Classes \n";

echo "\t".$indent(dump(new my_class));
