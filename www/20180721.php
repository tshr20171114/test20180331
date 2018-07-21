<?php

$url = $_GET['u'];

error_log($url);

$res = file_get_contents($url);

$rc = preg_match('/u=(.+?)</', $res, $matches);

error_log(var_export($matches, true));

/*
$rc = preg_match('/<div class="gotoBlog"><a href="(.+?)"/', $res, $matches);
$url = $matches[1];

error_log($url);
*/



?>
