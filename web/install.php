<?php

/**@var string $webRoot */
$webRoot = '';

$action = $_REQUEST['action'];

$lockFile = __DIR__ . DIRECTORY_SEPARATOR . 'install.lock';
$dbDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'database';
$indexExampleFile = __DIR__ . DIRECTORY_SEPARATOR . 'index.php.example';
$indexFile = __DIR__ . DIRECTORY_SEPARATOR . 'index.php';
$pubFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' .DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'dns_pub.php';
$pubTplFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'app' .DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'dns_pub.php.example';
$errors = [];
if (file_exists($lockFile)) {
	exit('系统已初始化，无需重复初始化');
}
if (!is_readable($dbDir)) {
	$errors[] = "权限错误, 目录需要可读权限: " . $dbDir;
}
if (!is_readable($indexExampleFile)) {
	$errors[] = "权限错误, 文件需要可读权限: " . $dbDir;
}
if (file_exists($indexFile)) {
	if (!is_writable($indexFile)) {
		$errors[] = "权限错误, 文件需要可写权限: " . $indexFile;
	}
} else if (!is_writable(__DIR__)) {
	$errors[] = "权限错误, 目录需要可写权限: " . __DIR__;
}
if (!is_readable($pubTplFile)) {
	$errors[] = "权限错误, 文件需要可读权限: " . $pubTplFile;
}
if (file_exists($pubFile)) {
	if (!is_writable($pubFile)) {
		$errors[] = "权限错误, 文件需要可写权限: " . $pubFile;
	}
} else if (!is_writable(dirname($pubFile))) {
	$errors[] = "权限错误, 目录需要可写权限: " . dirname($pubFile);
}

