#!/bin/bash

# >> Configuration start
URL="http://lipotigyor.hu/dyndns/update.php"
DOMAIN_ID=1
KEY1="7a7e8a78313b2883977b9c9565f12023"
KEY2="04f82c44cb5c7182d6475fbee9d1d81f"
# << Configuration end

# Don't change code below
DATETIME=`date +"%Y%m%d"`
HASH2PLAIN="{$KEY2}:{$DATETIME}"
HASH2=`echo $HASH2PLAIN | md5sum | cut -c1-32`
FULLURL="{$URL}?id={$DOMAIN_ID}&key1={$KEY1}&hash2={$HASH2}"
curl $FULLURL