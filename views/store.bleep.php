<!DOCTYPE html>
<html>
<head>
  <title>{{ $title }}</title>
   {# style @link='style.css' #} 
</head>
<body>
  <h1>{{ $title }}</h1>
  <ul>
    {% loop @template='product' @data='$products' %}
  </ul>
  {# script @link='script.js' #} 
</body>
</html>
