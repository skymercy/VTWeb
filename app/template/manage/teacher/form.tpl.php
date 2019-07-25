<?php include App::$view_root . "/base/header.begin.tpl.php" ?>
<?php include App::$view_root . "/base/header.end.tpl.php" ?>

<div class="page-content">
	<div class="row">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-12 col-sm-6 widget-container-col">
					<form class="form-horizontal" role="form" id="teacher-form" method="post" action="#">
						<input type="text" name="_csrf" hidden value="<?=$this->getCsrfToken()?>"/>
						<input type="text" name="csk" hidden value="<?=$csk?>"/>
						<input type="text" name="Teacher[id]" hidden value="<?=$PRM['teacher']['id']?>"/>
						<?php if ($PRM['teacher']['id']): ?>
							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-left" for="form-field-title"> 用户ID </label>
								<div class="col-sm-10">
									<input type="text"  class="form-control" readonly value="<?=$PRM['teacher']['id']?>">
								</div>
							</div>
						<?php endif;?>
						<div class="form-group">
							<label class="col-sm-2 control-label no-padding-left" for="form-field-title"> 登录名 </label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="Teacher[username]" value="<?=$PRM['teacher']['username']?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label no-padding-left" for="form-field-title"> 真实姓名 </label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="Teacher[nickname]" value="<?=$PRM['teacher']['nickname']?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label no-padding-left" for="form-field-title"> 联系电话 </label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="Teacher[phone]" value="<?=$PRM['teacher']['phone']?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label no-padding-left" for="form-field-title"> 电子邮箱 </label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="Teacher[email]" value="<?=$PRM['teacher']['email']?>">
							</div>
						</div>
						<?php if (!$PRM['teacher']['id']): ?>
						<div class="form-group">
							<label class="col-sm-2 control-label no-padding-left" for="form-field-title"> 登录密码 </label>
							<div class="col-sm-10">
								<input type="password" id="password" class="form-control" name="Teacher[password]" value="">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label no-padding-left" for="form-field-title"> 登录密码确认 </label>
							<div class="col-sm-10">
								<input type="password" class="form-control" name="Teacher[password_repeat]" value="">
							</div>
						</div>
						<?php endif; ?>
						<div class="clearfix form-actions">
							<div class="col-md-offset-2 col-md-9">
								<button class="btn btn-info" type="submit">
									<i class="ace-icon fa fa-check bigger-110"></i>
									<?=($PRM['course']['id'])?'保存修改':'创建教师账号'?>
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
		$('#teacher-form').validate({
			errorElement: 'div',
			errorClass: 'help-block',
			focusInvalid: false,
			ignore: "",
			rules: {
				'Teacher[username]': {
					required: true
				},
				'Teacher[nickname]': {
					required: true
				},
				'Teacher[phone]': {
					required: true
				},
				'Teacher[email]': {
					required: true,
					email:true
				},
				<?php if (!$PRM['course']['id']): ?>
				'Teacher[password]': {
					required: true
				},
				'Teacher[password_repeat]': {
					required: true,
					equalTo:"#password"
				},
				<?php endif;?>
			},
			submitHandler: function (form) {
				postRequest('/manage/teacher/ajax_edit_post', new FormData($('#teacher-form')[0]));
			}
		});
	});
</script>
<?php include App::$view_root . "/base/footer.end.tpl.php" ?>
