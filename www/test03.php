<?php

function f_parse($html)
{
  $buf = $html;
  $buf = explode('<div id="video_list_1column" style="display:block;">', $buf, 2)[1];
  $buf = explode('<div id="video_list_2column" style="display:none;">', $buf, 2)[0];
 
  $connection_info = parse_url(getenv('DATABASE_URL'));
  $pdo = new PDO(
    "pgsql:host=${connection_info['host']};dbname=" . substr($connection_info['path'], 1),
    $connection_info['user'],
    $connection_info['pass']);

  $sql = <<< __HEREDOC__
INSERT INTO t_contents
( uri, title, thumbnail, time ) VALUES ( :b_uri, :b_title, :b_thumbnail, :b_time )
__HEREDOC__;
  $statement = $pdo->prepare($sql);
  
  foreach(explode('<!--/video_list_renew-->', $buf) as $one_record)
  {
    $pos = strpos($one_record, 'コンテンツマーケット');
    if ($pos !== false)
    {
      continue;
    }
 
    $pos = strpos($one_record, '全員');
    if ($pos === false)
    {
      continue;
    }
    
    $rc = preg_match('/<span class="video_time_renew">(.+?)<\/span>/', $one_record, $matches);
    if ($rc == 0)
    {
      continue;
    }
    $time = $matches[1];
    
    $rc = preg_match('/0.:/', $time, $matches);
    if ($rc == 1)
    {
      continue;
    }
    
    $rc = preg_match('/1[012]:/', $time, $matches);
    if ($rc == 1)
    {
      continue;
    }
    
    $rc = preg_match('/<img src="(.+?)\?/', $one_record, $matches);
    if ($rc == 0)
    {
      continue;
    }
    $thumbnail = $matches[1];
        
    $rc = preg_match('/<a href="(.+?)"/', $one_record, $matches);
    if ($rc == 0)
    {
      continue;
    }
    $href = $matches[1];
        
    $rc = preg_match('/<h3><.+?>(.+?)<\/a>/', $one_record, $matches);
    if ($rc == 0)
    {
      continue;
    }
    $title = $matches[1];
        
    error_log("${time} ${title} ${href} ${thumbnail}");
    
    $statement->execute(
      array(':b_uri' => $href,
            ':b_content' => "${time} ${title} ${href} ${thumbnail}"
           ));
    $statement->execute(
      array(':b_uri' => $href,
            ':b_title' => $title,
            ':b_thumbnail' => $thumbnail,
            ':b_time' => $time
           ));
  }
  
  $pdo = null;
  
  return;
}

$start_time = time();

$max_count = getenv('MAX_COUNT');
$per_count = getenv('PER_COUNT');

$pid = getmypid();
$count = $_GET['c'];
$url = $_GET['u'];

error_log("${pid} START ${count}");

$count++;

$target_url = getenv('SEARCH_URL');

if ($count > $max_count)
{
  error_log("${pid} FINISH ${count}");
  return;
}

if ($count === 1)
{
  $connection_info = parse_url(getenv('DATABASE_URL'));
  $pdo = new PDO(
    "pgsql:host=${connection_info['host']};dbname=" . substr($connection_info['path'], 1),
    $connection_info['user'],
    $connection_info['pass']);

  $pdo->exec('TRUNCATE TABLE t_contents');

  $pdo = null;
}

$urls = array();
for($i = 0; $i < $per_count; $i++)
{
  $urls[] = $target_url . (($count - 1) * $per_count + $i + 1);
}
$urls[] = $url . '?c=' . $count . '&u=' . $url;

$mh = curl_multi_init();

foreach($urls as $url)
{
  $ch = curl_init();
  curl_setopt_array($ch, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true
    ));
  curl_multi_add_handle($mh, $ch);
}

error_log("${pid} CHECK POINT 0100");

do
{
  $stat = curl_multi_exec($mh, $running);
} while ($stat === CURLM_CALL_MULTI_PERFORM);

do switch (curl_multi_select($mh, 10))
{
  case -1:
    usleep(10);
    do
    {
      $stat = curl_multi_exec($mh, $running);
    } while ($stat === CURLM_CALL_MULTI_PERFORM);
    continue 2;
  case 0:
    continue 2;
  default:
    do
    {
      $stat = curl_multi_exec($mh, $running);
    } while ($stat === CURLM_CALL_MULTI_PERFORM);
    
    do if ($raised = curl_multi_info_read($mh, $remains))
    {
      $info = curl_getinfo($raised['handle']);
      $query_string = parse_url($info[url], PHP_URL_QUERY);
      $response = curl_multi_getcontent($raised['handle']);
      error_log("${pid} ${query_string} " . strlen($response));
      //error_log($response);
      f_parse($response);
      curl_multi_remove_handle($mh, $raised['handle']);
      curl_close($raised['handle']);
    } while ($remains);
} while ($running);

curl_multi_close($mh);

error_log("${pid} FINISH ${count}");

if ($count === 1)
{
  echo '<HTML><HEAD><TITLE>' . ($start_time - time()) . '</TITLE></HEAD><BODY>' . time() . '</BODY></HTML>';
}
else
{
  echo '<HTML><HEAD><TITLE>' . ($start_time - time()) . '</TITLE></HEAD><BODY>' . time() . '</BODY></HTML>';
}
?>
