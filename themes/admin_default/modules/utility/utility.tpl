<!-- BEGIN: main -->
<form action="" method="post" name="levelnone" id="levelnone">
	<table class="tab1">
		<thead>
			<tr>
				<td>{LANG.alias}</td>
				<td>{LANG.content_ftitle}</td>
				<td style="width:90px" class="center">{LANG.feature}</td>
			</tr>
		</thead>
		<!-- BEGIN: row -->
		<tbody>
			<tr>
				<td>
					<strong>{ROW.alias}</strong>
				</td>
				<td>
					<!-- BEGIN: error -->
					<strong style="color:red">{ROW.error}</strong>
					<!-- END: error -->
					<!-- BEGIN: ok -->
					<a href="{ROW.url}" title="{ROW.title}"><strong>{ROW.title}</strong></a>
					<!-- END: ok -->
				</td>
				<td>
					<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_delete_u('{ROW.alias}');">{GLANG.delete}</a></span>
				</td>
			</tr>
		</tbody>
		<!-- END: row -->
	</table>
</form>
<!-- END: main -->
