<?php
/**
 * 使用最精简代码
 * 兼容php5 php7
 * 配置此脚本到php.ini内的auto_prepend_script
 */
//命令行模式禁用
if(!function_exists("is_cli")) {
    function is_cli(){
        return preg_match("/cli/i", php_sapi_name()) ? 1 : 0;
    }
}
if(is_cli()) {
    return;
}

//统计代码禁用
if (isset($_SERVER['SCRIPT_FILENAME']) && strpos($_SERVER['SCRIPT_FILENAME'], dirname(__DIR__)) === 0) {
    return;
}

//全局开关
$configs = require __DIR__ . '/config.php';
if (!$configs['open']) {
    return;
}

include_once dirname(__DIR__) . '/vendor/autoload.php';
use SebastianBergmann\CodeCoverage\CodeCoverage;

$coverage = new CodeCoverage;
$coverage->filter()->addDirectoryToWhitelist('/Users/chujilu/PhpstormProjects/user-service/src');
$coverage->start($host);

register_shutdown_function(function() use ($coverage, $configs) {
    register_shutdown_function(function() use ($coverage, $configs) {
        $coverage->stop();

        $oldDataFile = $configs['dataDir'] . $configs['id'] . '.xzpcc';
        if (is_file($oldDataFile)) {
            $content = file_get_contents($oldDataFile);
            if (!empty($content)) {
                $oldCoverage = unserialize($content);
            }
        }
        if (isset($oldCoverage)) {
            $oldCoverage->merge($coverage);
        } else {
            $oldCoverage = $coverage;
        }

        file_put_contents($oldDataFile, serialize($oldCoverage));

        // if (!is_file($oldDataFile)) {
        //     file_put_contents($oldDataFile, serialize($oldCoverage), LOCK_EX);
        // } else {
        //     fseek($oldDataFile, 0);
        //     fwrite($oldDataFile, serialize($oldCoverage));
        //     fflush($oldDataFile);
        //     flock($oldDataFile, LOCK_UN);
        //     fclose($oldDataFile);
        // }

        if (isset($_GET['_GENERATE_REPORT']) && $_GET['_GENERATE_REPORT'] == $configs['id']) {
            $writer = new \SebastianBergmann\CodeCoverage\Report\Html\Facade;
            $writer->process($coverage, $configs['dataDir'] . 'code-coverage-report/' . $configs['id']);
        }
    });
});
