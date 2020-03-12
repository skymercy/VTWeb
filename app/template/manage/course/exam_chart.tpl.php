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
								<?php if (!empty($item['result_id']) && ($item['status'] == examResult::Status_Submit || $item['status'] == examResult::Status_Check)):?>
								<div class="hidden-sm hidden-xs btn-group">
									<a href="javascript:;" class="btn btn-xs btn-info btn-view-result" data-id="<?=$item['result_id']?>">
										<i class="ace-icon fa fa-search bigger-120"></i> 查看
									</a>
								</div>
								<?php endif; ?>
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
	<div class="modal fade" id="studentResultInfoModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content" style="background: transparent;">
				<div class="widget-box">
					<div class="widget-header">
						<h5 class="widget-title">
							成绩详情
						</h5>
					</div>
					<div class="widget-body">
						<div class="widget-main">
							<div class="row">
								<div class="col-sm-12">
									<div class="tabbable">
										<ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab4">
											<li class="active">
												<a data-toggle="tab" href="#tab1">试卷详情</a>
											</li>

											<li>
												<a data-toggle="tab" href="#tab2">思考题详情</a>
											</li>

											<li>
												<a data-toggle="tab" href="#tab3">实验详情</a>
											</li>
										</ul>

										<div class="tab-content">
											<div id="tab1" class="tab-pane in active">
											</div>
											<div id="tab2" class="tab-pane">
												<p>...</p>
											</div>

											<div id="tab3" class="tab-pane">
                                                <p>...</p>
											</div>
										</div>
									</div>
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

    function renderAutoData(autoData) {
        var html = '';
        html += "<p>得分: " + autoData.score + "</p>";
        html += "<hr/>";
        for(var k in autoData.questions) {
            var question = autoData.questions[k];
            html += "<p>";
            html += "[" + question.type + "] " + question.title + question.content + "</br>";
            for(var kk in question.items)  {
                var item = question.items[kk];
                html += item.alias + ":&nbsp;&nbsp;<b>" + item.title + item.content + "</b></br>";
            }
            html += "正确答案: " + question.correct_items + "</br>";
            html += "考生答案: " + question.selected_items + "</br>";

            html += "</p>";
            html += "<hr/>";
        }
        $('#tab1').html(html);
    }
	$(function () {
		$('.btn-view-result').on('click', function(){
			var resultId = $(this).attr('data-id');
			postRequest('<?=$routerRoot?>/exam/ajax_result_info?id='+resultId,{},function(data){
				$('#studentResultInfoModal').modal('show');
                renderAutoData(data.data.auto);
			},'get')
		});

	});
</script>
<?php include App::$view_root . "/base/footer.end.tpl.php" ?>