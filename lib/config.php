<?php
$configs = [
    'debug' => false,
    'open' => true,
    'cacheTime' => 60*10,
    'dataDir' => dirname(__DIR__) . '/data',
    'reportDir' => dirname(__DIR__) . '/public/reports',
//    'redis' => [
//        'host' => '10.0.0.*',
//        'port' => '6379',
//        'auth' => '**',
//    ],
];
//获取域名
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'default';
$configs['host'] = $host;
//获取代码目录
$script = isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : '';
$configs['script'] = $script;

if ($configs['debug']) {
    //加载数据库配置
} else {
    //加载php配置
    $generateConfigFile = $configs['dataDir'] . '/generateConfigs.php';
    if (is_file($generateConfigFile)) {
        include $generateConfigFile;
    }
}

return $configs;