<?php
$stdin = fopen('php://stdin', 'r');
ob_implicit_flush(true);
while ($line = fgets($stdin))
{
  file_get_contents(getenv('TEST_URL') . '?' . urlencode($line));
}
?>
