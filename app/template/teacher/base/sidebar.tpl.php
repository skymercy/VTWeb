<?php

/**@var array $breadcrumbs*/

?>
<script type="text/javascript">
    try{ace.settings.loadState('sidebar')}catch(e){}
</script>
<div class="sidebar-shortcuts" id="sidebar-shortcuts">
	<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
		<a class="btn btn-success" href="javascript:;">
			<i class="ace-icon fa fa-signal"></i>
		</a>
		<a class="btn btn-info" href="javascript:;">
			<i class="ace-icon fa fa-gavel"></i>
		</a>
		<a class="btn btn-warning"  href="javascript:;">
			<i class="ace-icon fa fa-users"></i>
		</a>
		<button class="btn btn-danger">
			<i class="ace-icon fa fa-cogs"></i>
		</button>
	</div>
</div><!-- /.sidebar-shortcuts -->
<ul class="nav nav-list">
	<li class="<?=(isset($sidebar['index'])? 'active open' : '')?>">
		<a href="<?=$webRoot?>/admin/">
			<i class="menu-icon fa fa-tachometer"></i>
			<span class="menu-text"> 首页 </span>
		</a>
		<b class="arrow"></b>
	</li>
    <li class="<?=(isset($sidebar['course'])? 'active open' : '')?>">
        <a href="javascript:;" class="dropdown-toggle">
            <i class="menu-icon fa fa-gavel"></i>
            <span class="menu-text">课程管理</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>
        <b class="arrow"></b>
        <ul class="submenu">
            <li class="<?=(isset($sidebar['course-create'])? 'active' : '')?>">
                <a href="<?=$webRoot?>/admin/course/create">
                    <i class="menu-icon fa fa-caret-right"></i>
                    添加课程
                </a>
            </li>
            <li class="<?=(isset($sidebar['course-index'])? 'active' : '')?>">
                <a href="<?=$webRoot?>/admin/course">
                    <i class="menu-icon fa fa-caret-right"></i>
                    课程列表
                </a>
            </li>
        </ul>
    </li>
    <li class="<?=(isset($sidebar['user'])? 'active open' : '')?>">
        <a href="javascript:;" class="dropdown-toggle">
            <i class="menu-icon fa fa-users"></i>
            <span class="menu-text">用户管理</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>
        <b class="arrow"></b>
        <ul class="submenu">
            <li class="<?=(isset($sidebar['user-index'])? 'active' : '')?>">
                <a href="<?=$webRoot?>/admin/user">
                    <i class="menu-icon fa fa-caret-right"></i>
                    用户列表
                </a>
            </li>
        </ul>
    </li>
    <li class="<?=(isset($sidebar['chart'])? 'active open' : '')?>">
        <a href="javascript:;" class="dropdown-toggle">
            <i class="menu-icon fa fa-line-chart"></i>
            <span class="menu-text">数据统计</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>
        <b class="arrow"></b>
        <ul class="submenu">
            <li class="<?=(isset($sidebar['chart-index'])? 'active' : '')?>">
                <a href="#">
                    <i class="menu-icon fa fa-caret-right"></i>
                    用户统计
                </a>
            </li>
        </ul>
    </li>

</ul><!-- /.nav-list -->
<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
	<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
</div>
