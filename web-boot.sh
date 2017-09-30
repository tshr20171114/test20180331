#!/bin/bash

set -x

export TZ=JST-9

./delegate/delegated -r -fvv -P${PORT} +=./delegate/delegate.conf
