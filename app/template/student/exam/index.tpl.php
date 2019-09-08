<?php include App::$view_root . "/base/header.begin.tpl.php" ?>
<?php include App::$view_root . "/base/header.end.tpl.php" ?>

<div class="page-content">
	<div class="row">
		<div class="col-xs-12">
			<div class="row">
				<table id="simple-table" class="table  table-bordered table-hover">
					<thead>
					<tr>
						<th>ID</th>
						<th>考试题目</th>
						<th>所属课程</th>
						<th>总分</th>
						<th>考试时间</th>
						<th>考试时长</th>
						<th>发布时间</th>
						<th>状态</th>
						<th>得分</th>
						<th></th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($PRM['items'] as $item): ?>
						<tr>
							<td><span><?=$item['id']?></span></td>
							<td><span><?=$item['title']?></span></td>
							<td><b>【<?=$item['course_no']?>】</b><span><?=$item['course_title']?></span></td>
							<td><span><?=sprintf("%0.2f",$item['total']/100 )?>分</span></td>
							<td><span><?=date('Y/m/d H:i',$item['start_at'])?> - <?=date('Y/m/d H:i',$item['end_at'])?></span></td>
							<td><span><?=sprintf("%0.2f",$item['duration']/3600 )?>小时</span></td>
							<td><span><?=date('Y/m/d H:i',$item['created_at'])?></span></td>
							<td><span><?=\app\model\examResult::getStatusName($item['status'])?></span> </td>
							<td><span><?=($item['status']==\app\model\examResult::Status_Check)?$item['score']:'--'?></span></td>
							<td>
								<div class="hidden-sm hidden-xs btn-group">
									<?php if (is_null($item['status'])):?>
									<a href="/exam/edit?examId=<?=$item['id']?>" class="btn btn-xs btn-info" title="去答题">
										<i class="ace-icon fa fa-edit bigger-120"></i>去答题
									</a>
									<?php endif;?>
									<?php if ($item['status'] === \app\model\examResult::Status_Doing):?>
										<a href="/exam/edit/<?=$item['result_id']?>" class="btn btn-xs btn-info" title="继续答题">
											<i class="ace-icon fa fa-edit bigger-120"></i>继续答题
										</a>
									<?php endif;?>
									<?php if ($item['status'] === \app\model\examResult::Status_Submit || $item['status'] === \app\model\examResult::Status_Check):?>
										<a href="/exam/view/<?=$item['result_id']?>" class="btn btn-xs btn-info" title="查看">
											<i class="ace-icon fa fa-edit bigger-120"></i>查看
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
<?php include App::$view_root . "/base/footer.begin.tpl.php" ?>
<!-- inline scripts related to this page -->
<script type="text/javascript">
	$(function () {
	
	});
</script>
<?php include App::$view_root . "/base/footer.end.tpl.php" ?>


