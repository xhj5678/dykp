<?php
class Agent {

    /**
     * 处理一笔订单的代理分佣
     */
    public static function handle($orderId) {

        $cfg = require __DIR__ . '/../config/config.php';
        $agentCfg = $cfg['agent'];

        // 取订单
        $order = DB::fetch("SELECT * FROM `order` WHERE id=$orderId");
        if (!$order || $order['is_agent_done']) {
            return;
        }

        $amount = $order['amount'];
        $fromUid = $order['uid'];

        // 找第 1 个上级
        $parent = DB::fetch("SELECT parent_uid FROM invite WHERE uid=$fromUid");
        if (!$parent || !$parent['parent_uid']) {
            return;
        }

        $rate = $agentCfg['first_rate'];
        $totalRate = 0;
        $level = 1;
        $currentUid = $parent['parent_uid'];

        while ($currentUid && $rate >= $agentCfg['min_rate']) {

            // 超过总分佣上限则停止
            if ($totalRate + $rate > $agentCfg['max_rate']) {
                break;
            }

            $income = round($amount * $rate, 2);
            if ($income <= 0) {
                break;
            }

            // 记录分佣
            DB::query("
                INSERT INTO agent_income(uid,from_uid,order_id,amount,level,time)
                VALUES ($currentUid,$fromUid,$orderId,$income,$level,NOW())
            ");

            // 累加
            $totalRate += $rate;
            $rate *= $agentCfg['decay'];
            $level++;

            // 找上一级
            $next = DB::fetch("SELECT parent_uid FROM invite WHERE uid=$currentUid");
            if (!$next || !$next['parent_uid']) {
                break;
            }
            $currentUid = $next['parent_uid'];
        }

        // 标记订单已分佣
        DB::query("UPDATE `order` SET is_agent_done=1 WHERE id=$orderId");
    }
}