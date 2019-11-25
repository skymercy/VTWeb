<?php
use app\model\examResult;
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
			<div class="row" style="">
				<h1>[<span style="color: red;"><?=$PRM['exam']['title']?></span>]考试统计</h1>
				<div>
					总分: <?=sprintf("%0.2f",$PRM['exam']['total']/100 )?>分
					| 考试时长: <?=sprintf("%0.2f",$PRM['exam']['duration']/3600 )?>小时
					| 可答题时间: <?=date('Y/m/d H:i',$PRM['exam']['start_at'])?> - <?=date('Y/m/d H:i',$PRM['exam']['end_at'])?>
				</div>
				<div>
					考试班级: <?=$PRM['exam']['classes']?>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<div class="row">
				<table id="question-table" class="table  table-bordered table-hover">
					<thead>
					<tr>
						<th style="width: 2%;">ID</th>
						<th style="width: 10%;">学号</th>
						<th style="width: 10%;">学生姓名</th>
						<th style="width: 10%;">所属班级</th>
						<th style="width: 5%;">状态</th>
						<th style="width: 10%;">答题时间</th>
						<th style="width: 10%;">交卷时间</th>
						<th style="width: 5%;">考试时长</th>
						<th style="width: 5%;">试卷得分</th>
						<th style="width: 5%;">思考题得分</th>
						<th style="width: 5%;">实验得分</th>
						<th style="width: 5%;">操作</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($PRM['items'] as $item): ?>
						<tr>
							<td><span><?=$item['id']?></span></td>
							<td><span><?=$item['student_no']?></span></td>
							<td><span><?=$item['nickname']?></span></td>
							<td><span><?=$item['class_title']?></span></td>
							<td><span><?=examResult::getStatusName($item['status']) ?></span></td>
							<td><span> <?=(!empty($item['result_id']) && !empty($item['start_at'])) ? date('Y/m/d H:i',$item['start_at']) : '-'  ?> </span></td>
							<td><span> <?=(!empty($item['result_id']) && !empty($item['end_at'])) ? date('Y/m/d H:i',$item['end_at']) : '-' ?> </span></td>
							<td><span> <?=(!empty($item['result_id']) && !empty($item['start_at']) && !empty($item['end_at']) ) ?  ceil(($item['end_at']-$item['start_at'])/60) . '分钟' : '-' ?>  </span></td>
							<td><span> <?=(empty($item['result_id']) || is_null($item['auto_score'])) ? '-' : sprintf("%0.2f分",$item['auto_score']/100 )?> </span></td>
							<td><span> <?=(empty($item['result_id']) || is_null($item['manual_score'])) ? '-' : sprintf("%0.2f分",$item['manual_score']/100 )?> </span></td>
							<td><span> <?=(empty($item['result_id']) || is_null($item['lab_score'])) ? '-' : sprintf("%0.2f分",$item['lab_score']/100 )?> </span></td>
							<td>
								<div class="hidden-sm hidden-xs btn-group">
									<a href="javascript:;" class="btn btn-xs btn-info btn-edit-exam" data-id="<?=$item['id']?>">
										<i class="ace-icon fa fa-search bigger-120"></i> 查看
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

	<!-- 编辑考题（Modal） -->
	<div class="modal fade" id="publishQuestionModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content" style="background: transparent;">
				<div class="widget-box">
					<div class="widget-header">
						<h5 class="widget-title">
							编辑考试信息
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
										<input type="hidden" name="Exam[id]" value="0">
										<input type="hidden" name="Exam[course_id]" value="0">
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
			</div>
		</div>
	</div>


<?php include App::$view_root . "/base/footer.begin.tpl.php" ?>
<!-- inline scripts related to this page -->
<script src="<?=$webRoot?>/assets/js/jquery.validate.min.js"></script>
<script type="text/javascript">
	$(function () {
		
		$('#exam-form').validate({
			errorElement: 'div',
			errorClass: 'help-block',
			focusInvalid: false,
			ignore: "",
			submitHandler: function (form) {
				var formData = new FormData($('#exam-form')[0]);
				postRequest('<?=$routerRoot?>/exam/ajax_edit_post', formData);
			}
		})
		
		$('.btn-edit-exam').on('click', function(){
			var examId = $(this).attr('data-id');
			postRequest('<?=$routerRoot?>/exam/ajax_info?id='+examId,{},function(data){
				$("#exam-form")[0].reset();
				$("#exam-form").find('input[name="Exam[id]"]').val(data.data.id);
				$("#exam-form").find('input[name="Exam[course_id]"]').val(data.data.course_id);
				$("#exam-form").find('input[name="Exam[title]"]').val(data.data.title);
				$("#exam-form").find('input[name="Exam[start_date]"]').val(data.data.start_date);
				$("#exam-form").find('input[name="Exam[start_time]"]').val(data.data.start_time);
				$("#exam-form").find('input[name="Exam[end_date]"]').val(data.data.end_date);
				$("#exam-form").find('input[name="Exam[end_time]"]').val(data.data.end_time);
				$("#exam-form").find('select[name="Exam[duration]"]').val(data.data.duration);
				if (data.classes) {
					var optionHtml = '';
					for(var k in data.classes) {
						if (data.data.classes[k]) {
							optionHtml += '<option value="'+k+'" selected>'+data.classes[k]+'</option>';
						} else {
							optionHtml += '<option value="'+k+'">'+data.classes[k]+'</option>';
						}
						
					}
					$('.j-exam-classes').html(optionHtml);
				}
				refreshChosen();
				$('#publishQuestionModal').modal('show');
			},'get')
		});

	});
</script>
<?php include App::$view_root . "/base/footer.end.tpl.php" ?>