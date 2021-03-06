<!-- BEGIN: main -->
<form class="form-inline" action="{FORM_ACTION}" method="post" name="levelnone" id="levelnone">
	<table class="table table-striped table-bordered table-hover">
		<caption>{LANG.standard_list}</caption>
		<thead>
			<tr>
				<td style="width:50px">{LANG.weight}</td>
				<td>{LANG.title}</td>
				<td>{LANG.description}</td>
				<td style="width:50px">{LANG.status}</td>
				<td style="width:90px" class="text-center">{LANG.feature}</td>
			</tr>
		</thead>
		<tbody>
		<!-- BEGIN: row -->
			<tr>
				<td>
                    <select class="form-control" name="weight" id="weight{ROW.id}" onchange="nv_change_standard_weight({ROW.id});">
                        <!-- BEGIN: weight -->
                        <option value="{WEIGHT.weight}"{WEIGHT.selected}>{WEIGHT.title}</option>
                        <!-- END: weight -->
                    </select>
				</td>
				<td>{ROW.title}</td>
				<td>{ROW.description}</td>
				<td class="text-center"><input name="status" id="change_status{ROW.id}" value="1" type="checkbox"{ROW.status} onclick="nv_change_standard_status({ROW.id})" /></td>
				<td class="text-center">
					<span class="edit_icon"><a href="{ROW.url_edit}">{GLANG.edit}</a></span>
					&nbsp;&nbsp;
					<span class="delete_icon"><a href="javascript:void(0);" onclick="nv_delete_standard({ROW.id});">{GLANG.delete}</a></span>
				</td>
			</tr>
		<!-- END: row -->
		<tbody>
	</table>
</form>
<!-- BEGIN: error -->
<div style="width: 98%;" class="quote">
    <blockquote class="error">
        <p>
            <span>{ERROR}</span>
        </p>
    </blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form class="form-inline" action="{FORM_ACTION}" method="post" name="levelform" id="levelform">
	<a name="addeditarea"></a>
	<table class="table table-striped table-bordered table-hover">
		<caption>{TABLE_CAPTION}</caption>
		<tbody>
			<tr>
				<td style="width:100px">{LANG.title}</td>
				<td class="text-center" style="width:10px"><span class="requie">*</span></td>
				<td><input class="form-control" type="text" name="title" value="{DATA.title}" style="width:350px"/></td>
			</tr>
			<tr>
				<td>{LANG.description}</td>
				<td>&nbsp;</td>
				<td><input class="form-control" type="text" name="description" value="{DATA.description}" style="width:550px"/></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="3"><input class="btn btn-primary" type="submit" name="submit" value="{LANG.submit}"/></td>
			</tr>
		</tfoot>
	</table>
</form>
<!-- END: main -->