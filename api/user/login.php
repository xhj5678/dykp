<?php
require '../../core/DB.php';
require '../../core/Response.php';

$username = trim($_POST['username']);
$password = trim($_POST['password']);

if (!$username || !$password) {
    Response::json([], 400, '参数不完整');
}

$pwd = md5($password);
$user = DB::fetch("
    SELECT id,invite_code 
    FROM user 
    WHERE username='$username' AND password='$pwd'
");

if (!$user) {
    Response::json([], 401, '账号或密码错误');
}

// 登录成功
setcookie('uid', $user['id'], time()+86400*30, '/');

Response::json([
    'uid' => $user['id'],
    'invite_code' => $user['invite_code']
]);