#!/bin/bash

set -x

export TZ=JST-9

./delegate/delegated -r -fvd -P${PORT} +=./delegate/delegate.conf
