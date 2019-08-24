<?php include App::$view_root . "/base/header.begin.tpl.php" ?>
<?php include App::$view_root . "/base/header.end.tpl.php" ?>

<div class="page-content">
	<div class="row">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-12 col-sm-6 widget-container-col">
					<form class="form-horizontal" role="form" id="college-form" method="post" action="#">
						<input type="text" name="_csrf" hidden value="<?=$this->getCsrfToken()?>"/>
						<input type="text" name="csk" hidden value="<?=$csk?>"/>
						<input type="text" name="College[id]" hidden value="<?=$PRM['college']['id']?>"/>
						<?php if ($PRM['college']['id']): ?>
							<div class="form-group">
								<label class="col-sm-2 control-label no-padding-left" for="form-field-title"> 院系ID </label>
								<div class="col-sm-10">
									<input type="text"  class="form-control" readonly value="<?=$PRM['college']['id']?>">
								</div>
							</div>
						<?php endif;?>
						<div class="form-group">
							<label class="col-sm-2 control-label no-padding-left" for="form-field-title"> 名称 </label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="College[title]" value="<?=$PRM['college']['title']?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label no-padding-left" for="form-field-status">上级院系</label>
							<div class="col-xs-10 col-sm-10">
								<select class="chosen-select " id="form-field-status" name="College[pid]">
									<?php foreach($PRM['topColleges'] as $k=>$v): ?>
										<option value="<?=$k?>"
											<?php
											if ((empty($PRM['college']['pid'])&&$k==0) || $PRM['college']['pid'] == $k) {
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
									<?=($PRM['college']['id'])?'保存修改':'创建院系'?>
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
		$('#college-form').validate({
			errorElement: 'div',
			errorClass: 'help-block',
			focusInvalid: false,
			ignore: "",
			rules: {
				'College[title]': {
					required: true
				}
			},
			submitHandler: function (form) {
				postRequest('<?=$routerRoot?>/college/ajax_edit_post', new FormData($('#college-form')[0]));
			}
		});
		refreshChosen();
	});
</script>
<?php include App::$view_root . "/base/footer.end.tpl.php" ?>
