<?php
session_start();
$ticket=trim((string)($_GET['ticket']??''));
$ctx=stream_context_create(['http'=>['method'=>'POST','timeout'=>3,'ignore_errors'=>true,'header'=>"Content-Type: application/x-www-form-urlencoded\r\nX-Client-Secret: change-jadwal-secret\r\n",'content'=>http_build_query(['app'=>'jadwal','ticket'=>$ticket])]]);
$raw=$ticket!==''?@file_get_contents('http://localhost/platform-auth/exchange',false,$ctx):false;$data=$raw?json_decode($raw,true):null;
if(empty($data['ok'])||empty($data['local_user_id'])){header('Location: ../login');exit;}
$c=require dirname(__DIR__).'/app/config/database.php';$db=new PDO("mysql:host={$c['host']};dbname={$c['database']};charset={$c['charset']}",$c['username'],$c['password'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);$stmt=$db->prepare("SELECT u.*,r.name role FROM users u JOIN roles r ON r.id=u.role_id WHERE u.id=? AND u.status='aktif' LIMIT 1");$stmt->execute([(int)$data['local_user_id']]);$user=$stmt->fetch();if(!$user){header('Location: ../login');exit;}
session_regenerate_id(true);$_SESSION['user']=['id'=>$user['id'],'name'=>$user['name'],'email'=>$user['email'],'role'=>$user['role'],'prodi_id'=>$user['prodi_id']];header('Location: ../dashboard');exit;
