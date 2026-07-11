<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');header('Cache-Control: no-store');
if(!is_file(__DIR__.'/config.php')){http_response_code(503);echo json_encode(['ok'=>false,'message'=>'قاعدة البيانات غير مهيأة. افتح setup.php لإكمال الإعداد.'],JSON_UNESCAPED_UNICODE);exit;}
[$pdo,$c]=require __DIR__.'/db.php';$in=json_decode(file_get_contents('php://input'),true)?:[];$a=$in['action']??'';
function logEvent(PDO $p,?int $id,?string $code,string $type,array $data=[]):void{$s=$p->prepare('INSERT INTO visitor_events(visitor_id,visitor_code,event_type,event_data) VALUES(?,?,?,?)');$s->execute([$id,$code,$type,json_encode($data,JSON_UNESCAPED_UNICODE)]);}
try{
 if($a==='new'){for($i=0;$i<40;$i++){try{$code=(string)random_int(1000,9999);$s=$pdo->prepare('INSERT INTO visitors(visitor_code,calculator_data,last_user_agent) VALUES(?,?,?)');$s->execute([$code,'{}',substr($_SERVER['HTTP_USER_AGENT']??'',0,500)]);$id=(int)$pdo->lastInsertId();logEvent($pdo,$id,$code,'new');echo json_encode(['ok'=>true,'code'=>$code]);exit;}catch(PDOException $e){if($e->getCode()!=='23000')throw $e;}}throw new Exception('تعذر إنشاء رقم زائر.');}
 $code=preg_replace('/\D/','',(string)($in['code']??''));if(strlen($code)!==4)throw new Exception('رقم الزائر يجب أن يكون 4 خانات.');$s=$pdo->prepare('SELECT id,calculator_data,return_count FROM visitors WHERE visitor_code=?');$s->execute([$code]);$v=$s->fetch();
 if($a==='restore'){logEvent($pdo,$v?(int)$v['id']:null,$code,'search',['found'=>(bool)$v]);if(!$v)throw new Exception('رقم الزائر غير موجود.');$pdo->prepare('UPDATE visitors SET return_count=return_count+1 WHERE id=?')->execute([$v['id']]);logEvent($pdo,(int)$v['id'],$code,'restore');echo json_encode(['ok'=>true,'code'=>$code,'data'=>json_decode($v['calculator_data'],true)]);exit;}
 if($a==='save'){if(!$v)throw new Exception('رقم الزائر غير موجود.');$pdo->prepare('UPDATE visitors SET calculator_data=? WHERE id=?')->execute([json_encode($in['data']??[],JSON_UNESCAPED_UNICODE),$v['id']]);logEvent($pdo,(int)$v['id'],$code,'save');echo json_encode(['ok'=>true]);exit;}
 if($a==='logout'){if($v)logEvent($pdo,(int)$v['id'],$code,'logout');echo json_encode(['ok'=>true]);exit;}
 throw new Exception('طلب غير معروف.');
}catch(Throwable $e){http_response_code(400);echo json_encode(['ok'=>false,'message'=>$e->getMessage()],JSON_UNESCAPED_UNICODE);}
