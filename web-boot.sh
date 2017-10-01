#!/bin/bash

set -x

export TZ=JST-9

./delegate/delegated -r -f -P${PORT} +=./delegate/delegate.conf
