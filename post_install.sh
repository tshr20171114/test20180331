#!/bin/bash

set -x

date

chmod 755 ./start_web.sh

exit

cd /tmp

mkdir /tmp/usr

wget https://download.pureftpd.org/pub/pure-ftpd/misc/libpuzzle/releases/libpuzzle-0.11.tar.bz2

tar xf libpuzzle-0.11.tar.bz2

cd libpuzzle-0.11

./configure --help
./configure --prefix=/tmp/usr

make -j4

make install

cd php/libpuzzle
phpize
./configure --help
./configure --with-libpuzzle=/tmp/usr
make clean
make
make instal

date
