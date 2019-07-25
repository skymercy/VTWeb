<?php
/**@var $PRM array */
/**@var $searchData array */

$PRM['pagination'] = [
	'total' => 10,
	'page' => 1,
	'cnt_page' => 1,
];
?>

<?php if (!empty($PRM['pagination'])): ?>
	<?php  $pagination = $PRM['pagination']; ?>
	<div class="space-4"></div>
	<div class="message-footer clearfix">
		<form method="get">
			<input type="hidden" name="Search[page]" value="<?=$searchData['page']?>">
		</form>
		<div class="pull-left">共<?=$pagination['total']?>项</div>
		<div class="pull-right">
			<div class="inline middle"> <?=$pagination['page']?> / <?=$pagination['cnt_page']?> </div>
			&nbsp; &nbsp;
			<ul class="pagination middle">
				<li class="disabled">
					<span>
						<i class="ace-icon fa fa-step-backward middle"></i>
					</span>
				</li>
				<li class="disabled">
					<span>
						<i class="ace-icon fa fa-caret-left bigger-140 middle"></i>
					</span>
				</li>
				<li>
					<span>
						<input value="1" maxlength="3" type="text" name="goToPage"/>
					</span>
				</li>
				<li>
					<a href="#" class="">
						<i class="ace-icon fa fa-caret-right bigger-140 middle"></i>
					</a>
				</li>
				<li>
					<a href="#">
						<i class="ace-icon fa fa-step-forward middle"></i>
					</a>
				</li>
			</ul>
		</div>
	</div>
	<script type="text/javascript">
      jQuery(function($) {
      });
	</script>
<?php endif;?>

