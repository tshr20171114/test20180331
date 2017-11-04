<?php

// hostname:port:database:username:password

$info = parse_url(getenv('DATABASE_URL'));

/*
var_dump($info);

echo "\n\n";

echo $info['host'] . "\n\n";
echo $info['port'] . "\n\n";
echo $info['path'] . "\n\n";
echo $info['username'] . "\n\n";
echo $info['pass'] . "\n\n";
*/

echo $info['host'] . ':' . $info['port'] . ':' . $info['path'] . ':' . $info['username'] . ':' . $info['pass'];
?>
