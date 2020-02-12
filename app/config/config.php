<?php

$_tmpLocalConfig = [];

if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . 'config-local.php')) {
    $_tmpLocalConfig = require_once __DIR__ . DIRECTORY_SEPARATOR . 'config-local.php';
}

return YiiArray::merge(
    [
        'webRoot' => '@web@',
    ]
    ,$_tmpLocalConfig
);