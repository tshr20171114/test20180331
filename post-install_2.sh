#!/bin/bash

set -x

mkdir delegate
chmod 777 delegate
touch ./delegate/index.html

cp ./delegate.conf ./delegate/

chmod 755 ./web-boot.sh

unzip delegate.zip
rm delegate.zip

chmod 777 delegated
cp delegated ./delegate/

