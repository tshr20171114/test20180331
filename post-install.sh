#!/bin/bash

set -x

ls -lang

chmod 777 delegate

rm -f delegate9.9.13.tar.gz
wget http://delegate.hpcc.jp/anonftp/DeleGate/delegate9.9.13.tar.gz
rm -rf delegate9.9.13
tar xfz delegate9.9.13.tar.gz
cd delegate9.9.13

export CFLAGS="-march=native -O2"
export CXXFLAGS="$CFLAGS"

time make -j8 ADMIN="admin@localhost"

ls -lang src/

cp src/delegated /app/delegate/
cp src/delegated /app/

ls -lang /app/delegate/
