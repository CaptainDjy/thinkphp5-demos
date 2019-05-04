<?php
$task = '写文章'; // 任务名称
$duration = 25; // 设置时长
$rest = 5; // 休息时间
$now = time();
$batName = 'clock.bat'; // 调用的bat文件
$relatePath = './' . $batName;
$recordPath = './record_time.md'; // 文本倒计时
$absolutePath = str_replace('php', 'bat', __FILE__);
$seconds = $duration * 60;
file_put_contents($recordPath, '');
for ($i=$seconds; $i > 0; $i--) {
    $hour = floor($i / 3600) ? floor($i / 3600) . '时' : '';
    $minute = floor($i / 60) ? floor($i / 60) . '分' : '';
    $second = $i % 60 ? $i % 60 . '秒' : '';
    $message = "当前任务：{$task}\n当前时间：" . date('Y-m-d H:i:s', time()) . "\n" . "专注时间：{$duration}分钟\n" . "剩下：" . $hour . $minute . $second;
    file_put_contents($recordPath, $message);
    sleep(1);
    if ($i == 1) {
        $content = "Great! You have been working for $duration minute! Now, Relax yourself $rest minute!";
        $content = 'msg * "' . $content . '"';
        file_put_contents($relatePath, $content);
        exec($absolutePath); // 执行bat文件
    }
}
