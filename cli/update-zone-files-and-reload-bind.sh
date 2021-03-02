#!/bin/bash
# Find container directory of this script (@source https://stackoverflow.com/a/246128)
SOURCE="${BASH_SOURCE[0]}"
while [ -h "$SOURCE" ]; do
  DIR="$( cd -P "$( dirname "$SOURCE" )" >/dev/null 2>&1 && pwd )"
  SOURCE="$(readlink "$SOURCE")"
  [[ $SOURCE != /* ]] && SOURCE="$DIR/$SOURCE"
done
MYDIR="$( cd -P "$( dirname "$SOURCE" )" >/dev/null 2>&1 && pwd )"
# Do the update
UPDATE_OUTPUT=`php ${MYDIR}/update-zone-files.php`
UPDATED_FILES=`echo $UPDATE_OUTPUT | wc -l`
if [ "$UPDATED_FILES" -gt 0 ]; then
  /etc/init.d/bind9 reload
fi
