#!/bin/sh
# description: getwords.php  görevini crontab ile 5 dakikada bir otomatik kontrol eder.

SERVICE='getwords.php'
 
if ps ax | grep -v grep | grep $SERVICE > /dev/null
then
    echo "$SERVICE service running, everything is ok"
else
  /usr/bin/php /var/www/kelimeci/scripts/getwords.php &	
fi
