<?php

$url = $_GET['u'];

error_log($url);

$page1 = file_get_contents($url);

$rc = preg_match('/<div class="gotoBlog"><a href="(.+?)"/', $res, $matches);
$url = $matches[1];

error_log($url);




?>
