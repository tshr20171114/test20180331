#!/bin/bash

set -x

date

tar xf phpPgAdmin-5.1.tar.bz2

mv phpPgAdmin-5.1 www/phppgadmin

rm -f phpPgAdmin-5.1.tar.bz2

cp config.inc.php www/phppgadmin/conf/config.inc.php

chmod 755 ./start_web.sh

cd /tmp

wget http://download.pureftpd.org/pub/pure-ftpd/misc/libpuzzle/releases/libpuzzle-0.11.tar.bz2

tar xf libpuzzle-0.11.tar.bz2

cd libpuzzle-0.11

./configure --help
./configure

make -j4

date
