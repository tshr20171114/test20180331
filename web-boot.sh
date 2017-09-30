#!/bin/bash

set -x

echo ${PORT}
export TZ=JST-9

echo "delegate"
./delegate/delegated -r -fvv -P${PORT} +=./delegate/delegate.conf
