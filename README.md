# Pfsense Captive Portal Wifi Marketing

## Send Contact information to DataArc Marketing API

### Bước cài đặt:
1. Cài đặt Pfsense bản Community Edition. Enable ssh shell. 
2. SSH Pfsense, vào shell, chạy command để patch module Captive Portal:
```
curl -s https://raw.githubusercontent.com/minhtri2582/pfsense_captiveportal_wifi_marketing/master/install.sh | sh
```
3. Edit file /usr/local/captiveportal/config.php. Chỉnh thông tin API cần thiết:
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
?>
```
4. Vào Pfsense Web Admin: 
- Enable Captive Portal, update file portal.html (Lưu ý: không cần chỉnh allow Domain và allow IP. Các file css, js, image đã có sẵn khi patch captive portal ở bước 2)
- Download file mẫu portal.html tại: https://raw.githubusercontent.com/minhtri2582/pfsense_captiveportal_wifi_marketing/master/portal.html
- Có thể tạo nhiều Zone riêng subnet cho các khách sạn khác nhau. Trong file portal.html thay đỗi mã khách sạn tương ứng:
```
<input name="hotel" type="hidden" value="LICO">
```
