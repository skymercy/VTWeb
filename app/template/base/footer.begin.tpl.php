</div>
</div><!-- /.main-content -->
<div class="footer">
    <div class="footer-inner">
        <div class="footer-content">
            <!--
            <span class="bigger-120">
                <span class="blue bolder">u79w.com</span>
                &copy; 2018-2088
            </span>
            -->
        </div>
    </div>
</div>
<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
    <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
</a>
</div><!-- /.main-container -->
<!-- page specific plugin scripts -->

<!-- ace scripts -->
<script src="<?=$webRoot?>/assets/js/ace-elements.min.js"></script>
<script src="<?=$webRoot?>/assets/js/ace.min.js"></script>
<script src="<?=$webRoot?>/assets/js/jquery.toast.min.js"></script>
<script src="<?=$webRoot?>/assets/js/chosen.jquery.min.js"></script>
<script src="<?=$webRoot?>/assets/js/main.js"></script>
<script>
	if(!ace.vars['touch']) {
		$('.chosen-select').chosen({allow_single_deselect:true});
		//resize the chosen on window resize

		$(window)
			.off('resize.chosen')
			.on('resize.chosen', function() {
				$('.chosen-select').each(function() {
					var $this = $(this);
//            $this.next().css({'width': $this.parent().width()});
					$this.next().css({'width': '100%'});
				})
			}).trigger('resize.chosen');
		//resize chosen on sidebar collapse/expand
		$(document).off('settings.ace.chosen').on('settings.ace.chosen', function(e, event_name, event_val) {
			if(event_name != 'sidebar_collapsed') return;
			$('.chosen-select').each(function() {
				var $this = $(this);
//          $this.next().css({'width': $this.parent().width()});
				$this.next().css({'width': '100%'});
			})
		});
	}
	var refreshChosen = function () {
		if(!ace.vars['touch']) {
			$('.chosen-select').trigger("chosen:updated");
		}
	};
	var toastError = function(msg) {
		$.toast({
			text: msg,
			position:'top-center',
			bgColor : 'white',              // Background color for toast
			textColor : 'red'
		});
	}
	var postRequest = function(uri, data, success) {
		$.ajax({
			type: 'post',
			contentType: false,
			processData: false,
			url: uri,
			data: data,
			success: function (resp) {
				loading = false;
				if (resp.error == 0) {
					if (success) {
						success(resp);
					} else {
						if (resp.redirectUri) {
							window.location.href = resp.redirectUri;
						} else {
							window.location.reload();
						}
					}
				} else {
					toastError(resp.message);
				}
			}
		});
	}
	var page = <?=(isset($searchData)&&isset($searchData['page'])) ? $searchData['page'] : 1?>;
	var pageNum = <?=(isset($PRM['pages'])&&isset($PRM['pages']['num'])) ? $PRM['pages']['num'] : 1?>;
	var gotoPage = function (v) {
		$('#search-form input[name="SearchData[page]"]').val(v);
		$('#search-form button[type=submit').trigger('click');
	}
	var gotoNext = function () {
		if (page >= pageNum) {
			return;
		}
		gotoPage(page+1);
	}
	var gotoPre = function () {
		if (page <= 1) {
			return;
		}
		gotoPage(page-1);
	}
	var gotoFirst = function () {
		if (page <= 1) {
			return;
		}
		gotoPage(1);
	}
	var gotoLast = function () {
		if (page >= pageNum) {
			return;
		}
		gotoPage(pageNum);
	}
</script>
