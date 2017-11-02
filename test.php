<?php
$pid = getmypid();
//$buf = file_get_contents('php://stdin');
//echo $buf;
//echo $argc;
file_get_contents(getenv('TEST_URL') . '?' . $argc);
?>
