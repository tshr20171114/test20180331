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
  if ($rc === 0) {
    continue;
  }
  // error_log(print_r($match, true));
  $thumbnail = $match[1];
  
  $rc = preg_match('/<var class="duration">(.+?)</s', $item, $match);
  // error_log(print_r($match, true));
  $time = $match[1];
  if ((int)explode(':', $time)[0] < 50) {
    continue;
  }
  
  $rc = preg_match('/<a href="(.+?)".+?title="(.+?)"/s', $item, $match);
  // error_log(print_r($match, true));
  $link = $match[1];
  $title = $match[2];
  $items[] = "<item><title>${time}min ${title}</title><link>${url}${link}</link><description>&lt;img src='${thumbnail}'&gt;</description><pubDate/></item>";
}

error_log(print_r($list2, true));

$xml_root_text = <<< __HEREDOC__
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
  <channel>
    <title>rss2</title>
    <link>http://www.yahoo.co.jp</link>
    <description>none</description>
    <language>ja</language>
    __ITEMS__
  </channel>
</rss>
__HEREDOC__;

header('Content-Type: application/xml; charset=UTF-8');
echo str_replace('__ITEMS__', implode("\r\n", $items), $xml_root_text);
  
