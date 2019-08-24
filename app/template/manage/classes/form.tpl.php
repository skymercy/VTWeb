<?php include App::$view_root . "/base/header.begin.tpl.php" ?>
<?php include App::$view_root . "/base/header.end.tpl.php" ?>

<div class="page-content">
	<div class="row">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-12 col-sm-6 widget-container-col">
					<form class="form-horizontal" role="form" id="classes-form" method="post" action="#">
						<input type="text" name="_csrf" hidden value="<?=$this->getCsrfToken()?>"/>
						<input type="text" name="csk" hidden value="<?=$csk?>"/>
						<input type="text" name="Classes[id]" hidden value="<?=$PRM['classes']['id']?>"/>
						<?php if ($PRM['classes']['id']): ?>
							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-left" for="form-field-title"> 班级ID </label>
								<div class="col-sm-10">
									<input type="text"  class="form-control" readonly value="<?=$PRM['classes']['id']?>">
								</div>
							</div>
						<?php endif;?>
						<div class="form-group">
							<label class="col-sm-2 control-label no-padding-left" for="form-field-title"> 班级名 </label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="Classes[title]" value="<?=$PRM['classes']['title']?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label no-padding-left" for="form-field-status">所属院系</label>
							<div class="col-xs-10 col-sm-10">
								<select class="chosen-select " id="form-field-status" name="Classes[college_id]">
									<?php foreach($PRM['allColleges'] as $k=>$v): ?>
										<option value="<?=$k?>"
											<?php
											if ((empty($PRM['classes']['college_id'])&&$k==0) || $PRM['classes']['college_id'] == $k) {
												echo "selected";
											}
											?>>
											<?=$v?>
										</option>
									<?php endforeach;?>
								</select>
							</div>
						</div>
						<div class="clearfix form-actions">
							<div class="col-md-offset-2 col-md-9">
								<button class="btn btn-info" type="submit">
									<i class="ace-icon fa fa-check bigger-110"></i>
									<?=($PRM['classes']['id'])?'保存修改':'创建班级'?>
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
		$('#classes-form').validate({
			errorElement: 'div',
			errorClass: 'help-block',
			focusInvalid: false,
			ignore: "",
			rules: {
				'Classes[title]': {
					required: true
				},
			},
			submitHandler: function (form) {
				postRequest('<?=$routerRoot?>/classes/ajax_edit_post', new FormData($('#classes-form')[0]));
			}
		});
	});
</script>
<?php include App::$view_root . "/base/footer.end.tpl.php" ?>
