#!/bin/bash

set -x

date

tar xf phpPgAdmin-5.1.tar.bz2

mv phpPgAdmin-5.1 www/phppgadmin

rm -f phpPgAdmin-5.1.tar.bz2

cp config.inc.php www/phppgadmin/conf/config.inc.php

chmod 755 ./start_web.sh

cd /tmp

wget https://osdn.jp/projects/nkf/downloads/64158/nkf-2.1.4.tar.gz
tar xf nkf-2.1.4.tar.gz
cd nkf-2.1.4
make

ls -lang

cd /tmp

git clone --depth 1 https://github.com/google/guetzli.git

cd guetzli

make -j4

date
