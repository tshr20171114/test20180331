#!/bin/bash

set -x

echo "delegate"
./delegate/delegated -r -vv +=./delegate/P8080.conf &

echo "apache"
vendor/bin/heroku-php-apache2 -C apache_app.conf www &
