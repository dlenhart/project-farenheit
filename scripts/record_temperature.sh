#!/bin/sh

###
# record_temperature.sh
#
# Author:  Drew D. Lenhart
# Date:  03/19/2018
# URL: https://github.com/dlenhart/project-farenheit
# Desc:  Start/Stop shell script for Redis
#
# Use: ./record_temperature.sh
###

## VARIABLES
USERNAME=admin@localhost.com
PASSWORD=admin
BASE_URL=http://localhost:8000
LOG=record_temp.log

## CURL
curl -u ${USERNAME}:$PASSWORD ${BASE_URL}/api/get/temperature/log > ../logs/${LOG}

sleep 15
if grep "SUCCESS" ../logs/${LOG}
then
  echo "Success!"
else
  echo "Failed!"
  ##Perhaps I could fire off an email here with mailx?
fi
