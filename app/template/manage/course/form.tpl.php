<?php

?>
<?php include App::$view_root . "/base/header.begin.tpl.php" ?>
<style>
	.modal-dialog {
		width: 80%;!important;
	}
	.modal {
		z-index: 950;!important;
	}
	.modal-backdrop {
		z-index: 940;!important;
	}
</style>
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
											<th style="width: 10%;">ID</th>
											<th style="width: 10%;">序号</th>
											<th style="width: 50%;">题目</th>
											<th style="width: 20%;">类型</th>
											<th style="width: 20%;">操作</th>
										</tr>
										</thead>
										<tbody>
										<?php foreach ($PRM['items'] as $item): ?>
											<tr class="j-tag-item-<?=$item['id']?>">
												<td class="j-item-id"><span><?=$item['id']?></span></td>
												<td class="j-item-sort"><span><?=$item['sort']?></span></td>
												<td class="j-item-title"><span><?=$item['title']?></span></td>
												<td></td>
												<td>
													<div class="hidden-sm hidden-xs btn-group">
														<a href="#" class="btn btn-xs btn-info btn-edit-question" data-id="<?=$item['id']?>">
															<i class="ace-icon fa fa-edit bigger-120"></i>
														</a>
														<a href="#" class="btn btn-xs btn-info">
															<i class="ace-icon fa fa-question bigger-120"></i>
														</a>
													</div>
												</td>
											</tr>
										<?php endforeach;?>
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


<!-- 题目模态框（Modal） -->
<div class="modal fade" id="questionModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content" style="background: transparent;">
			<div class="widget-box">
				<div class="widget-header">
					<h5 class="widget-title">创建/编辑题目</h5>
				</div>
				<div class="widget-body">
					<div class="widget-main">
						<div class="row">
							<div class="col-sm-12">
								<form id="question-form"  class="form-horizontal" role="form" enctype="multipart/form-data">
									<input type="hidden" name="Question[course_id]" value="<?=$PRM['course']['id']?>">
									<div class="form-group" id="question-id">
										<label class="col-sm-2 control-label no-padding-left" > ID </label>
										<div class="col-xs-10 col-sm-2">
											<input type="text" class="form-control" name="Question[id]" readonly />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-left" >类型</label>
										<div class="col-xs-10 col-sm-9">
											<select class="chosen-select" id="form-field-is_multiple" name="Question[type]">
												<?php foreach (\app\model\question::$TypeNames as $k=>$v): ?>
													<option value="<?=$k?>"><?=$v?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-left" > 题目 </label>
										<div class="col-sm-10 col-sm-9">
											<textarea class="form-control" name="Question[title]"></textarea>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-left" > 排序 </label>
										<div class="col-xs-10  col-sm-9">
											<input type="text" class="form-control" name="Question[sort]" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-left" > 分值 </label>
										<div class="col-xs-10  col-sm-9">
											<input type="text" class="form-control" name="Question[score]" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-left" >状态</label>
										<div class="col-xs-10  col-sm-9">
											<select class="chosen-select" id="form-field-is_multiple" name="Question[status]">
												<?php foreach (\app\model\question::$StatusNames as $k=>$v): ?>
													<option value="<?=$k?>"><?=$v?></option>
												<?php endforeach; ?>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-left" > 内容 </label>
										<div class="col-xs-10 col-sm-9">
											<!--name="Question[content]"-->
											<script id="editor" type="text/plain" style="width:100%;height:300px;"></script>
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
<script type="text/javascript" charset="utf-8" src="<?=$webRoot?>/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="<?=$webRoot?>/ueditor/ueditor.all.min.js"> </script>
<!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
<!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
<script type="text/javascript" charset="utf-8" src="<?=$webRoot?>/ueditor/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript">
	$(function () {
		var ue = UE.getEditor('editor');
		
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


		$('#question-form').validate({
			errorElement: 'div',
			errorClass: 'help-block',
			focusInvalid: false,
			ignore: "",
			rules: {
				'Question[title]': {
					required: true
				},
			},
			submitHandler: function (form) {
				var formData = new FormData($('#question-form')[0]);
				formData.delete("editorValue");
				formData.append("Question[content]", UE.getEditor('editor').getContent());
				postRequest('/manage/question/ajax_edit_post', formData);
			}
		});
		

		$('.btn-create-question').on('click', function (e) {
			$("#question-form")[0].reset();
			$("#question-form").find('input[name="Question[id]"]').val('0');
			ue.setContent('', false);
			refreshChosen();
			$('#question-id').hide();
			$('#questionModal').modal('show');
		});
		
		$('.btn-edit-question').on('click', function (e) {
			var qid = $(this).attr('data-id');
			postRequest('/manage/question/ajax_info?id='+qid,{},function (data) {
				$("#question-form")[0].reset();
				$("#question-form").find('input[name="Question[id]"]').val(data.data.id);
				$("#question-form").find('input[name="Question[sort]"]').val(data.data.sort);
				$("#question-form").find('textarea[name="Question[title]"]').val(data.data.title);
				$("#question-form").find('input[name="Question[score]"]').val((parseInt(data.data.score)/100).toFixed(2));
				ue.setContent(data.data.content ? data.data.content : '', false);
				refreshChosen();
				$('#question-id').show();
				$('#questionModal').modal('show');
			},'get');
		});
		
		

	});
</script>
<?php include App::$view_root . "/base/footer.end.tpl.php" ?>
