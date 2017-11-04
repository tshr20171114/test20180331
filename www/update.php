<?php

// ★トリガで履歴作成

error_log('***** START *****');

$url_loggly = 'https://logs-01.loggly.com/inputs/' . getenv('LOGGLY_TOKEN') . '/tag/' . $_SERVER['SERVER_NAME'] . '/';

header("Content-type: text/plain");

$connection_info = parse_url(getenv('DATABASE_URL'));

$dsn = sprintf('pgsql:host=%s;dbname=%s', $connection_info['host'], substr($connection_info['path'], 1));

$pdo = new PDO($dsn, $connection_info['user'], $connection_info['pass']);

$sql = <<< __HEREDOC__
SELECT M1.api_key
  FROM m_application M1
 WHERE M1.update_time < localtimestamp - interval '30 minutes'
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
  echo "check point 010";
  error_log('***** START (ABORT) *****');
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
  error_log('***** CHECK POINT 100 *****');
  $url = 'https://api.heroku.com/account';
  $context = array(
    "http" => array(
      "method" => "GET",
      "header" => array(
        "Accept: application/vnd.heroku+json; version=3",
        "Authorization: Bearer " . $api_key
      )
    )
  );
  $response = file_get_contents($url, false, stream_context_create($context));
  
  $data = json_decode($response, true);
  
  $url = 'https://api.heroku.com/accounts/' . $data['id'] . '/actions/get-quota';
  
  $context = array(
    "http" => array(
      "method" => "GET",
      "header" => array(
        "Accept: application/vnd.heroku+json; version=3.account-quotas",
        "Authorization: Bearer " . $api_key
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
$pdo = null;

echo "check point 020";

error_log('***** FINISH *****');
?>
