<?php

$connection_info = parse_url(getenv('DATABASE_URL'));

$dsn = sprintf('pgsql:host=%s;dbname=%s', $connection_info['host'], substr($connection_info['path'], 1));

$pdo = new PDO($dsn, $connection_info['user'], $connection_info['pass']);

$sql = 'SELECT T1.Fqdn FROM M_Appliction T1';

foreach ($pdo->query($sql) as $row)
{
  echo convert_enc($row['Fqdn']) . "\n";
}

$pdo = null;
?>
