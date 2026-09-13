<?php
session_start();
$config=require __DIR__.'/../config/config.php';
$dsn="mysql:host={$config['db']['host']};dbname={$config['db']['name']};charset={$config['db']['charset']}";
try{$pdo=new PDO($dsn,$config['db']['user'],$config['db']['pass'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);}catch(Throwable $e){http_response_code(500);exit('Database connection failed. Check your database settings.');}
function e($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
function csrf_token(){if(empty($_SESSION['csrf']))$_SESSION['csrf']=bin2hex(random_bytes(32));return $_SESSION['csrf'];}
function verify_csrf(){if(!hash_equals($_SESSION['csrf']??'',$_POST['csrf']??'')){http_response_code(419);exit('Invalid request.');}}
function require_login(){if(empty($_SESSION['user_id'])){header('Location:/login.php');exit;}}
function current_user(PDO $pdo){static $u=null;if($u!==null)return $u;if(empty($_SESSION['user_id']))return null;$s=$pdo->prepare('SELECT * FROM users WHERE id=?');$s->execute([$_SESSION['user_id']]);return $u=$s->fetch()?:null;}
function require_admin(PDO $pdo){require_login();$u=current_user($pdo);if(!$u||$u['role']!=='admin'){http_response_code(403);exit('Forbidden');}}
function flash($key,$value=null){if($value!==null){$_SESSION['flash'][$key]=$value;return;} $v=$_SESSION['flash'][$key]??null;unset($_SESSION['flash'][$key]);return $v;}
function money($n){return '₦'.number_format((float)$n,2);}
function notify(PDO $pdo,int $uid,string $title,string $message){$s=$pdo->prepare('INSERT INTO notifications(user_id,title,message) VALUES(?,?,?)');$s->execute([$uid,$title,$message]);}
function phone_ok(string $phone):bool{return (bool)preg_match('/^0[789][01]\d{8}$/',$phone);}
function provider_ok(array $r):bool{return (($r['code']??'')==='success')||(($r['status']??'')==='success')||(($r['success']??false)===true)||str_contains(strtolower((string)($r['message']??'')),'completed');}
function provider_ref(array $r){return $r['data']['order_id']??$r['data']['reference']??$r['reference']??null;}
