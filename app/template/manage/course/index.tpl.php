<?php include App::$view_root . "/base/header.begin.tpl.php" ?>
<?php include App::$view_root . "/base/header.end.tpl.php" ?>

<div class="page-content">
	<div class="row" style="margin-bottom: 10px;">
		<a href="<?=$routerRoot?>/course/create" class="btn btn-xs btn-warning">
			<i class="ace-icon fa fa-plus bigger-110"></i>
			添加实验课程
		</a>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<div class="row">
				<table id="simple-table" class="table  table-bordered table-hover">
					<thead>
					<tr>
						<th>ID</th>
						<th>编号</th>
						<th>实验课程</th>
						<th>创建时间</th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($PRM['items'] as $item): ?>
						<tr>
							<td><span><?=$item['id']?></span></td>
							<td><span><?=$item['unique_no']?></span></td>
							<td><span><?=$item['title']?></span></td>
							<td><span><?=date('Y/m/d H:i:s', $item['created_at'])?></span></td>
							<td>
								<div class="hidden-sm hidden-xs btn-group">
									<a href="<?=$routerRoot?>/course/edit/<?=$item['id']?>" class="btn btn-xs btn-info" title="编辑信息">
										<i class="ace-icon fa fa-edit bigger-120"></i>编辑信息
									</a>
									<a href="<?=$routerRoot?>/course/questions/<?=$item['id']?>" class="btn btn-xs btn-info" title="编辑题库">
										<i class="ace-icon fa fa-book bigger-120"></i>编辑题库
									</a>
									<a href="<?=$routerRoot?>/course/classes/<?=$item['id']?>" class="btn btn-xs btn-info" title="编辑班级">
										<i class="ace-icon fa fa-book bigger-120"></i>编辑班级
									</a>
								</div>
							</td>
						</tr>
					<?php endforeach;?>
					</tbody>
				</table>
				<div class="message-footer clearfix">
					<div class="pull-left"> 共<?=$PRM['pages']['total']?>项</div>
					<div class="pull-right">
						<div class="inline middle"><?=$searchData['page']?>/<?=$PRM['pages']['num']?> </div>
						<ul class="pagination middle">
							<li><a href="javascript:gotoFirst()"><i class="ace-icon fa fa-step-backward middle"></i></a></li>
							<li><a href="javascript:gotoPre()"><i class="ace-icon fa fa-caret-left bigger-140 middle"></i></a></li>
							<li><span><input value="1" maxlength="3" type="text"></span></li>
							<li><a href="javascript:gotoNext()"><i class="ace-icon fa fa-caret-right bigger-140 middle"></i></a></li>
							<li><a href="javascript:gotoLast()"><i class="ace-icon fa fa-step-forward middle"></i></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include App::$view_root . "/base/footer.begin.tpl.php" ?>
<!-- inline scripts related to this page -->
<script type="text/javascript">
	$(function () {
	
	});
</script>
<?php include App::$view_root . "/base/footer.end.tpl.php" ?>


