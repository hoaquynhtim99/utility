<!-- BEGIN: main -->
<div class="panel-body">
	<h2 class="text-center unti-error-title">{LANG.send_error}: {DATA.title}</h2>
	<!-- BEGIN: complete -->
	<div class="alert alert-success">
		<p>{LANG.error_thank}</p>
		<div class="text-center"><strong><a href="javascript:window.close();" title="{LANG.error_close}">[{LANG.error_close}]</a></strong></div>
	</div>
	<!-- END: complete -->
	<!-- BEGIN: error -->
	<div class="alert alert-danger">
		<p>{ERROR}</p>
		<div class="text-center"><strong><a href="javascript:window.close();" title="{LANG.error_close}">[{LANG.error_close}]</a></strong></div>
	</div>
	<!-- END: error -->
	<!-- BEGIN: form -->
	<form action="{DATA.form_action}" method="post" onsubmit="return checkform();" role="form">
		<div class="form-group">
			<input class="form-control" onfocus="if(this.value == '{LANG.error_entername}') {this.value = '';}" onblur="if (this.value == '') {this.value = '{LANG.error_entername}';}" type="text" name="name" value="{LANG.error_entername}" maxlength="100"/>
		</div>
		<div class="form-group">
			<input class="form-control" onfocus="if(this.value == '{LANG.error_enteremail}') {this.value = '';}" onblur="if (this.value == '') {this.value = '{LANG.error_enteremail}';}" type="text" name="email" value="{LANG.error_enteremail}" maxlength="100"/>
		</div>
		<div class="form-group">
			<textarea class="form-control" onfocus="if(this.value == '{LANG.error_content}') {this.value = '';}" onblur="if (this.value == '') {this.value = '{LANG.error_content}';}" name="body" rows="5">{LANG.error_content}</textarea>
		</div>
		<div class="text-center">
			<input class="btn btn-primary" type="submit" name="submit" value="{LANG.error_submit}"/>
		</div>
	</form>
	<script type="text/javascript">
	function checkform(){
		var name = $('input[name=name]').val();
		var email = $('input[name=email]').val();
		var body = $('textarea[name=body]').val();
		
		if((name=='')||(name=='{LANG.error_entername}')){
			alert('{LANG.error_entername}'); return false;
		}
		if((email=='')||(email=='{LANG.error_enteremail}')){
			alert('{LANG.error_enteremail}'); return false;
		}
		if((body=='')||(body=='{LANG.error_content}')){
			alert('{LANG.error_content}'); return false;
		}
		return true;
	}
	</script>
	<!-- END: form -->
</div>
<!-- END: main -->