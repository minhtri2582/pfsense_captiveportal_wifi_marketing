# Pfsense Captive Portal Wifi Marketing

## Send Contact information to DataArc API

### Bước cài đặt:
1. Cài đặt Pfsense bản Community Edition. Enable ssh shell. 
2. SSH Pfsense, vào shell, chạy command để patch module Captive Portal:
```
curl -s https://raw.githubusercontent.com/minhtri2582/pfsense_captiveportal_wifi_marketing/master/install.sh | sh
```
![alt text](https://raw.githubusercontent.com/minhtri2582/pfsense_captiveportal_wifi_marketing/master/install.png)

3. Edit file /usr/local/captiveportal/index.php. Chỉnh thông tin API cần thiết:
```
vi /usr/local/captiveportal/index.php
```
Ví dụ: (các tham số này đã theo thực tế, chỉ điều chỉnh trong trường hợp đối tác thay đổi)
```
    $url =  'https://mail.dataarc.com/api/jsonrpcServer';
    $api_key = 'a662c9247d5751a5e00728d2d7f0f844a663fe4c829adb6036f4a6b4d7f02fe0';
    $list_id = 579741;
```
4. Cấu hình Captive Portal trên Pfsense Web Admin: 
- Services - Captive Portal:  Add Zone.
- Enable Captive Portal, chọn subnet. (Lưu ý: không cần chỉnh Allow Hostnames, Allow IP Addresses, File Manager. Các file css, js, image đã có sẵn khi patch Captive Portal ở bước 2)
![alt text](https://raw.githubusercontent.com/minhtri2582/pfsense_captiveportal_wifi_marketing/master/1_enable_captive.png)
- Upload file portal.html. Download file mẫu tại: https://raw.githubusercontent.com/minhtri2582/pfsense_captiveportal_wifi_marketing/master/portal.html
![alt text](https://raw.githubusercontent.com/minhtri2582/pfsense_captiveportal_wifi_marketing/master/2_Login_upload.png)
- Có thể tạo nhiều Zone riêng cho từng subnet ứng với khách sạn khác nhau. Trong file portal.html thay đổi mã khách sạn tương ứng:
```
<input name="hotel" type="hidden" value="LICO">
```
- Authentication Method: None
![alt text](https://raw.githubusercontent.com/minhtri2582/pfsense_captiveportal_wifi_marketing/master/3_Authentication_method.png)
- Save cấu hình.

## XEM THÔNG TIN KHÁCH ĐĂNG KÝ
http://{PFSENSE_IP}:8002/db.txt
