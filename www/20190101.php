<?php

$ftp_link_id = ftp_connect(getenv('Test01'));
$rc = ftp_login($ftp_link_id, getenv('Test02'), getenv('Test03'));

error_log($rc);

$rc = ftp_rawlist($ftp_link_id, './');

error_log(print_r($rc, true));

//$rc = ftp_put($ftp_link_id, '20190102.php', '20190101.php', FTP_ASCII);

error_log($rc);

ftp_close($ftp_link_id);
