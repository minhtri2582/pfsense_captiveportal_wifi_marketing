# Pfsense Captive Portal Wifi Marketing
##Send Contact information to Marketing API

### Bước cài đặt:
1. Pfsense cài Captive Portal
2. SSH pfsense, vào shell, chạy command để patch module captive portal:
```
curl -s https://raw.githubusercontent.com/minhtri2582/pfsense_captiveportal_wifi_marketing/master/install.sh
```
3. Edit file /usr/local/captiveportal/config.php. Chỉnh mã khách sạn và thông tin API cần thiết:
```
cd /usr/local/captiveportal
vi config.php
```
Ví dụ:
```
<?php
    $url =  'https://mail.dataarc.com/api/jsonrpcServer';
    $api_key = 'a662c9247d5751a5e00728d2d7f0f844a663fe4c829adb6036f4a6b4d7f02fe0';
    $list_id = 579741;
    $hotel = 'LICO';
?>
```
4. Pfsense admin: vào Enable Captive Portal, update file portal.html (Lứu ý: không cần chỉnh allow Domain và allow IP. Các file css, js, image đã có sẵn khi patch captive portal ở bước 2)
Download file portal.html tại: https://raw.githubusercontent.com/minhtri2582/pfsense_captiveportal_wifi_marketing/master/portal.html
