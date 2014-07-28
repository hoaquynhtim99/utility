<!-- BEGIN: main -->
<form class="form-inline" action="" method="post" name="levelnone" id="levelnone">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>{LANG.alias}</th>
					<th>{LANG.content_ftitle}</th>
					<th class="text-center w100">{LANG.feature}</th>
				</tr>
			</thead>
			<tbody>
				<!-- BEGIN: row -->
				<tr>
					<td>
						<strong>{ROW.alias}</strong>
					</td>
					<td>
						<!-- BEGIN: error -->
						<strong class="text-danger">{ROW.error}</strong>
						<!-- END: error -->
						<!-- BEGIN: ok -->
						<a href="{ROW.url}" title="{ROW.title}"><strong>{ROW.title}</strong></a>
						<!-- END: ok -->
					</td>
					<td class="text-center">
						<em class="fa fa-lg fa-trash-o">&nbsp;</em> <a href="javascript:void(0);" onclick="nv_delete_u('{ROW.alias}');">{GLANG.delete}</a>
					</td>
				</tr>
				<!-- END: row -->
			<tbody>
		</table>
	</div>
</form>
<!-- END: main -->