#!/bin/bash
UPDATE_OUTPUT=`php ./update-zone-files.php`
UPDATED_FILES=`echo $UPDATE_OUTPUT | wc -l`
if [ "$UPDATED_FILES" -gt 0 ]; then
  /etc/init.d/bind9 reload
fi