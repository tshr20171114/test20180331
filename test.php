<?php
$pid = getmypid();
//$buf = file_get_contents('php://stdin');
//echo $buf;
//echo $argc;
//file_get_contents(getenv('TEST_URL') . '?' . $argc);

$stdin = fopen('php://stdin', 'r');
ob_implicit_flush(true);
while ($line = fgets($stdin))
{
  file_get_contents(getenv('TEST_URL') . '?' . urlencode($_SERVER['SERVER_NAME'] . ' ' . $line));
}
?>
