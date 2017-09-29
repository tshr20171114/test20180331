#!/bin/bash

set -x

echo "apache"
vendor/bin/heroku-php-apache2 -C apache_app.conf www

echo "delegate"
./delegate/delegated -r -vv +=./delegate/P8080.conf
