<?php
require '../common/auth.php';
require '../../core/DB.php';

$uid = intval($_GET['uid']);

$list = DB::fetchAll("
    SELECT u.id,u.username
    FROM invite i
    JOIN user u ON i.uid=u.id
    WHERE i.parent_uid=$uid
");

foreach ($list as $u) {
    echo "下级：{$u['username']}<br>";
}