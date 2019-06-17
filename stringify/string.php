<?php

return (function() {
	/**
	 * Checks whether $ascii is printable or not.
	 */
	$fn_escape_string_is_printable = function($ascii) {
		return ($ascii >= 32) && (126 >= $ascii);
	};

	/**
	 * Escapes string $string.
	 */
	$fn_escape_string = function($string, $color) use(
		$fn_escape_string_is_printable
	) {
		$fn_color = require __DIR__."/../color.php";
		$escaped_string = "";

		for ($i = 0, $len = \strlen($string); $i < $len; ++$i) {
			$character = $string[$i];
			$ascii     = \ord($character);

			if ($fn_escape_string_is_printable($ascii)) {
				$escaped_string .= $character;
			} else {
				$tmp = "";

				// check special characters like
				// new line, line feed etc.
				switch ($character) {
					case "\n":
						$tmp = "\\n";
					break;

					case "\t":
						$tmp = "\\t";
					break;

					case "\r":
						$tmp = "\\r";
					break;

					// convert to hex
					default:
						$hex = \strtoupper(\dechex($ascii));
						// pad with zeros
						$hex = \str_pad($hex, 2, "0", \STR_PAD_LEFT);

						$tmp = "\\x$hex";
				}

				$escaped_string .= $fn_color($tmp, $color ? "cyan" : "");
			}
		}

		return $escaped_string;
	};

	return function($string, $color) use(
		$fn_escape_string
	) {
		return "[string](\"".$fn_escape_string($string, $color)."\")";
	};
})();
