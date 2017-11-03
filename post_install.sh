#!/bin/bash

set -x

date

chmod 755 ./start_web.sh

tar xf phpPgAdmin-5.1.tar.bz2

mv phpPgAdmin-5.1 www/phpphadmin

rm -f phpPgAdmin-5.1.tar.bz2

date
