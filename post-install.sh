#!/bin/bash

wget http://delegate.hpcc.jp/anonftp/DeleGate/delegate9.9.13.tar.gz
tar xfz delegate9.9.13.tar.gz
cd delegate9.9.13
time make -j8 ADMIN="admin@localhost"
