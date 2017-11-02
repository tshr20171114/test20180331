<?php
$pid = getmypid();
$buf = file_get_contents('php://stdin');
echo $buf;
?>
