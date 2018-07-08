#!/bin/bash

set -x

date

chmod 755 ./start_web.sh

cd /tmp

wget http://download.pureftpd.org/pub/pure-ftpd/misc/libpuzzle/releases/libpuzzle-0.11.tar.bz2

tar xf libpuzzle-0.11.tar.bz2

cd libpuzzle-0.11

./configure --help
./configure --prefix=/tmp/usr

make -j4

meake install

ls -lang /tmp/usr

date
