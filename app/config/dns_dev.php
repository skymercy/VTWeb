<?php
return array(
    'database' => array(
		'host'     => '192.168.0.193',
		'database' => 'db_vtweb',
		'user'     => 'root',
		'password' => 'u79Wlaozhou',
		'encode' => 'utf8mb4',
        'port' => 3306,
        'keep-alive' => true,
    ),
    'slaveDb' => array(
        'host'     => '127.0.0.1',
        'database' => 'Biny',
        'user'     => 'root',
        'password' => 'root',
        'encode' => 'utf8',
        'port' => 3306,
    ),
    'memcache' => array(
        'host' => '127.0.0.1',
        'port' => 12121,
        'keep-alive' => true,
    ),
    'memcache2' => array(
        'host' => '127.0.0.1',
        'port' => 21212,
        'keep-alive' => true,
    ),
	'redis' => array(
		'host'     => '192.168.0.193',
		'port' => 6379,
		'keep-alive' => true,
	),
);