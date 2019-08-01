<?php include App::$view_root . "/base/header.begin.tpl.php" ?>
<?php include App::$view_root . "/base/header.end.tpl.php" ?>

<div class="page-content">
	<div class="row">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-12 col-sm-<?=($PRM['course']['id'])?'3':'6'?> widget-container-col">
					<form class="form-horizontal" role="form" id="course-form" method="post" action="#">
						<input type="text" name="_csrf" hidden value="<?=$this->getCsrfToken()?>"/>
						<input type="text" name="csk" hidden value="<?=$csk?>"/>
						<input type="text" name="Course[id]" hidden value="<?=$PRM['course']['id']?>"/>
						<?php if ($PRM['course']['id']): ?>
							<div class="form-group">
								<label class="col-sm-3 control-label no-padding-left" for="form-field-title"> 实验课程ID </label>
								<div class="col-sm-7">
									<input type="text"  class="form-control" readonly value="<?=$PRM['course']['id']?>">
								</div>
							</div>
						<?php endif;?>
						<div class="form-group">
							<label class="col-sm-3 control-label no-padding-left" for="form-field-title"> 实验课程名 </label>
							<div class="col-sm-7">
								<input type="text" class="form-control" name="Course[title]" value="<?=$PRM['course']['title']?>">
							</div>
						</div>
						<div class="clearfix form-actions">
							<div class="col-md-offset-2 col-md-9">
								<button class="btn btn-info" type="submit">
									<i class="ace-icon fa fa-check bigger-110"></i>
									<?=($PRM['course']['id'])?'保存修改':'创建实验课程'?>
								</button>
							</div>
						</div>
					</form>
				</div>
				<?php if ($PRM['course']['id']): ?>
				<div class="col-xs-12 col-sm-9 widget-container-col">
					<div class="widget-box">
						<div class="widget-header">
							<h5 class="widget-title">题库
								<small>
									<button class="btn btn-xs btn-warning btn-create-question" type="button">
										<i class="ace-icon fa fa-plus bigger-120"></i>
										添加
									</button>
								</small>
							</h5>
						</div>
						<div class="widget-body">
							<div class="widget-main">
								<div class="row" style="  max-height: 300px; overflow-y: scroll;">
									<table id="question-table" class="table  table-bordered table-hover">
										<thead>
										<tr>
											<th style="width: 10%;">序号</th>
											<th style="width: 50%;">题目</th>
											<th style="width: 20%;">类型</th>
											<th style="width: 20%;">操作</th>
										</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<?php include App::$view_root . "/base/footer.begin.tpl.php" ?>
<!-- inline scripts related to this page -->
<script src="<?=$webRoot?>/assets/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	$(function () {
		$('#course-form').validate({
			errorElement: 'div',
			errorClass: 'help-block',
			focusInvalid: false,
			ignore: "",
			rules: {
				'Course[title]': {
					required: true
				},
			},
			submitHandler: function (form) {
				postRequest('/manage/course/ajax_edit_post', new FormData($('#course-form')[0]));
			}
		});
	});
</script>
<?php include App::$view_root . "/base/footer.end.tpl.php" ?>
