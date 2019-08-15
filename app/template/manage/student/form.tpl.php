<?php include App::$view_root . "/base/header.begin.tpl.php" ?>
<?php include App::$view_root . "/base/header.end.tpl.php" ?>

<div class="page-content">
	<div class="row">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-12 col-sm-6 widget-container-col">
					<form class="form-horizontal" role="form" id="student-form" method="post" action="#">
						<input type="text" name="_csrf" hidden value="<?=$this->getCsrfToken()?>"/>
						<input type="text" name="csk" hidden value="<?=$csk?>"/>
						<input type="text" name="Student[id]" hidden value="<?=$PRM['student']['id']?>"/>
						<?php if ($PRM['student']['id']): ?>
							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-left" for="form-field-title"> 用户ID </label>
								<div class="col-sm-10">
									<input type="text"  class="form-control" readonly value="<?=$PRM['student']['id']?>">
								</div>
							</div>
						<?php endif;?>
						<div class="form-group">
							<label class="col-sm-2 control-label no-padding-left" for="form-field-title"> 登录名 </label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="Student[username]" value="<?=$PRM['student']['username']?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label no-padding-left" for="form-field-title"> 真实姓名 </label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="Student[nickname]" value="<?=$PRM['student']['nickname']?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label no-padding-left" >班级</label>
							<div class="col-xs-10  col-sm-9">
								<select class="chosen-select" id="form-field-is_multiple" name="Student[classes_id]">
									<option value="0">--请选择--</option>
									<?php foreach ($PRM['classes'] as $k=>$v): ?>
										<option value="<?=$k?>"
											<?php
											if ((empty($PRM['student']['classes_id'])&&$k==0) || $PRM['student']['classes_id'] == $k) {
												echo "selected";
											}
											?>>
												<?=$v?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label no-padding-left" for="form-field-title"> 学号 </label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="Student[student_no]" value="<?=$PRM['student']['student_no']?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label no-padding-left" for="form-field-title"> 联系电话 </label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="Student[phone]" value="<?=$PRM['student']['phone']?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label no-padding-left" for="form-field-title"> 电子邮箱 </label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="Student[email]" value="<?=$PRM['student']['email']?>">
							</div>
						</div>
						<?php if (!$PRM['student']['id']): ?>
						<div class="form-group">
							<label class="col-sm-2 control-label no-padding-left" for="form-field-title"> 登录密码 </label>
							<div class="col-sm-10">
								<input type="password" id="password" class="form-control" name="Student[password]" value="">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label no-padding-left" for="form-field-title"> 登录密码确认 </label>
							<div class="col-sm-10">
								<input type="password" class="form-control" name="Student[password_repeat]" value="">
							</div>
						</div>
						<?php endif; ?>
						<div class="clearfix form-actions">
							<div class="col-md-offset-2 col-md-9">
								<button class="btn btn-info" type="submit">
									<i class="ace-icon fa fa-check bigger-110"></i>
									<?=($PRM['student']['id'])?'保存修改':'创建学生账号'?>
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include App::$view_root . "/base/footer.begin.tpl.php" ?>
<!-- inline scripts related to this page -->
<script src="<?=$webRoot?>/assets/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	$(function () {
		$('#student-form').validate({
			errorElement: 'div',
			errorClass: 'help-block',
			focusInvalid: false,
			ignore: "",
			rules: {
				'Student[username]': {
					required: true
				},
				'Student[nickname]': {
					required: true
				},
				'Student[phone]': {
					required: true
				},
				'Student[email]': {
					required: true,
					email:true
				},
				<?php if (!$PRM['course']['id']): ?>
				'Student[password]': {
					required: true
				},
				'Student[password_repeat]': {
					required: true,
					equalTo:"#password"
				},
				<?php endif;?>
			},
			submitHandler: function (form) {
				postRequest('/manage/student/ajax_edit_post', new FormData($('#student-form')[0]));
			}
		});
	});
</script>
<?php include App::$view_root . "/base/footer.end.tpl.php" ?>
