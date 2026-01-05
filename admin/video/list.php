<?php
require '../common/auth.php';
require '../../core/DB.php';

$list = DB::fetchAll("SELECT * FROM video ORDER BY id DESC");
foreach ($list as $v) {
    echo "{$v['id']} | {$v['title']} | VIP:{$v['is_vip']} 
    <a href='edit.php?id={$v['id']}'>编辑</a>
    <a href='delete.php?id={$v['id']}'>删除</a><br>";
}