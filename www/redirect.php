<?php
$path = $_GET["p"];

$connection_info = parse_url(getenv('DATABASE_URL'));

$dsn = sprintf('pgsql:host=%s;dbname=%s', $connection_info['host'], substr($connection_info['path'], 1));

$pdo = new PDO($dsn, $connection_info['user'], $connection_info['pass']);

// ★更新時刻の条件追加
$sql = <<< __HEREDOC__
SELECT fqdn
  FROM m_application
 WHERE select_type = 1
   AND dyno_quota <> -1
 ORDER BY CAST(dyno_used as numeric) / CAST(dyno_quota as numeric)
 LIMIT 1 OFFSET 0
__HEREDOC__;

foreach ($pdo->query($sql) as $row)
{
  $fqdn = $row['fqdn'];
  break;
}
$url = 'https://' . $fqdn . '/' . $path . '/';

header('Location: ' . $url);
exit;
?>
