<?php 

// замеряем время выполнения скрипта
$start_framework = microtime(true);

if (PHP_MAJOR_VERSION < 8) {
   die("PHP 8 or higher is required");
}

// время выполнения скрипта
var_dump("Time: " . (microtime(true) - $start_framework));