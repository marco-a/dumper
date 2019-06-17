<?php

return (function() {
	return function($boolean) {
		return "[boolean](".($boolean ? "true" : "false").")";
	};
})();
