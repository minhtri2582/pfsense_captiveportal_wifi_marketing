#!/bin/sh
echo "Installing Pfsense Wifi Marketing"
rm -f master.zip
rm -rf pfsense_captiveportal_wifi_marketing-master/
curl -L -o master.zip https://github.com/minhtri2582/pfsense_captiveportal_wifi_marketing/archive/master.zip
unzip master.zip
rm -f master.zip
mv -f pfsense_captiveportal_wifi_marketing-master/* /usr/local/captiveportal
rm -rf pfsense_captiveportal_wifi_marketing-master
chown -Rf root:wheel /usr/local/captiveportal
