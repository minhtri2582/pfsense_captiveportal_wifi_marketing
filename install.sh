#!/bin/sh
echo "Installing Pfsense Wifi Marketing"
curl https://github.com/minhtri2582/pfsense_captiveportal_wifi_marketing/archive/master.zip
unzip master.zip
chown -Rf root:wheel *
