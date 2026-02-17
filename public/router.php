<?php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = __DIR__;

$file = $path . $uri;
if ($uri !== '/' && file_exists($file) && is_file($file)) {
    return false;
}

require $path . '/index.php';
