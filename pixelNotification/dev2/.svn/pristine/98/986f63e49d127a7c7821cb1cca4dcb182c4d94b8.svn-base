#/bin/bash

type="$1"
OPS=`find /var/script/pixelNotification/stable/logs/ -name 'process_pixel_*_access.log' | sed -e 's/\/var\/script\/pixelNotification\/stable\/logs\/process_pixel_//' | sed -e 's/_access.log//' | sort -g`

json=""
for OP in ${OPS[@]}; do
    fileType=`head -n 1 /var/script/pixelNotification/stable/logs/process_pixel_${OP}_access.log | awk '{print tolower($4)}'`
    #echo "$type"
    if [ "$fileType" == "$type" ]; then
        cant=`cat /var/script/pixelNotification/stable/logs/process_pixel_${OP}_access.log | grep 'Response: OK' | wc -l`
        string="{\"Name\":\"${OP}\",\"Value\":\"$cant\"}"
        if [ -z "$json" ] ; then
            json="$string"
        else
            json="$json,$string"
        fi
    fi
done
json="[$json]"
echo $json > /var/www/html/pixelreports/${type}_pixels_processed.json
