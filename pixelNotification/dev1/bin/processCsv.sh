#!/bin/bash

h=`date +'%H'`
procDate=`date --date='2 days ago' +'%Y%m%d'`

if [ $h -le 12 ]; then
   #primer archivo
   filename="${procDate}_1.csv";
   #/usr/bin/php /var/script/googleOfflineConvertion/googleads-php-lib/examples/AdWords/v201809/Remarketing/uploadsOfflineConversions.php $filename
else
   #segundo archivo
   filename="${procDate}_2.csv"
   #/usr/bin/php /var/script/googleOfflineConvertion/googleads-php-lib/examples/AdWords/v201809/Remarketing/uploadsOfflineConversions.php $filename
fi 