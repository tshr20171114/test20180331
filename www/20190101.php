<?php

$rc = ftp_login(getenv('Test01'), getenv('Test02'), getenv('Test03'));

error_log($rc);
