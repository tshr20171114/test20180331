#!/bin/bash

set -x

# delegate
/app/delegate/delegated +=/app/delegate/P8080.conf
./delegate/delegated +=./delegate/P8080.conf

vendor/bin/heroku-php-apache2 -C apache_app.conf www
