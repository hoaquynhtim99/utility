<!-- BEGIN: main -->
<form class="form-inline" action="" method="post" name="levelnone" id="levelnone">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>{LANG.error_title}</th>
					<th>{LANG.error_name}</th>
					<th>{LANG.error_email}</th>
					<th>{LANG.error_ip}</th>
					<th>{LANG.error_addtime}</th>
					<th style="width:50px">{LANG.error_status}</th>
					<th style="width:90px" class="text-center">{LANG.feature}</th>
				</tr>
			</thead>
			<tbody>
			<!-- BEGIN: row -->
				<tr class="topalign">
					<td><strong><a href="{ROW.url_edit}" title="{ROW.title}">{ROW.title}</a></strong></td>
					<td>{ROW.name} - {ROW.username}</td>
					<td><a href="mailto:{ROW.email}" title="{ROW.email}">{ROW.email}</a></td>
					<td>{ROW.ip}</td>
					<td>{ROW.addtime}</td>
					<td>{ROW.status}</td>
					<td class="text-center">
						<span class="search_icon"><a href="javascript:void(0);" onclick="nv_view_error({ROW.id});">{LANG.error_view}</a></span>
						&nbsp;&nbsp;
						<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_delete_error({ROW.id});">{GLANG.delete}</a></span>
					</td>
				</tr>
			<!-- END: row -->
			<tbody>
			<!-- BEGIN: generate_page -->
			<tbody>
				<tr>
					<td colspan="7">
						{GENERATE_PAGE}
					</td>
				</tr>
			<!-- END: generate_page -->
			<tbody>
		</table>
	</div>
</form>
<script type="text/javascript">
function nv_view_error( id )
{
	Shadowbox.open({
		content : "<iframe src=\"{URL_VIEW}&amp;id=" + id + "\" border=\"1\" frameborder=\"0\" style=\"width:600px; height:400px\"></iframe>",
		player : "html",
		width : 600,
		height : 400
	});
}
</script>	
<!-- END: main -->