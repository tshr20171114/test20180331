<?php

$info = parse_url(getenv('DATABASE_URL'));

echo $info['host'] . ':' . $info['port'];
echo "\n";

echo "${info['host']}:${info['port']}";
echo "\n";

?>
