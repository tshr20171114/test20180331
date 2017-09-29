#!/bin/bash

set -x

echo ${PORT}

echo "delegate"
./delegate/delegated -r -vv -P${PORT} +=./delegate/P8080.conf

#echo "apache"
#vendor/bin/heroku-php-apache2 -C apache_app.conf www
