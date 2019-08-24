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
	<div class="page-header">
		<h1>
			编辑[<span style="color: red;"><?=$PRM['course']['title']?></span>]的题库
		</h1>
	</div> <!-- /.page-header -->
	<div class="row">
		<div class="col-xs-12">
			<div class="row" style="margin-bottom: 10px;">
				<button class="btn btn-xs btn-warning btn-create-question">
					<i class="ace-icon fa fa-plus bigger-110"></i>
					添加题目
				</button>
				<button class="btn btn-xs btn-info btn-publish">
					<i class="ace-icon fa fa-check bigger-110"></i>
					发布考试
				</button>
			</div>
			<div class="row">
				<table id="question-table" class="table  table-bordered table-hover">
					<thead>
					<tr>
						<th style="width: 5%;">ID</th>
						<th style="width: 5%;">序号</th>
						<th style="width: 30%;">题目</th>
						<th style="width: 10%;">类型</th>
						<th style="width: 10%;">分值</th>
						<th style="width: 10%;">状态</th>
						<th style="width: 10%;">操作</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($PRM['items'] as $item): ?>
						<tr class="j-tag-item-<?=$item['id']?>">
							<td class="j-item-id"><span><?=$item['id']?></span></td>
							<td class="j-item-sort"><span><?=$item['sort']?></span></td>
							<td class="j-item-title"><span><?=$item['title']?></span></td>
							<td class="j-item-type"><span><?=\app\model\question::getTypeName($item['type'])?></span></td>
							<td class="j-item-title"><span><?=sprintf("%0.2f",$item['score']/100 )?>分</span></span></td>
							<td class="j-item-title"><span><?=($item['status']==1)?'已加入试卷':'-'?></span></td>
							<td>
								<div class="hidden-sm hidden-xs btn-group">
									<a href="javascript:;" class="btn btn-xs btn-info btn-edit-question" data-id="<?=$item['id']?>">
										<i class="ace-icon fa fa-edit bigger-120"></i>编辑
									</a>
									<?php if (in_array($item['type'], [\app\model\question::Type_Select, \app\model\question::Type_Select_Multiple])):?>
										<a href="javascript:;" class="btn btn-xs btn-info btn-edit-question-items" data-id="<?=$item['id']?>">
											<i class="ace-icon fa fa-question bigger-120"></i>编辑选项
										</a>
									<?php endif;?>
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

<table style="display: none;">
	<tr id="questionItemItem_Tpl">
		<td><span>{{id}}</span></td>
		<td><span>{{sort}}</span></td>
		<td><span>{{title}}</span></td>
		<td><span>{{content}}</span></td>
		<td><span>{{correct}}</span></td>
		<td>
			<div class="hidden-sm hidden-xs btn-group">
				<a href="javascript:;" class="btn btn-xs btn-info btn-edit-questionItem" data-id="{{id}}">
					<i class="ace-icon fa fa-edit bigger-120"></i>
				</a>
			</div>
		</td>
	</tr>
