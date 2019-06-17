<?php

return (function() {
	return function($callable) {
		$meta = new \ReflectionFunction($callable);
		$parameters = [];

		foreach ($meta->getParameters() as $parameter) {
			\array_push($parameters, "\$".$parameter->getName());
		}

		$parameters = \sizeof($parameters) ? \implode(", ", $parameters) : "void";

		return "[callable]($parameters)";
	};
})();
