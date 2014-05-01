<!-- BEGIN: main -->
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
<form action="{FORM_ACTION}" method="post">
    <table class="tab1">
		<caption>{TABLE_CAPTION}</caption>
		<tbody>
			<tr>
				<td style="width:150px">
					<strong>{LANG.content_ftitle}</strong>
				</td>
				<td>
					<input type="text" style="width:350px" name="title" value="{DATA.title}"/>
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td>
					<strong>{LANG.alias}</strong>
				</td>
				<td>
					<input type="text" style="width:350px" name="alias" value="{DATA.alias}"/>
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>
					<strong>{LANG.images}</strong>
				</td>
				<td>
					<input readonly="readonly" type="text" style="width:350px" name="images" id="images" value="{DATA.images}"/>
					<input type="button" name="selectimages" id="selectimages" value="{LANG.select}"/>
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>
					<strong>{LANG.content_flogo}</strong>
				</td>
				<td>
					<input readonly="readonly" type="text" style="width:350px" name="logo" id="logo" value="{DATA.logo}"/>
					<input type="button" name="selectlogo" id="selectlogo" value="{LANG.select}"/>
				</td>
			</tr>
		</tbody>
        <tbody class="second">
            <tr>
                <td>
                    <strong>{LANG.content_whoview}</strong>
                </td>
                <td>
                    <select name="who_view">
                        <!-- BEGIN: who_view -->
                        <option value="{who_view.key}"{who_view.selected}>{who_view.title}</option>
                        <!-- END: who_view -->
                    </select>
                </td>
            </tr>
        </tbody>
        <!-- BEGIN: group1 -->
        <tbody>
            <tr>
                <td><strong>{LANG.content_groupview}</strong></td>
                <td>
                    <!-- BEGIN: groups_view -->
                    <input name="groups_view[]" value="{groups_view.key}" type="checkbox"{groups_view.checked} />{groups_view.title}<br />
                    <!-- END: groups_view -->
                </td>
            </tr>
        </tbody>
        <!-- END: group1 -->
		<tbody class="second">
			<tr>
				<td>
					<strong>{LANG.content_iscache}</strong>
				</td>
				<td>
					<input type="checkbox" name="iscache"{DATA.iscache} value="1"/>
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td>
					<strong>{LANG.content_delcache}</strong> <em>{LANG.content_delcache_info}</em>
				</td>
				<td>
					<input type="text" style="width:350px" name="delcache" value="{DATA.delcache}"/>({LANG.minutes})
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td colspan="2">
					<strong>{LANG.introtext}</strong><br /><br />
					<textarea name="introtext" style="width:100%;height:150px" >{DATA.introtext}</textarea>
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td colspan="2">
					<strong>{LANG.description}</strong><br /><br />
					{DATA.description}
				</td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td colspan="2">
					<strong>{LANG.guide}</strong><br /><br />
					{DATA.guide}
				</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2" class="center">
					<input type="submit" name="submit" value="{LANG.submit}" />
				</td>
			</tr>
		</tfoot>
    </table>
</form>
<script type="text/javascript">
$(document).ready(function(){
	$("#selectimages").click( function() {
		nv_open_browse_file( "{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}=upload&popup=1&area=images&path={IMG_DIR}&type=image", "NVImg", "850", "500", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
		return false;
	});
	$("#selectlogo").click( function() {
		nv_open_browse_file( "{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}=upload&popup=1&area=logo&path={IMG_DIR}&type=image", "NVImg", "850", "500", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
		return false;
	});
});
</script>
<!-- END: main -->
