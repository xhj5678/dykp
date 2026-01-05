<?php
require '../../core/DB.php';
require '../../core/Response.php';

$page = max(1, intval($_GET['page'] ?? 1));
$size = 20;
$offset = ($page - 1) * $size;

$list = DB::fetchAll("
    SELECT id,title,cover,is_vip,price,duration
    FROM video
    WHERE status = 1
    ORDER BY id DESC
    LIMIT $offset,$size
");

Response::json([
    'list' => $list,
    'page' => $page
]);