<?php include App::$view_root . "/base/header.begin.tpl.php" ?>
<?php include App::$view_root . "/base/header.end.tpl.php" ?>

<div class="page-content">
	<div class="page-header">
		<h1>
			编辑[<span style="color: red;"><?=$PRM['course']['title']?></span>]的班级
		</h1>
	</div> <!-- /.page-header -->
	<div class="row">
		<div class="col-xs-12">
			<div class="row" style="margin-bottom: 10px;">
				<button class="btn btn-xs btn-warning btn-bind-classes">
					<i class="ace-icon fa fa-plus bigger-110"></i>
					添加班级
				</button>
			</div>
			<div class="row">
				<table id="simple-table" class="table  table-bordered table-hover">
					<thead>
					<tr>
						<th>班级ID</th>
						<th>班级名</th>
						<th>院系</th>
						<th>上级院系</th>
						<th>创建时间</th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($PRM['items'] as $item): ?>
						<tr>
							<td><span><?=$item['classes_id']?></span></td>
							<td><span><?=$item['title']?></span></td>
							<td><span><?=$item['title1']?></span></td>
							<td><span><?=$item['title2']?></span></td>
							<td>
								<div class="hidden-sm hidden-xs btn-group">
									<a href="javascript:;" class="btn btn-xs btn-info">
										<i class="ace-icon fa fa-remove bigger-120"></i>删除
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

<!-- 添加班级模态框（Modal） -->
<div class="modal fade" id="bindClassesModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content" style="background: transparent;">
			<div class="widget-box">
				<div class="widget-header">
					<h5 class="widget-title">添加班级</h5>
				</div>
				<div class="widget-body">
					<div class="widget-main">
						<div class="row">
							<div class="col-sm-12">
								<form id="classesCourse-form"  class="form-horizontal" role="form" enctype="multipart/form-data">
									<input type="hidden" name="ClassesCourse[course_id]" value="<?=$PRM['course']['id']?>">
									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-left" > 班级 </label>
										<div class="col-xs-10 col-sm-10">
											<select class="chosen-select " id="form-field-status" name="ClassesCourse[classes_id]">
												<?php foreach($PRM['unbindClasses'] as $k=>$v): ?>
													<option value="<?=$k?>" > <?=$v?> </option>
												<?php endforeach;?>
											</select>
										</div>
									</div>
									<div class="clearfix form-actions">
										<div class="col-md-offset-2 col-md-9">
											<button class="btn btn-info" type="submit">
												<i class="ace-icon fa fa-check bigger-110"></i>
												保存
											</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal -->
</div>
											
<?php include App::$view_root . "/base/footer.begin.tpl.php" ?>
<!-- inline scripts related to this page -->
<script src="<?=$webRoot?>/assets/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	$(function () {
		$('#classesCourse-form').validate({
			errorElement: 'div',
			errorClass: 'help-block',
			focusInvalid: false,
			ignore: "",
			rules: {
				'ClassesCourse[course_id]': {
					required: true
				},
				'ClassesCourse[classes_id]': {
					required: true
				}
			},
			submitHandler: function (form) {
				postRequest('<?=$routerRoot?>/course/ajax_bind_classes', new FormData($('#classesCourse-form')[0]));
			}
		});

		$('.btn-bind-classes').on('click', function (e) {
			$('#bindClassesModal').modal('show');
		});
		
		refreshChosen();
	});
</script>
<?php include App::$view_root . "/base/footer.end.tpl.php" ?>


