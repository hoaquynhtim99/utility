<!-- BEGIN: main -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
		<title>{LANG.send_error}</title>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.min.js"></script>
		<style type="text/css">
		body{font-family:Arial, Helvetica, sans-serif;font-size:12px;padding:10px;margin:0;background-color:#F9F9F9;color:#333333}
		input[type=text],textarea{border:1px #CCCCCC solid;margin-bottom:10px;color:#666666;font-size:12px;font-family:Arial, Helvetica, sans-serif;padding:3px}
		h3{margin:0;font-size:18px}
		.center{text-align:center}
		.infook{text-align:center;color:#006600;font-weight:bold}
		.infoerror{text-align:center;color:#FF0000;font-weight:bold}
		</style>
	</head>
    <body>
		<h3 class="center">{LANG.send_error}: {DATA.title}</h3>
		<p>&nbsp;</p>
		<!-- BEGIN: complete -->
		<p class="infook">{LANG.error_thank}</p>
		<p class="center"><strong><a class="infook" href="javascript:window.close();" title="{LANG.error_close}">[{LANG.error_close}]</a></strong></p>
		<!-- END: complete -->
		<!-- BEGIN: error -->
		<p class="infoerror">{ERROR}</p>
		<p class="center"><strong><a class="infook" href="javascript:window.close();" title="{LANG.error_close}">[{LANG.error_close}]</a></strong></p>
		<!-- END: error -->
		<!-- BEGIN: form -->
		<form class="center" action="{DATA.form_action}" method="post" onsubmit="return checkform();">
			<input onfocus="if(this.value == '{LANG.error_entername}') {this.value = '';}" onblur="if (this.value == '') {this.value = '{LANG.error_entername}';}" type="text" name="name" value="{LANG.error_entername}" maxlength="100" style="width:96%"/>
			<input onfocus="if(this.value == '{LANG.error_enteremail}') {this.value = '';}" onblur="if (this.value == '') {this.value = '{LANG.error_enteremail}';}" type="text" name="email" value="{LANG.error_enteremail}" maxlength="100" style="width:96%"/>
			<textarea onfocus="if(this.value == '{LANG.error_content}') {this.value = '';}" onblur="if (this.value == '') {this.value = '{LANG.error_content}';}" name="body" style="width:96%;height:100px">{LANG.error_content}</textarea>
			<input type="submit" name="submit" value="{LANG.error_submit}"/>
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
    </body>
</html>
<!-- END: main -->