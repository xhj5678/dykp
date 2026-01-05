<?php
require '../common/auth.php';
require '../../core/DB.php';

if ($_POST) {
    DB::query("
        INSERT INTO video(title,play_url,cover,is_vip,price,status)
        VALUES (
            '{$_POST['title']}',
            '{$_POST['play_url']}',
            '{$_POST['cover']}',
            {$_POST['is_vip']},
            {$_POST['price']},
            1
        )
    ");
    echo '添加成功';
}