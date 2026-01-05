<?php
require '../core/DB.php';

if ($_POST) {
    $user = $_POST['user'];
    $pass = md5($_POST['pass']);

    // 写死一个管理员（最安全）
    if ($user === 'admin' && $pass === md5('123456')) {
        setcookie('admin', 1, time()+86400, '/');
        header('Location: index.php');
        exit;
    }
    echo '登录失败';
}