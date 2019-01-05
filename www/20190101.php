<?php

$ftp_link_id = ftp_connect(getenv('Test01'));
$rc = ftp_login($ftp_link_id, getenv('Test02'), getenv('Test03'));

error_log($rc);
