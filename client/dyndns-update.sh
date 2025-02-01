#!/bin/bash
# Find container directory of this script (@source https://stackoverflow.com/a/246128)
SOURCE="${BASH_SOURCE[0]}"
while [ -h "$SOURCE" ]; do
  DIR="$( cd -P "$( dirname "$SOURCE" )" >/dev/null 2>&1 && pwd )"
  SOURCE="$(readlink "$SOURCE")"
  [[ $SOURCE != /* ]] && SOURCE="$DIR/$SOURCE"
done
MYDIR="$( cd -P "$( dirname "$SOURCE" )" >/dev/null 2>&1 && pwd )"
# Import configuration
source "${MYDIR}/dyndns-update-config.sh"
# Do the update
DATETIME=`date +"%Y%m%d%H%M%S"`
HASH2PLAIN="${KEY2}:${DATETIME}"
HASH2=`echo -n $HASH2PLAIN | md5sum | cut -c1-32`
FULLURL="${URL}?name=${NAME}&key1=${KEY1}&hash2=${HASH2}"
curl $FULLURL
