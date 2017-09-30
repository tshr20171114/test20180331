#!/bin/bash

set -x

export TZ=JST-9

./delegate/delegated -r -fvvv -P${PORT} +=./delegate/delegate.conf
