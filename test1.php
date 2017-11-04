<?php

// hostname:port:database:username:password
// pg_dump -h localhost -U postgres db > dmp

$info = parse_url(getenv('DATABASE_URL'));

echo 'pg_dump --format=plain --schema=public --schema-only --host=' . $info['host'] . ' --username=' . $info['user'] . ' ' . substr($info['path'], 1);

?>
