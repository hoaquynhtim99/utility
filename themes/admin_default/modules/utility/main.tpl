<!-- BEGIN: main -->
<!-- BEGIN: info -->
<div class="alert alert-info">
	{INFO}.
</div>
<!-- END: info -->
<form class="form-inline" action="" method="post" name="levelnone" id="levelnone">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th style="width:50px">{LANG.weight}</th>
					<th>{LANG.content_ftitle}</th>
					<th>{LANG.alias}</th>
					<th>{LANG.viewhit}</th>
					<th>{LANG.downloadhit}</th>
					<th>{LANG.like}</th>
					<th>{LANG.dislike}</th>
					<th>{LANG.error}</th>
					<th style="width:50px">{LANG.status}</th>
					<th style="width:90px" class="text-center">{LANG.feature}</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: row -->
				<tr class="topalign">
					<td>
	                    <select class="form-control" name="weight" id="weight{ROW.id}" onchange="nv_change_weight({ROW.id});">
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
					<td class="text-center">
						<input name="status" id="change_status{ROW.id}" value="1" type="checkbox"{ROW.status} onclick="nv_change_status({ROW.id})" />
					</td>
					<td class="text-center">
						<span class="edit_icon"><a href="{ROW.url_edit}">{GLANG.edit}</a></span>
						&nbsp;&nbsp;
						<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_delete_ti({ROW.id});">{GLANG.delete}</a></span>
					</td>
				</tr>
				<!-- END: row -->
			<tbody>
		</table>
	</div>
</form>
<script type="text/javascript">
	var NV_MOD_LANG_NODEL_FILES = "{LANG.error_delete_dir}";
</script>
<!-- END: main -->