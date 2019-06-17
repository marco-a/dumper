<?php

return (function() {
	return function($string, $color) {
		switch ($color) {
			case "red":
				return "\033[0;31m$string\033[0m";

			case "green":
				return "\033[0;32m$string\033[0m";

			case "yellow":
				return "\033[0;33m$string\033[0m";

			case "cyan":
				return "\033[0;34m$string\033[0m";
		}

		return $string;
	};
})();
