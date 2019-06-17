<?php

return (function() {
	return function($resource) {
		$meta = \stream_get_meta_data($resource);
		$type = $meta["stream_type"] === "dir" ? "dir" : "file";
		$mode = $meta["mode"];

		if ($type === "dir") {
			return "[resource](directory, $mode)";
		}

		$uri = \array_key_exists("uri", $meta) ? $meta["uri"] : NULL;

		if ($uri === NULL) {
			return "[resource](file, $mode)";
		}

		return "[resource](file, $uri, $mode)";
	};
})();
