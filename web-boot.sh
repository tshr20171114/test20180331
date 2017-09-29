#!/bin/bash

# delegate
/app/delegate/delegated +=P8080.conf

vendor/bin/heroku-php-apache2 -C apache_app.conf www
