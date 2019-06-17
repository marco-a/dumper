# dumper

A function that nicely prints the value of PHP variables.

`PHP >= 7` required.

## Usage

```
<?php
$stringify = require __DIR__."/stringify.php";

echo $stringify("My variable");

```

## Type handling

### boolean, integer, float and string

Nothing too fancy here. Values are returned in the following format:

`[type](value)`.

For example a boolean with value `true`:

`[boolean](true)`

Strings are formatted to always use exactly one line.

Unprintable characters are represented in their hex format:

`[string]("Non-printable characters: \x88 \x89 \x90")`

### resource

If available the path and mode are added:

`[resource](type, uri, mode)`

For example an opened file:

`$fp = fopen(__FILE__, "rb");`

`[resource](file, /path/to/file, rb)`

### callable

Callables are returned like this:

`[callable](void)` if no parameters are present.

`[callable]($a, $b)` for a callback that takes two parameters `a` and `b`.

### array

Sequential arrays are returned like this:

```
[array](
	...items...
)
```

For example an array `[1, 2, 3, 4]`:

```
[array](
	[integer](1),
	[integer](2),
	[integer](3),
	[integer](4)
)
```

### associative array (dict)

Associative arrays are printed like a sequential array except keys are added:

```
[dict](
	...items...
)
```

For example: `["key" => "value"]`:

```
[dict](
	key => [string]("value")
)
```

### class instances (object)

All properties of a class are added, the `public` ones with their current value:

```
[class:CLASS_NAME](
	...properties...
)
```

For example: `class my_class { public $a = 1; }`:

```
[class:my_class](
	public $a -> [integer](1)
)
```

## Color output

You can specify color output with the second parameter `$color`:

- public, protected and private variables are colored in green, yellow and red.
- unprintable or special characters in a string are highlighted with cyan.