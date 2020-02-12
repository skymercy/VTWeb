<?php

/**@var string $webRoot */
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta charset="utf-8" />
	<meta name="keywords" content="<?=$this->encode($this->keywords) ?: "教学管理系统"?>">
	<meta name="description" content="<?=$this->encode($this->description) ?: "教学管理系统"?>">
	<title><?=$this->encode($this->title) ?: "教学管理系统"?></title>
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
						<h1><span class="white">教学管理系统</span></h1>
					</div>
					<div class="space-6"></div>
					<div class="position-relative">
						<div id="login-box" class="login-box visible widget-box no-border">
							<div class="widget-body">
								<div class="widget-main">
									<div class="space-6"></div>
									<form id="login-form" method="post" action="<?=$routerRoot?>/login/login">
										<input type="text" name="_csrf" hidden value="<?=$this->getCsrfToken()?>"/>
										<fieldset>
											<div class="form-group">
												<div class="block clearfix">
													<div class="block input-icon input-icon-right">
														<input type="text" class="form-control" name="username" placeholder="Username" id="username" />
														<i class="ace-icon fa fa-user"></i>
													</div>
												</div>
											</div>
											<div class="form-group">
												<div class="block clearfix">
													<div class="block input-icon input-icon-right">
														<input type="password" class="form-control" name="password" placeholder="Password" />
														<i class="ace-icon fa fa-lock"></i>
													</div>
												</div>
											</div>
											<div class="space"></div>
											<div class="clearfix">
												<button type="submit" class="width-35 pull-right btn btn-sm btn-primary">
													<i class="ace-icon fa fa-key"></i>
													<span class="bigger-110">登录</span>
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

		$('#login-form').validate({
			errorElement: 'div',
			errorClass: 'help-block',
			focusInvalid: false,
			ignore: "",
			rules: {
				password: {
					required: true
				},
				username: {
					required: true
				}
			},
			messages: {
				password: {
					required: "请输入密码",
				},
				username: {
					required: "请输入用户名",
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

