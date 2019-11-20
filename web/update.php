<?php

/**@var string $webRoot */
$webRoot = '';

$action = $_REQUEST['action'];

$dbDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'database';
$pubFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' .DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'dns_pub.php';
$errors = [];
if (!is_writable($dbDir)) {
	$errors[] = "权限错误, 目录需要可读权限: " . $dbDir;
}
if (!file_exists($pubFile)) {
	$errors[] = "配置文件不存在: " . $pubFile;
}
$pubConfig = require ($pubFile);
if (empty($pubConfig)) {
	$errors[] = "配置文件错误: " . print_r($pubConfig, true);
}

if (!empty($errors)) {
	var_dump($errors);
	exit;
}
$dbCfg = $pubConfig['database'];
$handler = mysqli_connect($dbCfg['host'], $dbCfg['user'], $dbCfg['password'], $dbCfg['database'], 3306);
if (!$handler) {
	print_r($dbCfg);
	var_dump($handler);
	exit;
}
foreach (glob($dbDir . DIRECTORY_SEPARATOR . '*.sql') as $filename) {
	$sqlLockFile = $filename. ".lock";
	if (file_exists($sqlLockFile)) {
		continue;
	}
	echo sprintf("<p>Execute Sql File : %s</p>" , $filename);
	$content = file_get_contents($filename);
	$sqlList = explode(";", $content);
	foreach ($sqlList as $sql) {
		if (empty($sql)) {
			continue;
		}
		if (mysqli_query($handler, $sql)) {
		
		} else {
			echo sprintf("<p>sql Error: %s</p>", mysqli_error($handler));
			exit;
		}
	}
	touch($sqlLockFile);
	echo sprintf("<p>%s  , OK</p>",  date('Y-m-d H:i:s') );
	
}
mysqli_close($handler);
echo "Update Success !!!!";
