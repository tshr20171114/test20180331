<?php
$url = getenv('RSS_URL');
$res = file_get_contents($url);
error_log('***** CHECK POINT AAA *****');
echo $res;
?>
