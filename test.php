<?php

// hostname:port:database:username:password

$info = parse_url(getenv('DATABASE_URL'));

echo $info['host'] . ':' . $info['port'] . ':' . substr($info['path'], 1) . ':' . $info['user'] . ':' . $info['pass'];
?>
