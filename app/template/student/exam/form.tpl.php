<?php include App::$view_root . "/base/header.begin.tpl.php" ?>
<?php include App::$view_root . "/base/header.end.tpl.php" ?>
<form id="exam-form">
	<input type="hidden" name="_csrf" value="<?=$this->getCsrfToken()?>"/>
	<input type="hidden" name="ExamResult[id]" value="<?=$PRM['result']['id']?>">
	<div class="page-content">
		<div class="row">
			<div class="col-xs-12">
				<?php
				$cnt = 1;
				$existResultContent = $PRM['result']['content'];
				?>
				<?php foreach ($PRM['exam'] as $k=>$questions): ?>
					<?php foreach ($questions as $question):?>
						<div class="col-xs-12 col-sm-12 widget-container-col" id="question-<?=$cnt?>">
							<div class="widget-box" id="widget-box-1">
								<div class="widget-header">
									<h5 class="widget-title"><b>[第<?=$cnt?>题, <?=\app\model\question::getTypeName($k)?>]</b><span style="color: black;"> <?=$question['title']?></span></h5>
								</div>
								<div class="widget-body">
									<div class="widget-main">
										<div><?=$question['content']?></div>
										<?php if ($k == \app\model\question::Type_Select):?>
											<?php foreach ($question['items'] as $item):?>
												<div class="radio">
													<label>
														<input name="ExamContent[<?=$question['id']?>]" type="radio" class="ace input-lg" value="<?=$item['id']?>"
															<?php if (isset($existResultContent[$question['id']]) && $existResultContent[$question['id']] == $item['id']) {echo "checked='checked'";} ?>
														/>
														<span class="lbl bigger-120"><?=$item['title']?></span>
														<?=$item['content']?>
													</label>
												</div>
											<?php endforeach;?>
										<?php elseif ($k == \app\model\question::Type_Select_Multiple):?>
											<?php foreach ($question['items'] as $item):?>
												<div class="checkbox">
													<label class="block">
														<input name="ExamContent[<?=$question['id']?>][]" type="checkbox" class="ace input-lg" value="<?=$item['id']?>"
															<?php if (isset($existResultContent[$question['id']]) && in_array($item['id'], $existResultContent[$question['id']]->values(false)) ) {echo "checked='checked'";} ?>
														/>
														<span class="lbl bigger-120"><?=$item['title']?></span>
														<?=$item['content']?>
													</label>
												</div>
											<?php endforeach;?>
										<?php elseif ($k == -1):?>
											<textarea rows="10" cols="100" name="ExamContent[<?=$question['id']?>]"><?=(isset($existResultContent[$question['id']])) ? $existResultContent[$question['id']] : '' ?></textarea>
										<?php endif;?>
									</div>
								</div>
							</div>
						</div>
						<?php $cnt ++;?>
					<?php endforeach;?>
				<?php endforeach;?>
			</div>
		</div>
	</div>
	<div class="clearfix form-actions" style="position: fixed; display: block; height: 80px; bottom: -20px;width: 100%;">
		<div class="col-md-offset-2 col-md-9">
			<button class="btn btn-info j-btn-save" type="button">
				<i class="ace-icon fa fa-check bigger-110"></i>
				保存当前数据
			</button>
			<button class="btn btn-info j-btn-submit" type="button">
				<i class="ace-icon fa fa-check bigger-110"></i>
				提交考卷
			</button>
		</div>
	</div>
</form>
<?php include App::$view_root . "/base/footer.begin.tpl.php" ?>
<!-- inline scripts related to this page -->
<script type="text/javascript">
	$(function () {
		$('.j-btn-save').on('click', function () {
			postRequest('<?=$routerRoot?>/exam/ajax_save', new FormData($('#exam-form')[0]), function(resp){
				console.log(resp);
			});
		});
		$('.j-btn-submit').on('click', function () {
			postRequest('<?=$routerRoot?>/exam/ajax_submit', new FormData($('#exam-form')[0]));
		});
	});
</script>
<?php include App::$view_root . "/base/footer.end.tpl.php" ?>






