<?php
declare(strict_types=1);
$configFile=__DIR__.'/config.php';$message='';
if(is_file($configFile)){exit('<meta charset="utf-8"><div dir="rtl" style="font-family:Tahoma;padding:30px">تم إعداد النظام مسبقًا. احذف setup.php من الخادم بعد التأكد من عمل الموقع.</div>');}
if($_SERVER['REQUEST_METHOD']==='POST'){
 try{
  $host=trim($_POST['db_host']??'localhost');$name=trim($_POST['db_name']??'');$user=trim($_POST['db_user']??'');$pass=(string)($_POST['db_pass']??'');$admin=trim($_POST['admin_user']??'');$adminPass=(string)($_POST['admin_pass']??'');
  if(!$name||!$user||strlen($admin)<4||strlen($adminPass)<12)throw new Exception('أكمل البيانات، واجعل كلمة مرور المالك 12 حرفًا على الأقل.');
  $pdo=new PDO('mysql:host='.$host.';dbname='.$name.';charset=utf8mb4',$user,$pass,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
  $sql=file_get_contents(__DIR__.'/schema.sql');$pdo->exec($sql);
  $config=['db_host'=>$host,'db_name'=>$name,'db_user'=>$user,'db_pass'=>$pass,'admin_user'=>$admin,'admin_password_hash'=>password_hash($adminPass,PASSWORD_DEFAULT)];
  $php="<?php\nreturn ".var_export($config,true).";\n";if(file_put_contents($configFile,$php,LOCK_EX)===false)throw new Exception('تعذر إنشاء config.php. تحقق من صلاحيات المجلد.');
  $message='تم إنشاء قاعدة البيانات وحفظ كلمة مرور المالك بصيغة Hash. افتح رابط المالك الخاص وسجّل الدخول، ثم احذف setup.php.';
 }catch(Throwable $e){$message=$e->getMessage();}
}
?><!doctype html><html lang="ar" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>إعداد الحاسبة</title><style>body{font-family:Tahoma;background:#f4f8f7;padding:20px}.box{max-width:520px;margin:auto;background:#fff;padding:24px;border-radius:20px}input,button{width:100%;box-sizing:border-box;padding:12px;margin:6px 0;border:1px solid #ccd;border-radius:10px}button{background:#0b6b62;color:#fff;font-weight:bold}.msg{padding:12px;background:#eef8f6;border-radius:10px}</style></head><body><main class="box"><h1>الإعداد الأول</h1><?php if($message):?><p class="msg"><?=htmlspecialchars($message)?></p><?php endif?><form method="post"><input name="db_host" value="localhost" required placeholder="خادم قاعدة البيانات"><input name="db_name" required placeholder="اسم قاعدة البيانات"><input name="db_user" required placeholder="مستخدم قاعدة البيانات"><input name="db_pass" type="password" required placeholder="كلمة مرور قاعدة البيانات"><hr><input name="admin_user" required minlength="4" placeholder="اسم مستخدم مالك النظام"><input name="admin_pass" type="password" required minlength="12" placeholder="كلمة مرور قوية للمالك"><button>إنشاء النظام</button></form><p>كلمة مرور المالك لا تُحفظ كنص؛ يُحفظ فقط Password Hash آمن.</p></main></body></html>
