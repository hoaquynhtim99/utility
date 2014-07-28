<?php

/**
 * @Project NUKEVIET 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 29, 2014, 12:13:24 AM
 */

if ( ! defined( 'NV_IS_DGAT_ADMIN' ) ) die( 'Stop!!!' );

/**
 * getFuncName()
 * 
 * @param mixed $func_file
 * @return
 */
function getFuncName( $func_file )
{
	return preg_replace( "/(.*)\.php$/i", "\\1", $func_file );
}

if( $nv_Request->isset_request( "submit", "get" ) )
{
	$contents = array();
	$contents['status'] = 'ERROR';
	$contents['message'] = 'ERROR';
	$contents['setUpLayout'] = false;
	
	$array = array();
	$array['in_module'] = $nv_Request->get_string( "module_name", "get", "" );
	$array['func_name'] = $nv_Request->get_string( "func_name", "get", "" );
	$array['func_custom_name'] = $nv_Request->get_string( "func_custom_name", "get", "" );
	$array['show_func'] = $nv_Request->get_int( "show_func", "get", 0 );
	$array['in_submenu'] = $nv_Request->get_int( "in_submenu", "get", 0 );
	$array['subweight'] = $nv_Request->get_int( "subweight", "get", 0 );
	$array['otherLang'] = $nv_Request->get_string( "otherLang", "get", "" );
	
	if( $array['show_func'] )
	{
		$contents['setUpLayout'] = true;
	}
	
	$otherLang = array();
	
	if( ! empty( $array['otherLang'] ) )
	{
		$otherLang = array_filter( array_unique( array_map( "trim", explode( ",", $array['otherLang'] ) ) ) );
	}
	
	$otherLang[] = NV_LANG_DATA;
	$array['otherLang'] = $otherLang;
	unset( $otherLang );
	
	if( empty( $array['in_module'] ) )
	{
		$contents['message'] = $u_lang['error_module_empty'];
	}
	elseif( ! isset( $site_mods[$array['in_module']] ) )
	{
		$contents['message'] = $u_lang['error_module_name'];
	}
	elseif( empty( $array['func_name'] ) or ! is_file( NV_ROOTDIR . "/modules/" . $site_mods[$array['in_module']]['module_file'] . "/funcs/" . $array['func_name'] . ".php" ) )
	{
		$contents['message'] = $u_lang['error_func_name'];
	}
	elseif( empty( $array['func_custom_name'] ) )
	{
		$contents['message'] = $u_lang['error_custom_name'];
	}
	else
	{
		$check_insert_error_lang = array();
		$check_insert_success_lang = array();
		
		foreach( $array['otherLang'] as $lang )
		{
			$sql = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_modfuncs ( func_id, func_name, func_custom_name, in_module, show_func, in_submenu, subweight ) VALUES( NULL, " . $db->quote( $array['func_name'] ) . ", " . $db->quote( $array['func_custom_name'] ) . ", " .  $db->quote( $array['in_module'] ). ", " . $array['show_func'] . ", " . $array['in_submenu'] . ", " . $array['subweight'] . " )";
			
			if( ! $db->query( $sql ) )
			{
				$check_insert_error_lang[$lang] = $language_array[$lang]['name'];
			}
			else
			{
				$check_insert_success_lang[$lang] = $language_array[$lang]['name'];
			}
		}
		
		if( empty( $check_insert_error_lang ) )
		{
			$contents['status'] = "SUCCESS";
		}
		else
		{
			$contents['message'] = sprintf( $u_lang['error_insert_lang'], implode( ", ", $check_insert_error_lang ) );
			
			foreach( $check_insert_success_lang as $lang => $langName )
			{
				$db->query( "DELETE FROM " . $db_config['prefix'] . "_" . $lang . "_modfuncs WHERE func_name=" . $db->quote( $array['func_name'] ) . " AND in_module=" . $db->quote( $array['in_module'] ) );
			}
		}
	}
	
	include NV_ROOTDIR . '/includes/header.php';
	echo json_encode( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
	die();
}

if( $nv_Request->isset_request( "load_func", "get" ) )
{
	$contents = array();
	$contents['status'] = 'ERROR';
	$contents['message'] = '';
	
	$mod_name = $nv_Request->get_string( "module_name", "get", "" );
	
	if( empty( $mod_name ) )
	{
		$contents['message'] = $u_lang['error_module_empty'];
	}
	elseif( ! isset( $site_mods[$mod_name] ) )
	{
		$contents['message'] = $u_lang['error_module_name'];
	}
	else
	{
		$list_data_funcs = array();
		$sql = "SELECT func_name FROM " . NV_MODFUNCS_TABLE . " WHERE in_module=" . $db->quote( $mod_name );
		$result = $db->query( $sql );
		while( $row = $result->fetch() )
		{
			$row['func_name'] = $row['func_name'];
			$list_data_funcs[$row['func_name']] = $row['func_name'];
		}
		
		$list_file_funcs = nv_scandir( NV_ROOTDIR . "/modules/" . $site_mods[$mod_name]['module_file'] . "/funcs", $global_config['check_op_file'] );
		$list_file_funcs = array_map( "getFuncName", $list_file_funcs );
		
		$new_funcs = array_diff( $list_file_funcs, $list_data_funcs );
		
		if( empty( $new_funcs ) )
		{
			$contents['message'] = $u_lang['error_no_new_func'];
		}
		else
		{
			$contents['new_funcs'] = array();
			foreach( $new_funcs as $func )
			{
				$contents['new_funcs'][$func] = ucfirst( $func );
			}
			
			// Ngon ngu khac
			$contents['isOtherLang'] = false;
			$sql = "SELECT lang FROM " . $db_config['prefix'] . "_setup_language WHERE setup=1 AND lang!=" . $db->quote( NV_LANG_DATA );
			$result = $db->query( $sql );
			$array_other_lang = array();
			while( $row = $result->fetch() )
			{
				$row['lang'] = $row['lang'];
				if( $db->query( "SELECT * FROM " . $db_config['prefix'] . "_" . $row['lang'] . "_modules WHERE title=" . $db->quote( $mod_name ) )->rowCount() )
				{
					$array_other_lang[$row['lang']] = $language_array[$row['lang']]['name'];
				}
			}
			if( ! empty( $array_other_lang ) )
			{
				$contents['isOtherLang'] = true;
				$contents['otherLang'] = $array_other_lang;
			}
			
			$contents['status'] = "SUCCESS";
		}
	}
	
	include NV_ROOTDIR . '/includes/header.php';
	echo json_encode( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
	die();
}

// Danh sach cac module
$module_option = "<option value=\"\">------</option>";
foreach( $site_mods as $name => $module )
{
	$module_option .= "<option value=\"" . $name . "\">" . $name . " - " . $module['custom_title'] . "</option>";
}

$contents = "
<div id=\"u-data\">
<div class=\"alert alert-warning\">" . $u_lang['info'] . "</div>
<table class=\"table table-striped table-bordered table-hover\">
	<col width=\"150\"/>
	<col width=\"200\"/>
	<tbody>
		<tr>
			<td>" . $u_lang['select_module'] . "</td>
			<td><select class=\"form-control\" name=\"module_name\"/>" . $module_option . "<select></td>
			<td></td>
		</tr>
		<tr>
			<td>" . $u_lang['func_name'] . "</td>
			<td id=\"func_name\"></td>
			<td class=\"info-explain\" id=\"func_name_explain\"></td>
		</tr>
		<tr>
			<td>" . $u_lang['func_custom_name'] . "</td>
			<td id=\"func_custom_name\"></td>
			<td class=\"info-explain\" id=\"func_custom_name_explain\"></td>
		</tr>
		<tr>
			<td>" . $u_lang['show_func'] . "</td>
			<td id=\"show_func\"></td>
			<td class=\"info-explain\" id=\"show_func_explain\"></td>
		</tr>
		<tr>
			<td>" . $u_lang['in_submenu'] . "</td>
			<td id=\"in_submenu\"></td>
			<td class=\"info-explain\" id=\"in_submenu_explain\"></td>
		</tr>
		<tr>
			<td>" . $u_lang['subweight'] . "</td>
			<td id=\"subweight\"></td>
			<td class=\"info-explain\" id=\"subweight_explain\"></td>
		</tr>
		<tr>
			<td>" . $u_lang['otherLang'] . "</td>
			<td id=\"otherLang\"></td>
			<td class=\"info-explain\" id=\"otherLang_explain\"></td>
		</tr>
		<tr>
			<td></td>
			<td><input class=\"btn btn-primary\" type=\"button\" name=\"submit\" value=\"" . $u_lang['submit'] . "\"/></td>
			<td></td>
		</tr>
	</tbody>
</table>
<div id=\"result-data\"></div>
<script type=\"text/javascript\">
var setOtherLang = false;
\$(function(){
	\$('[name=\"module_name\"]').change(function(){
		\$('#func_name,#func_custom_name,#show_func,#in_submenu,#subweight,#otherLang').html('<img src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\" alt=\"\"/>');
		\$('.info-explain,#result-data').html('');
		\$.getJSON( '" . $base_url_js . "&load_func=1&module_name=' + \$(this).val(), function(data){
			\$('#func_name,#func_custom_name,#show_func,#in_submenu,#subweight,#otherLang').html('');
			if(data.status=='ERROR'){
				\$('#func_name_explain').html('<div class=\"alert alert-danger\">' + data.message + '</div>');
			}else{
				var option_func_name = '<option rel=\"\" value=\"\">--------</option>';
				\$.each(data.new_funcs,function(k,v){ option_func_name += '<option rel=\"' + v + '\" value=\"' + k + '\">' + v + '</option>'; });
				
				\$('#func_custom_name').html('<input type=\"text\" class=\"txt-full\" name=\"func_custom_name\" value=\"\"/>');
				
				\$('#func_name').html('<select onchange=\"\$(\'[name=func_custom_name]\').val(\$(this).find(\'option:selected\').attr(\'rel\'));\" name=\"func_name\">' + option_func_name + '</select>');
				
				\$('#show_func').html('<input type=\"checkbox\" class=\"txt-full\" name=\"show_func\" value=\"1\"/>');
				\$('#show_func_explain').html('" . $u_lang['show_func_explain'] . "');
				
				\$('#in_submenu').html('<input type=\"text\" class=\"txt-full\" name=\"in_submenu\" value=\"0\"/>');
				\$('#in_submenu_explain').html('" . $u_lang['in_submenu_explain'] . "');
				
				\$('#subweight').html('<input type=\"text\" class=\"txt-full\" name=\"subweight\" value=\"0\"/>');
				\$('#subweight_explain').html('" . $u_lang['subweight_explain'] . "');
				
				if( data.isOtherLang ){
					setOtherLang = true;
					$.each( data.otherLang, function(k,v){
						\$('#otherLang').append('<label><input type=\"checkbox\" class=\"otherLang\" value=\"' + k + '\"/> ' + v + '</label><br />');
					});
				}else{
					setOtherLang = false;
				}
			}
		});
	});
	\$('[name=\"submit\"]').click(function(){
		\$(this).attr('disabled','disabled');
		\$('#result-data').html('<center><img src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\" alt=\"\"/></center>');
		
		var module_name = \$('[name=\"module_name\"]').val();
		var func_name = \$('[name=\"func_name\"]').val();
		var func_custom_name = encodeURIComponent( \$('[name=\"func_custom_name\"]').val() );
		var show_func = \$('[name=\"show_func\"]').attr('checked') ? 1 : 0;
		var in_submenu = \$('[name=\"in_submenu\"]').val();
		var subweight = \$('[name=\"subweight\"]').val();
		var otherLang = new Array();
		if( setOtherLang ){
			\$.each(\$('.otherLang'),function(){
				if( \$(this).attr('checked') ) otherLang.push( \$(this).val() );
			});
		}
		
		\$.getJSON( '" . $base_url_js . "&submit=1&module_name=' + module_name + '&func_name=' + func_name + '&func_custom_name=' + func_custom_name + '&show_func=' + show_func + '&in_submenu=' + in_submenu + '&subweight=' + subweight + '&otherLang=' + otherLang, function(data){
			\$('[name=\"submit\"]').removeAttr('disabled');
			if( data.status == 'ERROR' ){
				\$('#result-data').html('<div class=\"alert alert-danger\">' + data.message + '</div>');
			}else if(data.setUpLayout){
				\$('#result-data').html('<div class=\"alert alert-success\"><p>" . $u_lang['submit_ok'] . "</p><p><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=themes&amp;" . NV_OP_VARIABLE . "=setuplayout\">" . $u_lang['submit_ok_link'] . "</a></p></div>');
			}else{
				\$('#result-data').html('<div class=\"alert alert-success\"><p>" . $u_lang['submit_ok_clean_cache'] . "</p><p><a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=webtools&amp;" . NV_OP_VARIABLE . "=clearsystem\">" . $u_lang['submit_ok_clean_cache_link'] . "</a></p></div>');
			}
		});
	});
});
</script>
</div>";

?>