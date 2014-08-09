<!-- BEGIN: main -->
<div class="form-group">
	<button class="btn btn-info btn-block" id="ckeditor-code-format-btn">{LANG.getCode}</button>
</div>
<div class="form-group">
	<textarea class="form-control autoresize" id="ckeditor-code-format-area" rows="10"></textarea>
</div>
<div class="alert alert-info">{LANG.note}</div>
<div class="clearfix">
	<!-- BEGIN: guide -->
	<div class="pull-left">
		<em class="fa fa-book">&nbsp;</em>
		<a href="{DATA.url_guide}">{LANG.guide}</a>
	</div>
	<!-- END: guide -->
	<div class="pull-right">
		<em class="fa fa-exclamation-triangle">&nbsp;</em>
		<a rel="nofollow" id="open-error-popup" href="#">{LANG.send_error}</a>
	</div>
</div>
<script type="text/javascript">
$(function(){
	$('#open-error-popup').click(function(e){
		e.preventDefault();
		nv_open_browse( '{DATA.url_error}', '{LANG.send_error} {DATA.title}', 500, 400, '' );
	});
});
</script>
<!-- END: main -->