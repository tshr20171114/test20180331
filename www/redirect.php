<?php

if (!isset($_GET['p']) || $_GET['p'] === '')
{
  exit();
}
$path = $_GET['p'];

if ($path !== 'ttrss' && $path !== 'ml')
{
  exit();
}

$connection_info = parse_url(getenv('DATABASE_URL'));

$pdo = new PDO(
  'pgsql:host=' . $connection_info['host'] . ';dbname=' . substr($connection_info['path'], 1),
  $connection_info['user'],
  $connection_info['pass']);

// 空更新チェックによる連続取得の禁止

$sql = <<< __HEREDOC__
UPDATE m_access_time
   SET access_time = localtimestamp
 WHERE access_time < localtimestamp - interval '30 minutes'
__HEREDOC__;

$statement = $pdo->prepare($sql);
$statement->execute();
$update_count = $statement->rowCount(); // 後で使う

// 未使用割合が最も多いサーバにリダイレクト

$sql = <<< __HEREDOC__
SELECT M1.fqdn
  FROM m_application M1
 WHERE M1.select_type = 1
   AND M1.dyno_quota <> -1
 ORDER BY CAST(M1.dyno_used as numeric) / CAST(M1.dyno_quota as numeric)
 LIMIT 1 OFFSET 0
__HEREDOC__;

foreach ($pdo->query($sql) as $row)
{
  $fqdn = $row['fqdn'];
  break;
}
$url = 'https://' . $fqdn . '/' . $path . '/';

header('Location: ' . $url);

if ($update_count === 0)
{
  $pdo = null;
  exit;
}

// 使用量チェック & 更新

$sql = <<< __HEREDOC__
SELECT M1.api_key
  FROM m_application M1
 WHERE M1.update_time < localtimestamp - interval '30 minutes'
   AND M1.update_time < current_date + interval '3 hours'
   AND M1.select_type <> 9
 ORDER BY M1.api_key
__HEREDOC__;

$api_keys = array();
foreach ($pdo->query($sql) as $row)
{
  $api_keys[] = $row['api_key'];
}

if (count($api_keys) === 0)
{
  $pdo = null;
  exit();
}

$sql = <<< __HEREDOC__
UPDATE m_application
   SET dyno_used = :b_dyno_used
      ,dyno_quota = :b_dyno_quota
 WHERE api_key = :b_api_key
__HEREDOC__;
$statement = $pdo->prepare($sql);

foreach ($api_keys as $api_key)
{
  $url = 'https://api.heroku.com/account';
  $context = array(
    'http' => array(
      'method' => 'GET',
      'header' => array(
        'Accept: application/vnd.heroku+json; version=3',
        'Authorization: Bearer ' . $api_key
      )
    )
  );
  $response = file_get_contents($url, false, stream_context_create($context));

  $data = json_decode($response, true);

  $url = 'https://api.heroku.com/accounts/' . $data['id'] . '/actions/get-quota';

  $context = array(
    'http' => array(
      'method' => 'GET',
      'header' => array(
        'Accept: application/vnd.heroku+json; version=3.account-quotas',
        'Authorization: Bearer ' . $api_key
      )
    )
  );

  $response = file_get_contents($url, false, stream_context_create($context));
  $data = json_decode($response, true);

  $dyno_used = $data['quota_used'];
  $dyno_quota = $data['account_quota'];
  $statement->execute(
    array(':b_dyno_used' => $dyno_used,
          ':b_dyno_quota' => $dyno_quota,
          ':b_api_key' => $api_key,
         ));
}

// 報告

$sql = <<< __HEREDOC__
SELECT M1.fqdn
      ,M1.dyno_used
      ,to_char(M1.update_time, 'YYYY/MM/DD HH24:MI:SS') update_time
      ,(M1.dyno_quota - M1.dyno_used) / 86400 d
      ,((M1.dyno_quota - M1.dyno_used) / 3600) % 24 h
      ,((M1.dyno_quota - M1.dyno_used) / 60) % 60 m
  FROM m_application M1
 ORDER BY M1.fqdn
__HEREDOC__;

$url = 'https://logs-01.loggly.com/inputs/' . getenv('LOGGLY_TOKEN') . '/tag/dyno/';

foreach ($pdo->query($sql) as $row)
{  
  $context = array(
    "http" => array(
      "method" => "POST",
      "header" => array(
        "Content-Type: text/plain"
        ),
      "content" => $row['fqdn'] . ' ' . $row['update_time'] . ' ' . $row['dyno_used'] . ' ' . $row['d'] . 'd ' . $row['h'] . 'h ' . $row['m'] .'m'
      ));
  $res = file_get_contents($url, false, stream_context_create($context));
}

$pdo = null;
?>
