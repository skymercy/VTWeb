<?php include App::$view_root . "/base/header.begin.tpl.php" ?>
<?php include App::$view_root . "/base/header.end.tpl.php" ?>

<div class="page-content">
	<div class="row">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-12 col-sm-12 widget-container-col">
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
							<label class="col-sm-3 control-label no-padding-left" for="form-field-title"> 课程编号 </label>
							<div class="col-sm-7">
								<input type="text" class="form-control" name="Course[unique_no]" value="<?=$PRM['course']['unique_no']?>">
							</div>
						</div>
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
				'Course[unique_no]': {
					required: true
				},
			},
			submitHandler: function (form) {
				postRequest('<?=$routerRoot?>/course/ajax_edit_post', new FormData($('#course-form')[0]));
			}
		});

	});
</script>
<?php include App::$view_root . "/base/footer.end.tpl.php" ?>