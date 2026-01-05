<?php
return [
    // 代理分佣
    'agent' => [
        'max_rate' => 0.4,        // 总分佣上限 40%
        'first_rate' => 0.2,      // 第一代 20%
        'decay' => 0.5,           // 每代衰减 50%
        'min_rate' => 0.005       // 小于 0.5% 停止
    ]
];