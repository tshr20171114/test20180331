#!/bin/bash

set -x

export TZ=JST-9

httpd -V
httpd -M
php --version
whereis php

if [ ! -v LOGGLY_TOKEN ]; then
  echo "Error : LOGGLY_TOKEN not defined."
  exit
fi

if [ ! -v LOG_LEVEL ]; then
  export LOG_LEVEL="warn"
fi

if [ ! -v BASIC_USER ]; then
  echo "Error : BASIC_USER not defined."
  exit
fi

if [ ! -v BASIC_PASSWORD ]; then
  echo "Error : BASIC_PASSWORD not defined."
  exit
fi

htpasswd -c -b .htpasswd ${BASIC_USER} ${BASIC_PASSWORD}

postgres_user=$(echo ${DATABASE_URL} | awk -F':' '{print $2}' | sed -e 's/\///g')
postgres_password=$(echo ${DATABASE_URL} | grep -o '/.\+@' | grep -o ':.\+' | sed -e 's/://' | sed -e 's/@//')
postgres_server=$(echo ${DATABASE_URL} | awk -F'@' '{print $2}' | awk -F':' '{print $1}')
postgres_dbname=$(echo ${DATABASE_URL} | awk -F'/' '{print $NF}')

export PGPASSWORD=${postgres_password}

psql -U ${postgres_user} -d ${postgres_dbname} -h ${postgres_server} > /tmp/sql_result.txt << __HEREDOC__
SELECT 't_file_hash', COUNT('X')
  FROM t_file_hash
__HEREDOC__
cat /tmp/sql_result.txt

psql -U ${postgres_user} -d ${postgres_dbname} -h ${postgres_server} > /tmp/sql_result.txt << __HEREDOC__
DELETE
  FROM t_file_hash
 WHERE create_time < localtimestamp - interval '3 days'
__HEREDOC__

unset PGPASSWORD

vendor/bin/heroku-php-apache2 -C apache.conf www
