<?php
require '../common/auth.php';
require '../../core/DB.php';

// 第三方接口地址
$api = 'https://example.com/api/videos';

$json = file_get_contents($api);
$data = json_decode($json, true);

if (!$data || empty($data['list'])) {
    exit('接口无数据');
}

foreach ($data['list'] as $v) {

    $title = addslashes($v['name']);
    $play  = addslashes($v['url']);

    // 去重：同播放地址只进一次
    $exists = DB::fetch("SELECT id FROM video WHERE play_url='$play'");
    if ($exists) {
        continue;
    }

    DB::query("
        INSERT INTO video
        (title,play_url,cover,duration,category,tags,is_vip,price,status)
        VALUES (
            '$title',
            '$play',
            '{$v['pic']}',
            {$v['time']},
            '{$v['type']}',
            '{$v['type']}',
            0,
            0,
            1
        )
    ");
}

echo '采集完成';