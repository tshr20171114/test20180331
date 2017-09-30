#!/bin/bash

set -x

mkdir delegate
chmod 777 delegate

mkdir -p delegate/tmp
chmod 777 delegate/tmp

cp ./delegate.conf ./delegate/
cp ./filter.txt ./delegate/

chmod 755 ./web-boot.sh

unzip delegate.zip
rm delegate.zip

chmod 777 delegated
cp delegated ./delegate/

