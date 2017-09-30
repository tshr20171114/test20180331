#!/bin/bash

set -x

mkdir delegate
chmod 777 delegate

mkdir -p delegate/icons
chmod 777 delegate/icons

mkdir -p delegate/tmp
chmod 777 delegate/tmp

cp ./delegate.conf ./delegate/
cp ./filter.txt ./delegate/
cp ./filter.php ./delegate/

chmod 755 ./web-boot.sh

unzip delegate.zip
rm delegate.zip

chmod 777 delegated
cp delegated ./delegate/

rm -f delegate9.9.13.tar.gz
wget http://delegate.hpcc.jp/anonftp/DeleGate/delegate9.9.13.tar.gz
rm -rf delegate9.9.13
tar xfz delegate9.9.13.tar.gz
rm -f delegate9.9.13.tar.gz
cp ./delegate9.9.13/src/builtin/icons/ysato/*.gif ./delegate/icons/
rm -rf delegate9.9.13

for file in `ls ./delegate/icons/*.gif`;
do
  convert ${file} -geometry 12x12 -colors 2 ${file}
done