</table>
	<!-- 题目选项列表模态框（Modal） -->
	<div class="modal fade" id="publishQuestionModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content" style="background: transparent;">
				<div class="widget-box">
					<div class="widget-header">
						<h5 class="widget-title">
							发布考试
						</h5>
					</div>
					<div class="widget-body">
						<div class="widget-main">
							<div class="row">
								<div class="col-sm-12">
									<div class="j-publish-errors">
									
									</div>
								</div>
								<div class="col-sm-12">
									<form id="exam-form"  class="form-horizontal" role="form" enctype="multipart/form-data">
										<input type="hidden" name="Exam[course_id]" value="<?=$PRM['course']['id']?>">
										<div class="form-group">
											<label class="col-sm-2 control-label no-padding-left" > 标题 </label>
											<div class="col-sm-10 col-sm-9">
												<input type="text" class="form-control" name="Exam[title]">
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label no-padding-left" >考试时长</label>
											<div class="col-xs-10  col-sm-3">
												<select class="chosen-select" name="Exam[duration]">
													<option value="1800">0.5小时</option>
													<option value="3600">1小时</option>
													<option value="5400">1.5小时</option>
													<option value="7200">2小时</option>
													<option value="9000">2.5小时</option>
													<option value="10800">3小时</option>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label no-padding-left" >考试班级</label>
											<div class="col-xs-10  col-sm-10">
												<select class="chosen-select j-exam-classes" name="Exam[classes][]" multiple="multiple">
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label no-padding-left" >开始时间</label>
											<div class="col-xs-10  col-sm-9">
												<input type="date" name="Exam[start_date]">
												<input type="time" name="Exam[start_time]">
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label no-padding-left" >结束时间</label>
											<div class="col-xs-10  col-sm-9">
												<input type="date" name="Exam[end_date]">
												<input type="time" name="Exam[end_time]">
											</div>
										</div>
										<div class="clearfix form-actions">
											<div class="col-md-offset-2 col-md-9">
												<button class="btn btn-info" type="submit">
													<i class="ace-icon fa fa-check bigger-110"></i>
													发布
												</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- 题目选项列表模态框（Modal） -->
	<div class="modal fade" id="viewQuestionItemsModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content" style="background: transparent;">
				<div class="widget-box">
					<div class="widget-header">
						<h5 class="widget-title">
							编辑题目[<span id="viewQuestionItemsModal-title" style="color: red;">xxxxx</span>]的选项
						</h5>
						<div>
							<small>
								<button class="btn btn-xs btn-warning btn-create-question-item" type="button" data-id="0">
									<i class="ace-icon fa fa-plus bigger-120"></i>
									添加选项
								</button>
							</small>
						</div>
						<div style="display: block; width: 100%; height: 15px;"></div>
					</div>
					<div class="widget-body">
						<div class="widget-main">
							<div class="row" style="  max-height: 300px; overflow-y: scroll;">
								<table id="questionItems-table" class="table  table-bordered table-hover">
									<thead>
									<tr>
										<th style="width: 5%;">ID</th>
										<th style="width: 5%;">序号</th>
										<th style="width: 10%;">内容</th>
										<th style="width: 50%;">内容2</th>
										<th style="width: 10%">是否正确</th>
										<th style="width: 10%;">操作</th>
									</tr>
									</thead>
									<tbody id="questionItems_TBody"></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- 题目选项模态框（Modal） -->
	<div class="modal fade" id="questionItemModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content" style="background: transparent;">
				<div class="widget-box">
					<div class="widget-header">
						<h5 class="widget-title">创建/编辑选项</h5>
					</div>
					<div class="widget-body">
						<div class="widget-main">
							<div class="row">
								<div class="col-sm-12">
									<form id="questionItem-form"  class="form-horizontal" role="form" enctype="multipart/form-data">
										<input type="hidden" name="QuestionItem[question_id]" value="0">
										<div class="form-group" id="questionItem-id">
											<label class="col-sm-2 control-label no-padding-left" > ID </label>
											<div class="col-xs-10 col-sm-2">
												<input type="text" class="form-control" name="QuestionItem[id]" readonly />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label no-padding-left" > 排序 </label>
											<div class="col-sm-10 col-sm-9">
												<input type="text" class="form-control" name="QuestionItem[sort]" />
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label no-padding-left" >正确</label>
											<div class="col-xs-10  col-sm-9">
												<select class="chosen-select" id="form-field-is_multiple" name="QuestionItem[is_correct]">
													<option value="0">否</option>
													<option value="1">是</option>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label no-padding-left" > 内容 </label>
											<div class="col-sm-10 col-sm-9">
												<textarea class="form-control" name="QuestionItem[title]"></textarea>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-2 control-label no-padding-left" > 内容2 </label>
											<div class="col-xs-10 col-sm-9" id="item-editor-container">
												<script id="editor2" type="text/plain" style="width:100%;height:300px;" ></script>
											</div>
										</div>
										<div></div>
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
	<script id="editor" type="text/plain" style="width:100%;height:300px;" ></script>
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
		var courseId = <?=$PRM['course']['id']?>;
		var questionEditor = null;
		var itemEditor = null;
		
		var createQuestionEditor = function () {
			if(questionEditor == null)
				questionEditor = UE.getEditor('editor');
		};
		var createItemEditor = function () {
			if(itemEditor == null)
				itemEditor = UE.getEditor('editor2');
		};
		
		$('#question-form').validate({
			errorElement: 'div',
			errorClass: 'help-block',
			focusInvalid: false,
			ignore: "",
			submitHandler: function (form) {
				var formData = new FormData($('#question-form')[0]);
				formData.delete("editorValue");
				formData.append("Question[content]", questionEditor.getContent());
				postRequest('<?=$routerRoot?>/question/ajax_edit_post', formData);
			}
		});
		
		$('#exam-form').validate({
			errorElement: 'div',
			errorClass: 'help-block',
			focusInvalid: false,
			ignore: "",
			submitHandler: function (form) {
				var formData = new FormData($('#exam-form')[0]);
				postRequest('<?=$routerRoot?>/course/ajax_publish', formData);
			}
		})

		$('#questionItem-form').validate({
			errorElement: 'div',
			errorClass: 'help-block',
			focusInvalid: false,
			ignore: "",
			rules: {
				'QuestionItem[sort]': {
					required: true
				}
			},
			submitHandler: function (form) {
				var formData = new FormData($('#questionItem-form')[0]);
				formData.delete("editorValue");
				formData.append("QuestionItem[content]", itemEditor.getContent());
				var questionId = $('#questionItem-form').find("input[name='QuestionItem[question_id]']").val();
				postRequest('<?=$routerRoot?>/questionItem/ajax_edit_post', formData, function(resp){
					renderQuestionItems(questionId, resp.items);
					$('#questionItemModal').modal('hide');
				});
			}
		});
		

		$('.btn-create-question').on('click', function (e) {
			$("#question-form")[0].reset();
			$("#question-form").find('input[name="Question[id]"]').val('0');
			createQuestionEditor();
			setTimeout(function () {
				questionEditor.setContent('',false);
			},500);
			refreshChosen();
			$('#question-id').hide();
			$('#questionModal').modal('show');
		});
		
		$('.btn-publish').on('click', function(){
			postRequest('<?=$routerRoot?>/course/ajax_check_publish?id='+courseId,{},function (data) {
				$("#exam-form")[0].reset();
				$(".j-publish-errors").empty();
				$(".j-publish-errors").append('<p>总分:'+data.data.total_score+'</p>');
				if (data.errors.length > 0) {
					for(var k in data.errors) {
						$(".j-publish-errors").append(data.errors[k]);
					}
				}
				if (data.warnings.length > 0) {
					for(var k in data.warnings) {
						$(".j-publish-errors").append(data.warnings[k]);
					}
				}
				if (data.classes) {
					var optionHtml = '';
					for(var k in data.classes) {
						optionHtml += '<option value="'+k+'">'+data.classes[k]+'</option>';
					}
					console.log(optionHtml);
					$('.j-exam-classes').html(optionHtml);
				}
				refreshChosen();
				$('#publishQuestionModal').modal('show');
			},'get');
		});

		$('.btn-create-question-item').on('click', function (e) {
			var questionId = $('.btn-create-question-item').attr('data-id');
			$("#questionItem-form")[0].reset();
			$("#questionItem-form").find('input[name="QuestionItem[id]"]').val('0');
			$("#questionItem-form").find('input[name="QuestionItem[question_id]"]').val(questionId);
			createItemEditor();
			setTimeout(function () {
				itemEditor.setContent('',false);
			},500);
			refreshChosen();
			$('#questionItem-id').hide();
			$('#questionItemModal').modal('show');
		});
		
		$('#questionItems_TBody').on('click','.btn-edit-questionItem', function(e) {
			var itemId = $(this).attr('data-id');
			postRequest('<?=$routerRoot?>/questionItem/ajax_info?id='+itemId, {}, function(data){
				$("#questionItem-form")[0].reset();
				$("#questionItem-form").find('input[name="QuestionItem[id]"]').val(data.data.id);
				$("#questionItem-form").find('input[name="QuestionItem[question_id]"]').val(data.data.question_id);
				$("#questionItem-form").find('input[name="QuestionItem[sort]"]').val(data.data.sort);
				$("#questionItem-form").find('textarea[name="QuestionItem[title]"]').val(data.data.title);
				$("#questionItem-form").find('select[name="QuestionItem[is_correct]"]').val(data.data.is_correct);
				createItemEditor();
				setTimeout(function () {
					itemEditor.setContent(data.data.content ? data.data.content : '',false);
				},500);
				refreshChosen();
				$('#questionItem-id').show();
				$('#questionItemModal').modal('show');
			}, 'get');
		});
		
		$('.btn-edit-question').on('click', function (e) {
			var qid = $(this).attr('data-id');
			postRequest('<?=$routerRoot?>/question/ajax_info?id='+qid,{},function (data) {
				$("#question-form")[0].reset();
				$("#question-form").find('input[name="Question[id]"]').val(data.data.id);
				$("#question-form").find('input[name="Question[sort]"]').val(data.data.sort);
				$("#question-form").find('textarea[name="Question[title]"]').val(data.data.title);
				$("#question-form").find('input[name="Question[score]"]').val((parseInt(data.data.score)/100).toFixed(2));
				$("#question-form").find('select[name="Question[type]"]').val(data.data.type);
				$("#question-form").find('select[name="Question[status]"]').val(data.data.status);
				createQuestionEditor();
				setTimeout(function () {
					questionEditor.setContent(data.data.content ? data.data.content : '', false);
				},500);
				refreshChosen();
				$('#question-id').show();
				$('#questionModal').modal('show');
			},'get');
		});
		
		$('.btn-edit-question-items').on('click', function (e) {
			var qid = $(this).attr('data-id');
			postRequest('<?=$routerRoot?>/question/ajax_items?id='+qid,{},function (data) {
				$('#viewQuestionItemsModal-title').text(data.data.title);
				$('.btn-create-question-item').attr('data-id',data.data.id);
				//渲染
				renderQuestionItems(data.data.id, data.items);
			},'get');
			$('#viewQuestionItemsModal').modal('show');
		});
		
		var questionItemsCached = {};
		var renderQuestionItems = function (questionId, items) {
			if (questionItemsCached[questionId] == null) {
				questionItemsCached[questionId] = {};
			}
			if (items == null || items.length <= 0) {
				$('#questionItems_TBody').html('');
				return;
			}
			for(var i = 0; i<items.length; i++) {
				questionItemsCached[questionId]['id_' + items[i].id] = items[i];
			}
			var htmlTxt = "";
			for(var k in questionItemsCached[questionId]) {
				var item = questionItemsCached[questionId][k];
				var tplTxt = $('#questionItemItem_Tpl').html();
				tplTxt = tplTxt.replace("{{id}}", item.id);
				tplTxt = tplTxt.replace("{{id}}", item.id);
				tplTxt = tplTxt.replace("{{sort}}", item.sort);
				tplTxt = tplTxt.replace("{{title}}", item.title);
				tplTxt = tplTxt.replace("{{content}}", item.content);
				tplTxt = tplTxt.replace("{{correct}}", item.is_correct==1?'是':'否');
				htmlTxt += '<tr>'+ tplTxt + '</tr>';
			}
			$('#questionItems_TBody').html(htmlTxt);
		}

	});
</script>
<?php include App::$view_root . "/base/footer.end.tpl.php" ?>