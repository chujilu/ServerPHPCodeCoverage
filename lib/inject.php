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
    //return;
}

//全局开关
$configs = require __DIR__ . '/config.php';
if (!$configs['open']) {
    return;
}

//查找任务
if(empty($configs['tasks'])) {
    return;
}
$scriptFileName = isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : 'default';
$currentTask = null;
foreach ($configs['tasks'] as $task) {
    if(strpos($scriptFileName, $task['dir']) === 0 && (empty($task['host']) || $task['host'] === '*' || $task['host'] == $configs['host']) && $task['status'] === 'run') {
        $currentTask = $task;
    }
}
if ($currentTask === null) {
    return;
}
$configs['currentTask'] = $currentTask;

include_once dirname(__DIR__) . '/vendor/autoload.php';
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\FileIterator\Facade as FileIteratorFacade;

//添加白文件白名单
$includeDir = $configs['currentTask']['dir'];
$excludeDir = explode("\n", $configs['currentTask']['excludeDir']);
foreach ($excludeDir as $excludeDirKey => $excludeDirValue) {
    $excludeDirValue = trim($excludeDirValue);
    if (empty($excludeDirValue)) {
        unset($excludeDir[$excludeDirKey]);
    } else {
        $excludeDir[$excludeDirKey] = $excludeDirValue;
    }
}

$coverage = new CodeCoverage;
if (isset($configs['cacheTime']) && $configs['cacheTime'] > 0) {
    $cacheFilesByPhpFile = $configs['dataDir'] . '/' . $configs['currentTask']['id'] . '.files.php';
    if (is_file($cacheFilesByPhpFile)) {
        $cacheFilesByPhp = require $cacheFilesByPhpFile;
        if ($cacheFilesByPhp['expire'] >= time()) {
            $files = $cacheFilesByPhp['files'];
        }
    }
}
if (!isset($files)) {
    $facade = new FileIteratorFacade;
    $files  = $facade->getFilesAsArray($includeDir, '.php', '', $excludeDir);
    if (isset($configs['cacheTime']) && $configs['cacheTime'] > 0) {
        file_put_contents($cacheFilesByPhpFile, "<?php /* auto gengerate */ \n return " . var_export(['expire' => time()+$configs['cacheTime'], 'files' => $files], true) . ';');
    }
}

foreach ($files as $file) {
    $coverage->filter()->addFileToWhitelist($file);
}
$coverage->start((string)$configs['currentTask']['id']);

//注销自动加载函数
$autoloadFunctions = spl_autoload_functions();
foreach($autoloadFunctions as $function) {
    spl_autoload_unregister($function);
}

//结束
register_shutdown_function(function() use ($coverage, $configs) {
    register_shutdown_function(function() use ($coverage, $configs) {
        $coverage->stop();

        if (!empty($configs['redis'])) {
            $redis = new Redis();
            $redis->connect($configs['redis']['host'], $configs['redis']['port']);
            $redis->auth($configs['redis']['auth']);
            $redisResult = $redis->get($configs['currentTask']['id'] . '.xzpcc');
            if (!empty($redisResult)) {
                $content = $redisResult;
            }
        } else {
            $oldDataFile = $configs['dataDir'] . '/' . $configs['currentTask']['id'] . '.xzpcc';
            if (is_file($oldDataFile)) {
                $content = file_get_contents($oldDataFile);
            }
        }
        if (!empty($content)) {
            $oldCoverage = unserialize($content);
        }
        if (isset($oldCoverage)) {
            $oldCoverage->merge($coverage);
        } else {
            $oldCoverage = $coverage;
        }

        if (!empty($configs['redis'])) {
            $redis->set($configs['currentTask']['id'] . '.xzpcc', serialize($oldCoverage));
        } else {
            $r = file_put_contents($oldDataFile, serialize($oldCoverage));
        }

        // if (!is_file($oldDataFile)) {
        //     file_put_contents($oldDataFile, serialize($oldCoverage), LOCK_EX);
        // } else {
        //     fseek($oldDataFile, 0);
        //     fwrite($oldDataFile, serialize($oldCoverage));
        //     fflush($oldDataFile);
        //     flock($oldDataFile, LOCK_UN);
        //     fclose($oldDataFile);
        // }

//        if (isset($_GET['_GENERATE_REPORT']) && $_GET['_GENERATE_REPORT'] == $configs['currentTask']['id']) {
//            $writer = new \SebastianBergmann\CodeCoverage\Report\Html\Facade;
//            $writer->process($coverage, $configs['reportDir'] . '/' . $configs['currentTask']['id']);
//        }
    });
});
