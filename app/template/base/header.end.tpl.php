<?php
/* @var \biny\lib\Response $this */
/* @var string $webRoot */
/* @var string $routerRoot */
/* @var string $tplRoot */
/* @var string $requestRoot */
?>
<!-- ace styles -->
<link rel="stylesheet" href="<?=$webRoot?>/assets/css/ace.min.css" class="ace-main-stylesheet" id="main-ace-style" />

<!--[if lte IE 9]>
<link rel="stylesheet" href="<?=$webRoot?>/assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
<![endif]-->
<link rel="stylesheet" href="<?=$webRoot?>/assets/css/ace-skins.min.css" />
<link rel="stylesheet" href="<?=$webRoot?>/assets/css/ace-rtl.min.css" />

<!--[if lte IE 9]>
<link rel="stylesheet" href="<?=$webRoot?>/assets/css/ace-ie.min.css" />
<![endif]-->

<!-- inline styles related to this page -->
<link rel="stylesheet" href="<?=$webRoot?>/assets/css/chosen.min.css" />
<!-- ace settings handler -->
<script src="<?=$webRoot?>/assets/js/ace-extra.min.js"></script>

<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

<!--[if lte IE 8]>
<script src="<?=$webRoot?>/assets/js/html5shiv.min.js"></script>
<script src="<?=$webRoot?>/assets/js/respond.min.js"></script>
<![endif]-->

<script type="text/javascript">
	var loading = false;
    var webRoot = '<?=$webRoot?>';
    var routerRoot = '<?=$routerRoot?>';
	var tplRoot = '<?=$tplRoot?>';
	var requestRoot = '<?=$requestRoot?>';
</script>
</head>
<body class="no-skin">
<div id="navbar" class="navbar navbar-default ace-save-state">
	<?php include App::$view_root ."/base/navbar.tpl.php" ?>
</div>
<div class="main-container ace-save-state" id="main-container">
    <script type="text/javascript">
        try{ace.settings.loadState('main-container')}catch(e){}
    </script>
    <div id="sidebar" class="sidebar responsive ace-save-state">
		<?php
			if ($tplRoot == '/student' && isset($sidebar['exam-edit'])) {
				include App::$view_root . $tplRoot . "/base/exam-nav.tpl.php";
			} else {
				include App::$view_root . $tplRoot . "/base/sidebar.tpl.php";
			}
		?>
    </div>
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs ace-save-state" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <a href="/">首页</a>
                    </li>
                    <?php foreach ($breadcrumbs as $breadcrumb): ?>
                        <?php if($breadcrumb['active']): ?>
                            <li class="active"><?=$breadcrumb['name']?></li>
                        <?php else: ?>
                            <li>
                                <a href="#"><?=$breadcrumb['name']?></a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach;?>
                </ul>
            </div>