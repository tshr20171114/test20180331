#!/bin/bash

set -x

ls -lang

pwd

mkdir delegate
chmod 777 delegate

rm -f delegate9.9.13.tar.gz
wget http://delegate.hpcc.jp/anonftp/DeleGate/delegate9.9.13.tar.gz
rm -rf delegate9.9.13
tar xfz delegate9.9.13.tar.gz
pushd delegate9.9.13

export CFLAGS="-march=native -O2"
export CXXFLAGS="$CFLAGS"

time make -j4 ADMIN="admin@localhost"

cp ./src/delegated ../delegate/

ls -lang ../delegate/

popd
