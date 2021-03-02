#!/bin/bash
source ./dyndns-update-config.sh
DATETIME=`date +"%Y%m%d%H%M%S"`
HASH2PLAIN="${KEY2}:${DATETIME}"
HASH2=`echo -n $HASH2PLAIN | md5sum | cut -c1-32`
FULLURL="${URL}?domain=${DOMAIN_ID}&key1=${KEY1}&hash2=${HASH2}"
curl $FULLURL
