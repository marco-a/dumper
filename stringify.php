<?php

return (function() {
	$fn_color = require __DIR__."/color.php";

	$fn_get_var_type = function($value) {
		if (\is_bool($value)) {
			return "boolean";
		} else if (\is_string($value)) {
			return "string";
		} else if (\is_resource($value)) {
			return "resource";
		} else if (\is_callable($value)) {
			return "callable";
		} else if (\is_float($value)) {
			return "float";
		} else if (\is_integer($value)) {
			return "integer";
		} else if (\is_null($value)) {
			return "NULL";
		}

		if (\is_array($value)) {
			if ($value === []) {
				return "array";
			} else if (\array_keys($value) === \range(0, \sizeof($value) - 1)) {
				return "array";
			}

			return "dict";
		}

		if (\is_object($value)) {
			return "object";
		}

		return "unknown";
	};

	$fn_indent = function($value) {
		$lines = \explode("\n", $value);
		$new   = [];

		foreach ($lines as $i => $line) {
			if ($i === 0) {
				\array_push($new, $line);
			} else {
				\array_push($new, "\t$line");
			}
		}

		return \implode("\n", $new);
	};

	$fn_stringify_array = function($array, $type, $color) use(
		$fn_indent
	) {
		$fn_stringify = require __FILE__;
		$elements     = [];

		foreach ($array as $key => $value) {
			$stringified_element = $fn_stringify($value, $color);

			// always indent return value (except for first line)
			$stringified_element = $fn_indent($stringified_element);

			// add key for dictionaries
			$key = ($type === "dict") ? "$key => " : "";

			\array_push($elements, "\t$key$stringified_element");
		}

		if (!\sizeof($elements)) {
			return "[$type]()";
		}

		$body = \implode(",\n", $elements);

		return "[$type](\n$body\n)";
	};

	$fn_get_member_visibility_label = function(\ReflectionProperty $property, $color) use(
		$fn_color
	) {
		if ($property->isPublic()) {
			return $fn_color("public", $color ? "green" : "");
		} else if ($property->isProtected()) {
			return $fn_color("protected", $color ? "yellow" : "");
		}

		return $fn_color("private", $color ? "red" : "");
	};

	$fn_stringify_object = function($object, $color) use(
		$fn_indent,
		$fn_get_member_visibility_label,
		$fn_color
	) {
		$fn_stringify = require __FILE__;
		$class = new \ReflectionClass($object);
		$class_name = $class->getName();
		$members = [];

		foreach ($class->getProperties() as $property) {
			$property_name  = $property->getName();
			$property_value = NULL;

			if ($property->isPublic()) {
				$property_value = $property->getValue($object);
			}

			$property_value = $fn_stringify($property_value, $color);
			$property_value = $fn_indent($property_value);

			$visibility_label = $fn_get_member_visibility_label($property, $color);

			$member  = "\t$visibility_label \$$property_name -> ";

			if ($property->isPublic()) {
				$member .= $property_value;
			} else {
				$member .= "<unkown>";
			}

			\array_push($members, $member);
		}

		if (!\sizeof($members)) {
			return "[class:$class_name]()";
		}

		$body = \implode(",\n", $members);

		return "[class:$class_name](\n$body\n)";
	};

	return function($value, $color = false) use(
		$fn_get_var_type,
		$fn_stringify_array,
		$fn_stringify_object
	) {
		$var_type = $fn_get_var_type($value);

		// array, dict and object are handled separately
		if (!\in_array($var_type, ["array", "dict", "object"])) {
			$fn = require __DIR__."/stringify/$var_type.php";

			return $fn($value, $color);
		}

		switch ($var_type) {
			case "array":
				return $fn_stringify_array($value, "array", $color);

			case "dict":
				return $fn_stringify_array($value, "dict", $color);

			case "object":
				return $fn_stringify_object($value, $color);
		}
	};
})();
