#!/bin/bash

for i in `ps ax | grep pixelNotificationProcess | grep -v grep | grep $1 | awk {'print $1'}`;do
    sudo kill -9 $i
done
