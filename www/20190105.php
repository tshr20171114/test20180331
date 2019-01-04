<?php

$url = getenv('URL_010');

$options = [
        CURLOPT_URL => $url,
        CURLOPT_USERAGENT => getenv('USER_AGENT'),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_MAXREDIRS => 3,
        CURLOPT_SSL_FALSESTART => true,
        ];

$ch = curl_init();
curl_setopt_array($ch, $options);
$res = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
error_log('HTTP STATUS CODE : ' . $http_code);
curl_close($ch);

// error_log($res);

$tmp1 = explode('<div class="innerHeaderSubMenu langTextSubMenu">', $res, 2);
$tmp1 = explode('<div class="pagination3">', $tmp1[1]);

// error_log($tmp1[0]);

$list = explode('</li>', $tmp1[0]);

// error_log(print_r($list, true));

foreach($list as $item) {
  // error_log($item);
  $rc = preg_match('/data-thumb_url = "(.+?)"/s', $item, $match);
  // error_log(print_r($match, true));
  $url_img = $match[1];
  
  $rc = preg_match('/<var class="duration">(.+?)</s', $item, $match);
  // error_log(print_r($match, true));
  $duration = $match[1];
  
  $rc = preg_match('/<a href="(.+?)".+?title="(.+?)"/s', $item, $match);
  // error_log(print_r($match, true));
  $link = $match[1];
  $title = $match[2];
  $list2[] = $duration , ' ' . $title;
}

error_log(print_r($list2, true));
