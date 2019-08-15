<?php

/**@var array $breadcrumbs*/
/* @var \biny\lib\Response $this */
/* @var string $webRoot */
/* @var string $routerRoot */
/* @var string $tplRoot */
/* @var string $requestRoot */

?>
<script type="text/javascript">
    try{ace.settings.loadState('sidebar')}catch(e){}
</script>
<ul class="nav nav-list">
<?php $cnt = 1;?>
<?php foreach ($PRM['exam'] as $k=>$questions):?>
	<li class="open">
		<a href="#type-<?=$k?>" class="dropdown-toggle">
			<i class="menu-icon fa fa-gavel"></i>
			<span class="menu-text"><?=\app\model\question::getTypeName($k)?></span>
			<b class="arrow fa fa-angle-down"></b>
		</a>
		<b class="arrow"></b>
		<ul class="submenu">
			<?php foreach ($questions as $question):?>
				<li>
					<a href="#question-<?=$cnt?>">
						<i class="menu-icon fa fa-caret-right"></i>
						第<?=$cnt?>题
					</a>
				</li>
				<?php $cnt ++;?>
			<?php endforeach;?>
		</ul>
	</li>
<?php endforeach;?>
</ul>
<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
	<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
</div>
