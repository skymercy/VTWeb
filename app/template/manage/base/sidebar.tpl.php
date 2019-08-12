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
		<a href="<?=$routerRoot?>">
			<i class="menu-icon fa fa-tachometer"></i>
			<span class="menu-text"> 首页 </span>
		</a>
		<b class="arrow"></b>
	</li>
    <li class="<?=(isset($sidebar['teacher'])? 'active open' : '')?>">
        <a href="javascript:;" class="dropdown-toggle">
            <i class="menu-icon fa fa-gavel"></i>
            <span class="menu-text">教师管理</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>
        <b class="arrow"></b>
        <ul class="submenu">
            <li class="<?=(isset($sidebar['teacher-create'])? 'active' : '')?>">
                <a href="<?=$routerRoot?>/teacher/create">
                    <i class="menu-icon fa fa-caret-right"></i>
                    添加教师
                </a>
            </li>
            <li class="<?=(isset($sidebar['teacher-index'])? 'active' : '')?>">
                <a href="<?=$routerRoot?>/teacher">
                    <i class="menu-icon fa fa-caret-right"></i>
                    教师列表
                </a>
            </li>
        </ul>
    </li>
	<li class="<?=(isset($sidebar['course'])? 'active open' : '')?>">
		<a href="javascript:;" class="dropdown-toggle">
			<i class="menu-icon fa fa-gavel"></i>
			<span class="menu-text">实验课程管理</span>
			<b class="arrow fa fa-angle-down"></b>
		</a>
		<b class="arrow"></b>
		<ul class="submenu">
			<li class="<?=(isset($sidebar['course-create'])? 'active' : '')?>">
				<a href="<?=$routerRoot?>/course/create">
					<i class="menu-icon fa fa-caret-right"></i>
					添加实验课程
				</a>
			</li>
			<li class="<?=(isset($sidebar['course-index'])? 'active' : '')?>">
				<a href="<?=$routerRoot?>/course">
					<i class="menu-icon fa fa-caret-right"></i>
					实验课程列表
				</a>
			</li>
			<li class="<?=(isset($sidebar['course-exam'])? 'active' : '')?>">
				<a href="<?=$routerRoot?>/exam">
					<i class="menu-icon fa fa-caret-right"></i>
					考试管理
				</a>
			</li>
		</ul>
	</li>
	<li class="<?=(isset($sidebar['college'])? 'active open' : '')?>">
		<a href="javascript:;" class="dropdown-toggle">
			<i class="menu-icon fa fa-gavel"></i>
			<span class="menu-text">院系管理</span>
			<b class="arrow fa fa-angle-down"></b>
		</a>
		<b class="arrow"></b>
		<ul class="submenu">
			<li class="<?=(isset($sidebar['college-create'])? 'active' : '')?>">
				<a href="<?=$routerRoot?>/college/create">
					<i class="menu-icon fa fa-caret-right"></i>
					添加院系
				</a>
			</li>
			<li class="<?=(isset($sidebar['college-index'])? 'active' : '')?>">
				<a href="<?=$routerRoot?>/college">
					<i class="menu-icon fa fa-caret-right"></i>
					院系列表
				</a>
			</li>
		</ul>
	</li>
	<li class="<?=(isset($sidebar['classes'])? 'active open' : '')?>">
		<a href="javascript:;" class="dropdown-toggle">
			<i class="menu-icon fa fa-gavel"></i>
			<span class="menu-text">班级管理</span>
			<b class="arrow fa fa-angle-down"></b>
		</a>
		<b class="arrow"></b>
		<ul class="submenu">
			<li class="<?=(isset($sidebar['classes-create'])? 'active' : '')?>">
				<a href="<?=$routerRoot?>/classes/create">
					<i class="menu-icon fa fa-caret-right"></i>
					添加班级
				</a>
			</li>
			<li class="<?=(isset($sidebar['classes-index'])? 'active' : '')?>">
				<a href="<?=$routerRoot?>/classes">
					<i class="menu-icon fa fa-caret-right"></i>
					班级列表
				</a>
			</li>
		</ul>
	</li>
    <li class="<?=(isset($sidebar['student'])? 'active open' : '')?>">
        <a href="javascript:;" class="dropdown-toggle">
            <i class="menu-icon fa fa-users"></i>
            <span class="menu-text">学生管理</span>
            <b class="arrow fa fa-angle-down"></b>
        </a>
        <b class="arrow"></b>
        <ul class="submenu">
			<li class="<?=(isset($sidebar['student-create'])? 'active' : '')?>">
				<a href="<?=$routerRoot?>/student/create">
					<i class="menu-icon fa fa-caret-right"></i>
					添加学生
				</a>
			</li>
            <li class="<?=(isset($sidebar['student-index'])? 'active' : '')?>">
                <a href="<?=$routerRoot?>/student">
                    <i class="menu-icon fa fa-caret-right"></i>
					学生列表
                </a>
            </li>
        </ul>
    </li>
</ul><!-- /.nav-list -->
<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
	<i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
</div>
