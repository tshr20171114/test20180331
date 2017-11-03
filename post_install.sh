#!/bin/bash

set -x

date

tar xf phpPgAdmin-5.1.tar.bz2

mv phpPgAdmin-5.1 www/phpphadmin

rm -f phpPgAdmin-5.1.tar.bz2

cp config.inc.php www/phpphadmin/conf/config.inc.php

chmod 755 ./start_web.sh

date
