# Bleep PHP Rendering Library

Bleep PHP is a PHP rendering library that renders a template file `views/$templateName.bleep.php` and returns it as a string. It uses custom literals to simplify the syntax, making it easier and more intuitive to use.

## Installation

To use Bleep PHP, simply download or clone the repository and require the `customError.php` file in your project:

```sh
$ git clone https://github.com/greatezenna/Bleep.git
```
```php
require "customError.php"
```

## Usage 

To use the `render` function, simply call it with the template name and data you want to render:
```php
$data = ["title" => "Hello World"];
render("test", $data);
```

By default, Bleep PHP will look for the template file in the views directory. You can change this by specifying a different folder:
```php
render("test", $data, "templates");
```

## Custom Literals
Bleep PHP uses custom literals to simplify the syntax of the template files. These literals are:

* `{{ $data }}`: Renders a variable named `$data`
* `{{ $data || null || 'this' }}`: Renders a nullable variable named `$data` with a fallback value of null or 'this'.
* `{& if &} $cond {& else if &} $cond {& endif &}`: Renders an if statement with an optional else if statement. $cond should be a valid PHP boolean expression.
* `{# style @link='style.css' #}`: Renders a link tag for a CSS file with the specified path.
* `{# script @link='script.js' #}`: Renders a script tag for a JavaScript file with the specified path.
* `{% loop @template='post' @data='$posts' %}`: Renders a loop with the specified template and data. $posts should be a valid PHP array.

## Examples 
Here are some examples of how to use Bleep PHP:

Rendering a variable.
```php
$data = ["name" => "John"];
render("test", $data);
```

In the `test.bleep.php` template file:
```php
<h1>{{ $name }}</h1>
```

Rendering a Nullable Variable
```php
$data = ["name" => null];
render("test", $data);
```

In the `test.bleep.php` template file: 
```html
<h1>{{ $name || null || 'Guest' }}</h1>
```

Rendering an If Statement
```php 
$data = ["age" => 20];
render("test", $data);
```

In the test.bleep.php template file: 
```php 
{& if &} $age < 18 {& else if &} $age > 18 {& endif &}
```

Rendering a Style File
```php 
render("test", [], "templates");
```

In the ```test.bleep.php``` template file:
```php
{# style @link='style.css' #}
```

Rendering a Script File
```php 
render("test", [], "templates");
```

In the test.bleep.php template file: 
```php 
{# script @link='script.js' #}
```

Rendering a Loop
```php 
$data = [
    "posts" => [
        ["title" => "Post 1"],
        ["title" => "Post 2"],
        ["title" => "Post 3"],
    ]
];
render("test", $data);
```
