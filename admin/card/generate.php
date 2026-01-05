<?php
require '../common/auth.php';
require '../../core/DB.php';

if ($_POST) {
    $num  = intval($_POST['num']);
    $vid  = intval($_POST['vid']); // 绑定视频ID

    for ($i=0; $i<$num; $i++) {
        $code = strtoupper(substr(md5(uniqid().rand()),0,16));
        DB::query("
            INSERT INTO card(code,type,value,used)
            VALUES ('$code','video',$vid,0)
        ");
        echo $code."<br>";
    }
    exit;
}
?>

<form method="post">
    生成数量：<input name="num"><br>
    视频ID：<input name="vid"><br><br>
    <button>生成卡密</button>
</form>