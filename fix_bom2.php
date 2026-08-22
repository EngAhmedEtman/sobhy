<?php
$c = file_get_contents('H:\laravel\work\SobhyReda\app\Http\Controllers\ReportController.php');
$c = preg_replace('/^\xEF\xBB\xBF/', '', $c);
file_put_contents('H:\laravel\work\SobhyReda\app\Http\Controllers\ReportController.php', $c);
echo "Fixed!";
