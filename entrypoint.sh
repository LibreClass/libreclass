#!/bin/bash

selectphp 7.4
apachelinker /home/server-libreclass/public

tail -f /tmp/dev.log
