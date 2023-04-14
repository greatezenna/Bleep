<?php

require "customError.php";

/**
 * 
 * 
 * A php render function that renders a template file `views/$templateName.bleep.php`
 * 
 * Example:::
 * ```php
 * $data = ["title" => "Hello World"];
 * render("test", $data)
 * ```
 * 
 * @return string
 */

 $tempLiterals = [
    "variable" => '{{ $data }}',
    "nullable" => '{{ $data || null || \'this\' }}',
    "if" => '{& if &} $cond {& else if &} $cond {& endif &}',
    "style" => '{# style @link=\'style.css\' #}',
    "script" => '{# script @link=\'script.js\' #}',
    "loop" => '{% loop @template=\'post\' @data=\'$posts\' %}'
];

$variable = $tempLiterals["variable"];
$nullable = $tempLiterals["nullable"];
$ifLoop = $tempLiterals["if"];
$style = $tempLiterals["style"];
$script = $tempLiterals["script"];


function render_style($path)
{
    if (file_exists($path)) {
        $file_contents = file_get_contents($path);
    } else {
        $err = new CustomError();
        return $err->notFound();
        die;
    }
    $styleP = "<style> $file_contents </style>";
    return $styleP;
}

function render_script($path)
{
    if (file_exists($path)) {
        $file_contents = file_get_contents($path);
    } else {
        $err = new CustomError();
        return $err->notFound();
        die;
    }
    $scriptP = "<script> $file_contents </script>";
    return $scriptP;
}

function render_loop(string $templateName, array $dataArray, string $folder = "views") {
    $templatePath = "$folder/$templateName.bleep.php";
    if (file_exists($templatePath)) {
        $file_contents = file_get_contents($templatePath);
        $result = '';
        foreach ($dataArray as $data) {
            $temp = $file_contents;
            foreach ($data as $key => $value) {
                $temp = str_replace("{{ \$$key }}", $value, $temp);
            }
            $result .= $temp;
        }
        return $result;
    } else {
        $err = new CustomError("File not found");
        return $err->notFound();
    }
}


function return_variables($path)
{
    $file_contents = file_get_contents($path);

    preg_match_all('/{{\s*(\$\w+)\s*}}/', $file_contents, $matches);

    $style_matches = [];
    preg_match_all('/{#\s*style\s*@link=\'([^\']+)\'\s*#}/', $file_contents, $style_matches, PREG_SET_ORDER);

    $script_matches = [];
    preg_match_all('/{#\s*script\s*@link=\'([^\']+)\'\s*#}/', $file_contents, $script_matches, PREG_SET_ORDER);

    return array_merge($matches[1], $style_matches, $script_matches);
}

function render(string $templateName, array $data, string $folder = "views")
{
    $templatePath = "$folder/$templateName.bleep.php";
    if (file_exists($templatePath)) {
        $file_contents = file_get_contents($templatePath);
        $result = '';
        $matches = [];
        preg_match_all('/{%\s*loop\s*@template=\'([^\']+)\'\s*@data=\'\$([^\']+)\'\s*%}/', $file_contents, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $loopTemplate = $match[1];
            $loopData = $data[$match[2]];
            $loopResult = render_loop($loopTemplate, $loopData, $folder);
            $file_contents = str_replace($match[0], $loopResult, $file_contents);
        }
        foreach ($data as $key => $value) {
            $file_contents = str_replace("{{ \$$key }}", (string) $value, $file_contents);
        }
        $file_contents = preg_replace('/{&\s*if\s*&}/', '<?php if (', $file_contents);
        $file_contents = preg_replace('/{&\s*else if\s*&}/', '<?php } else if (', $file_contents);
        $file_contents = preg_replace('/{&\s*endif\s*&}/', '<?php } ?>', $file_contents);
        $file_contents = preg_replace_callback('/{#\s*style\s*@link=\'([^\']+)\'\s*#}/', function ($match) use ($folder) {
            return render_style("$folder/{$match[1]}");
        }, $file_contents);
        $file_contents = preg_replace_callback('/{#\s*script\s*@link=\'([^\']+)\'\s*#}/', function ($match) use ($folder) {
            return render_script("$folder/{$match[1]}");
        }, $file_contents);
        eval("?>" . $file_contents);
    } else {
        $err = new CustomError("File not found");
        return $err->notFound();
    }
}


function render_conditions(string $template, array $data) {
    $template = preg_replace_callback('/{&\s*if\s*&}\s*(.*?)\s*{&\s*else if\s*&}\s*(.*?)\s*{&\s*endif\s*&}/s', function($matches) use ($data) {
        if (eval("return {$matches[1]};")) {
            return $matches[1];
        } else if (eval("return {$matches[2]};")) {
            return $matches[2];
        } else {
            return '';
        }
    }, $template);

    return preg_replace_callback('/{&\s*if\s*&}\s*(.*?)\s*{&\s*endif\s*&}/s', function($matches) use ($data) {
        if (eval("return {$matches[1]};")) {
            return $matches[1];
        } else {
            return '';
        }
    }, $template);
}
