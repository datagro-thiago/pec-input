<?php

$caminho = "/var/www/html/devx/csv-input/src/Infraestrutura/Logs/csv";
$exts = ['csv'];

foreach ($exts as $ext) {
    $files = glob($caminho . "/*." . $ext);
    foreach ($files as $file) {
        unlink($file);
        }
    }