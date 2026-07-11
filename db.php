<?php
declare(strict_types=1);
$file=__DIR__.'/config.php';
if(!is_file($file)){http_response_code(503);exit('إعداد قاعدة البيانات غير مكتمل.');}
$c=require $file;
$pdo=new PDO('mysql:host='.$c['db_host'].';dbname='.$c['db_name'].';charset=utf8mb4',$c['db_user'],$c['db_pass'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
return [$pdo,$c];

