<?php

$connection_info = parse_url(getenv('DATABASE_URL'));

$dsn = sprintf('pgsql:host=%s;dbname=%s', $connection_info['host'], substr($connection_info['path'], 1));

echo $dsn . "\n";

$pdo = new PDO($dsn, $connection_info['user'], $connection_info['pass']);

$sql = 'SELECT COUNT(*) CNT FROM M_Application';

$result = $pdo->query($sql);

var_dump($result);

foreach ($result as $row)
{
  echo convert_enc($row['CNT']) . "\n";
}

$pdo = null;
?>
