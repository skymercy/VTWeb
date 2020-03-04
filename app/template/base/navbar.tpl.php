<?php
/* @var \biny\lib\Response $this */
/* @var string $webRoot */
/* @var string $routerRoot */
/* @var string $tplRoot */
/* @var string $requestRoot */
?>

<div class="navbar-container ace-save-state" id="navbar-container">
	<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
		<span class="sr-only">Toggle sidebar</span>
		
		<span class="icon-bar"></span>
		
		<span class="icon-bar"></span>
		
		<span class="icon-bar"></span>
	</button>
	
	<div class="navbar-header pull-left">
		<a href="<?=$routerRoot?>" class="navbar-brand">
			<small>
				<i class="fa fa-leaf"></i>
				教学管理系统
			</small>
		</a>
	</div>
	<div class="navbar-header  pull-right">
		<a href="<?=$webRoot?>/login/logout"  class="navbar-brand" style="font-size: 14px;">退出登录</a>
	</div>
</div><!-- /.navbar-container -->
