<?php

$info = parse_url(getenv('DATABASE_URL'));

echo '#!/bin/bash';
echo "\n\n";
echo 'pg_dump --format=plain --schema=public --schema-only --host=' . $info['host'] . ' --username=' . $info['user'] . ' ' . substr($info['path'], 1);
echo "\n\n";
?>
