#!/bin/sh
echo "Installing Pfsense Wifi Marketing"
curl -L -o master.zip https://github.com/minhtri2582/pfsense_captiveportal_wifi_marketing/archive/master.zip
unzip master.zip
rm -f master.zip
mv -rf pfsense_captiveportal_wifi_marketing-master /usr/local/captiveportal
chown -Rf root:wheel /usr/local/captiveportal
