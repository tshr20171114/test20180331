#!/bin/bash

set -x

chmod 777 delegate

wget http://delegate.hpcc.jp/anonftp/DeleGate/delegate9.9.13.tar.gz
tar xfz delegate9.9.13.tar.gz
cd delegate9.9.13

export CFLAGS="-march=native -O2"
export CXXFLAGS="$CFLAGS"

time make -j8 ADMIN="admin@localhost"

cp src/delegated /app/delegate/
