<?php

$url = 'https://api.heroku.com/account';

$context = array(
  "http" => array(
    "method" => "GET",
    "header" => array(
      "Accept: application/vnd.heroku+json; version=3",
      "Authorization: Bearer " . getenv('API_KEY_01')
    )
  )
);

$res = file_get_contents($url, false, stream_context_create($context));

echo $res . "\n";

$data = json_decode($res, true);
echo $data['id'] . "\n";

$url = 'https://api.heroku.com/accounts/' . $data['id'] . "/actions/get-quota";

$context = array(
  "http" => array(
    "method" => "GET",
    "header" => array(
      "Accept: application/vnd.heroku+json; version=3.account-quotas",
      "Authorization: Bearer " . getenv('API_KEY_01')
    )
  )
);

$res = file_get_contents($url, false, stream_context_create($context));

echo $res . "\n";
?>
