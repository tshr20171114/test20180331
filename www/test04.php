<?php
$url = getenv('RSS_URL');
echo file_get_contents($url);
?>
