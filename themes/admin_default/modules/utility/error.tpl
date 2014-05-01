<!-- BEGIN: main -->
<form action="" method="post" name="levelnone" id="levelnone">
	<table class="tab1">
		<thead>
			<tr>
				<td>{LANG.error_title}</td>
				<td>{LANG.error_name}</td>
				<td>{LANG.error_email}</td>
				<td>{LANG.error_ip}</td>
				<td>{LANG.error_addtime}</td>
				<td style="width:50px">{LANG.error_status}</td>
				<td style="width:90px" class="center">{LANG.feature}</td>
			</tr>
		</thead>
		<!-- BEGIN: row -->
		<tbody{ROW.class}>
			<tr class="topalign">
				<td><strong><a href="{ROW.url_edit}" title="{ROW.title}">{ROW.title}</a></strong></td>
				<td>{ROW.name} - {ROW.username}</td>
				<td><a href="mailto:{ROW.email}" title="{ROW.email}">{ROW.email}</a></td>
				<td>{ROW.ip}</td>
				<td>{ROW.addtime}</td>
				<td>{ROW.status}</td>
				<td class="center">
					<span class="search_icon"><a href="javascript:void(0);" onclick="nv_view_error({ROW.id});">{LANG.error_view}</a></span>
					&nbsp;&nbsp;
					<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_delete_error({ROW.id});">{GLANG.delete}</a></span>
				</td>
			</tr>
		</tbody>
		<!-- END: row -->
		<!-- BEGIN: generate_page -->
		<tbody>
			<tr>
				<td colspan="7">
					{GENERATE_PAGE}
				</td>
			</tr>
		</tbody>
		<!-- END: generate_page -->
	</table>
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
