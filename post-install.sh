#!/bin/bash

set -x

mkdir -m 777 icons
touch icons/index.html

rm -f delegate9.9.13.tar.gz
wget http://delegate.hpcc.jp/anonftp/DeleGate/delegate9.9.13.tar.gz
rm -rf delegate9.9.13
tar xfz delegate9.9.13.tar.gz
rm -f delegate9.9.13.tar.gz
cp ./delegate9.9.13/src/builtin/icons/ysato/*.gif ./icons/
rm -rf delegate9.9.13
