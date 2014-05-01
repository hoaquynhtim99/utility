<!-- BEGIN: main -->
<!-- BEGIN: info --><div style="width:98%" class="quote">
    <blockquote class="error"><span>{INFO}.</span></blockquote>
</div><!-- END: info -->
<form action="" method="post" name="levelnone" id="levelnone">
	<table class="tab1">
		<thead>
			<tr>
				<td style="width:50px">{LANG.weight}</td>
				<td>{LANG.content_ftitle}</td>
				<td>{LANG.alias}</td>
				<td>{LANG.viewhit}</td>
				<td>{LANG.downloadhit}</td>
				<td>{LANG.like}</td>
				<td>{LANG.dislike}</td>
				<td>{LANG.error}</td>
				<td style="width:50px">{LANG.status}</td>
				<td style="width:90px" class="center">{LANG.feature}</td>
			</tr>
		</thead>
		<!-- BEGIN: row -->
		<tbody{ROW.class}>
			<tr class="topalign">
				<td>
                    <select name="weight" id="weight{ROW.id}" onchange="nv_change_weight({ROW.id});">
                        <!-- BEGIN: weight -->
                        <option value="{WEIGHT.weight}"{WEIGHT.selected}>{WEIGHT.title}</option>
                        <!-- END: weight -->
                    </select>
				</td>
				<td>
					<strong>{ROW.title}</strong>
				</td>
				<td><strong>{ROW.alias}</strong></td>
				<td><strong>{ROW.viewhit}</strong></td>
				<td><strong>{ROW.downloadhit}</strong></td>
				<td><strong>{ROW.like}</strong></td>
				<td><strong>{ROW.dislike}</strong></td>
				<td><strong>{ROW.error}</strong><!-- BEGIN: delerror --> [<a href="javascript:void(0);" onclick="nv_delete_ierror({ROW.id});">{GLANG.delete}</a>]<!-- END: delerror --></td>
				<td class="center">
					<input name="status" id="change_status{ROW.id}" value="1" type="checkbox"{ROW.status} onclick="nv_change_status({ROW.id})" />
				</td>
				<td class="center">
					<span class="edit_icon"><a href="{ROW.url_edit}">{GLANG.edit}</a></span>
					&nbsp;&nbsp;
					<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_delete_ti({ROW.id});">{GLANG.delete}</a></span>
				</td>
			</tr>
		</tbody>
		<!-- END: row -->
	</table>
</form>
<script type="text/javascript">
	var NV_MOD_LANG_NODEL_FILES = "{LANG.error_delete_dir}";
</script>
<!-- END: main -->