if (!empty($errors)) {
	var_dump($errors);
	exit;
}
if ($action == 'install') {
	//安装
	$db_addr = $_POST['db_addr'];
	if (empty($db_addr)) {
		exit('参数错误:db_addr');
	}
	$db_user = $_POST['db_user'];
	if (empty($db_user)) {
		exit('参数错误:db_user');
	}
	$db_pwd = $_POST['db_pwd'];
	if (empty($db_pwd)) {
		exit('参数错误:db_pwd');
	}
	$db_name = $_POST['db_name'];
	if (empty($db_name)) {
		exit('参数错误:db_name');
	}
	$redis_addr = $_POST['redis_addr'];
	if (empty($redis_addr)) {
		exit('参数错误:redis_addr');
	}
	$admin_user = $_POST['admin_user'];
	if (empty($admin_user)) {
		exit('参数错误:admin_user');
	}
	$admin_pwd = $_POST['admin_pwd'];
	if (empty($admin_pwd)) {
		exit('参数错误:admin_pwd');
	}
	$admin_pwd_repeat = $_POST['admin_pwd_repeat'];
	if (empty($admin_pwd_repeat)) {
		exit('参数错误:admin_pwd_repeat');
	}
	if ($admin_pwd != $admin_pwd_repeat) {
		exit('参数错误:密码不一致');
	}
	//
	$dbCfg = [
		'host'     => $db_addr,
		'database' => $db_name,
		'user'     => $db_user,
		'password' => $db_pwd,
	];
	$handler = mysqli_connect($dbCfg['host'], $dbCfg['user'], $dbCfg['password'], $dbCfg['database'], 3306);
	if (!$handler) {
		print_r($dbCfg);
		var_dump($handler);
		exit;
	}
	foreach (glob($dbDir . DIRECTORY_SEPARATOR . '*.sql') as $filename) {
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
		echo sprintf("<p>%s  , OK</p>",  date('Y-m-d H:i:s') );
		
	}
	
	//
	echo "<p>写入配置文件 {$pubFile} </p>";
	$tplContent = file_get_contents($pubTplFile);
	$content = str_replace("[[db_addr]]", $db_addr, $tplContent);
	$content = str_replace("[[db_user]]", $db_user, $content);
	$content = str_replace("[[db_pwd]]",  $db_pwd, $content);
	$content = str_replace("[[db_name]]", $db_name, $content);
	$content = str_replace("[[redis_addr]]", $redis_addr, $content);
	file_put_contents($pubFile, $content);
	echo sprintf("<p>%s  , OK</p>",  date('Y-m-d H:i:s') );
	//
	echo "<p>web入口文件 {$indexFile} </p>";
	$tplContent = file_get_contents($indexExampleFile);
	$content = str_replace("[[env]]", "pub", $tplContent);
	file_put_contents($indexFile, $content);
	echo sprintf("<p>%s  , OK</p>",  date('Y-m-d H:i:s') );
	//
	echo "<p>创建管理员 </p>";
	include dirname(__DIR__) . DIRECTORY_SEPARATOR . 'extends' . DIRECTORY_SEPARATOR . 'YiiSecurity.php';
	$sql = sprintf("insert into `user` (`username`,`nickname`,`role`,`email`,`phone`,`password_hash`,`status`,`updated_at`,`created_at`) values ('%s','超级管理员',99,'test@test.com','18688889999','%s', '0','%s','%s')"
	, $admin_user, YiiSecurity::generatePasswordHash($admin_pwd), time(), time());
	mysqli_query($handler, $sql);
	echo sprintf("<p>%s  , OK</p>",  date('Y-m-d H:i:s') );
	//
	echo '<p>写入文件 .lock </p>';
	touch($lockFile);
	echo sprintf("<p>%s  , OK</p>",  date('Y-m-d H:i:s') );
	//
	exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta charset="utf-8" />
	<title> 教学管理系统 -- 安装 </title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
	
	<!-- bootstrap & fontawesome -->
	<link rel="stylesheet" href="<?=$webRoot?>/assets/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?=$webRoot?>/assets/font-awesome/4.5.0/css/font-awesome.min.css" />
	
	<!-- text fonts -->
	<link rel="stylesheet" href="<?=$webRoot?>/assets/css/fonts.googleapis.com.css" />
	
	<!-- ace styles -->
	<link rel="stylesheet" href="<?=$webRoot?>/assets/css/ace.min.css" />
	
	<!--[if lte IE 9]>
	<link rel="stylesheet" href="<?=$webRoot?>/assets/css/ace-part2.min.css" />
	<![endif]-->
	<link rel="stylesheet" href="<?=$webRoot?>/assets/css/ace-rtl.min.css" />
	
	<!--[if lte IE 9]>
	<link rel="stylesheet" href="<?=$webRoot?>/assets/css/ace-ie.min.css" />
	<![endif]-->
	
	<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
	
	<!--[if lte IE 8]>
	<script src="<?=$webRoot?>/assets/js/html5shiv.min.js"></script>
	<script src="<?=$webRoot?>/assets/js/respond.min.js"></script>
	<![endif]-->
</head>

<body class="login-layout blur-login">
<div class="main-container">
	<div class="main-content">
		<div class="row">
			<div class="col-sm-10 col-sm-offset-1">
				<div class="login-container">
					<div class="space-6"></div>
					<div class="center">
						<h1><span class="white">数据库配置</span></h1>
					</div>
					<div class="space-6"></div>
					<div class="position-relative">
						<div id="login-box" class="login-box visible widget-box no-border">
							<div class="widget-body">
								<div class="widget-main">
									<div class="space-6"></div>
									<form id="install-form" method="post" action="?action=install">
										<fieldset>
											<div class="form-group">
												<div class="block clearfix">
													<div class="block input-icon input-icon-right">
														<input type="text" class="form-control" name="db_addr" placeholder="数据库地址[DB_ADDR]" id="db_addr" />
														<i class="ace-icon fa fa-location-arrow"></i>
													</div>
												</div>
											</div>
											<div class="form-group">
												<div class="block clearfix">
													<div class="block input-icon input-icon-right">
														<input type="text" class="form-control" name="db_user" placeholder="用户名[DB_USER]" id="db_user" />
														<i class="ace-icon fa fa-user"></i>
													</div>
												</div>
											</div>
											<div class="form-group">
												<div class="block clearfix">
													<div class="block input-icon input-icon-right">
														<input type="password" class="form-control" name="db_pwd" placeholder="密码[DB_PWD]" />
														<i class="ace-icon fa fa-lock"></i>
													</div>
												</div>
											</div>
											<div class="form-group">
												<div class="block clearfix">
													<div class="block input-icon input-icon-right">
														<input type="text" class="form-control" name="db_name" placeholder="数据库[DB_NAME]" id="db_addr" />
														<i class="ace-icon fa fa-book"></i>
													</div>
												</div>
											</div>
											<div class="form-group">
												<div class="block clearfix">
													<div class="block input-icon input-icon-right">
														<input type="text" class="form-control" name="redis_addr" placeholder="Redis地址[REDIS_ADDR]" id="redis_addr" />
														<i class="ace-icon fa fa-book"></i>
													</div>
												</div>
											</div>
											<div class="form-group">
												<div class="block clearfix">
													<div class="block input-icon input-icon-right">
														<input type="text" class="form-control" name="admin_user" placeholder="系统管理员" id="admin_user" />
														<i class="ace-icon fa fa-user"></i>
													</div>
												</div>
											</div>
											<div class="form-group">
												<div class="block clearfix">
													<div class="block input-icon input-icon-right">
														<input type="password" class="form-control" name="admin_pwd" placeholder="管理员密码" id="admin_pwd"/>
														<i class="ace-icon fa fa-lock"></i>
													</div>
												</div>
											</div>
											<div class="form-group">
												<div class="block clearfix">
													<div class="block input-icon input-icon-right">
														<input type="password" class="form-control" name="admin_pwd_repeat" placeholder="密码确认" />
														<i class="ace-icon fa fa-lock"></i>
													</div>
												</div>
											</div>
											<div class="space"></div>
											<div class="clearfix">
												<button type="submit" class="width-35 pull-right btn btn-sm btn-primary">
													<i class="ace-icon fa fa-key"></i>
													<span class="bigger-110">初始化</span>
												</button>
											</div>
											<div class="space-4"></div>
										</fieldset>
									</form>
								</div><!-- /.widget-main -->
							
							</div><!-- /.widget-body -->
						</div><!-- /.login-box -->
					</div><!-- /.position-relative -->
				</div>
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.main-content -->
</div><!-- /.main-container -->

<!-- basic scripts -->

<!--[if !IE]> -->
<script src="<?=$webRoot?>/assets/js/jquery-2.1.4.min.js"></script>

<!-- <![endif]-->

<!--[if IE]>
<script src="<?=$webRoot?>/assets/js/jquery-1.11.3.min.js"></script>
<![endif]-->

<!-- page specific plugin scripts -->
<script src="<?=$webRoot?>/assets/js/jquery.validate.min.js"></script>

<script type="text/javascript">
	if('ontouchstart' in document.documentElement) document.write("<script src='<?=$webRoot?>/assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>

<!-- inline scripts related to this page -->
<script type="text/javascript">
	jQuery(function($) {
		$(document).on('click', '.toolbar a[data-target]', function(e) {
			e.preventDefault();
			var target = $(this).data('target');
			$('.widget-box.visible').removeClass('visible');//hide others
			$(target).addClass('visible');//show target
		});

		$('#install-form').validate({
			errorElement: 'div',
			errorClass: 'help-block',
			focusInvalid: false,
			ignore: "",
			rules: {
				db_addr: {
					required: true
				},
				db_user: {
					required: true
				},
				db_pwd: {
					required: true
				},
				db_name: {
					required: true
				},
				admin_user: {
					required: true
				},
				admin_pwd: {
					required: true
				},
				admin_pwd_repeat: {
					required: true,
					equalTo: '#admin_pwd',
				},
			},
			highlight: function (e) {
				$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
			},
			success: function (e) {
				$(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
				$(e).remove();
			},
			errorPlacement: function (error, element) {
			},
			submitHandler: function (form) {
				form.submit();
			},
			invalidHandler: function (form) {
			}
		});
	});
</script>
</body>
</html>



