<?php

/**
 * @Project NUKEVIET 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 29, 2014, 12:13:24 AM
 */

if ( ! defined( 'NV_IS_DGAT_ADMIN' ) ) die( 'Stop!!!' );

$c = '';

/**
 * nv_list_all_file()
 * 
 * @param string $dir
 * @param string $base_dir
 * @return
 */
function nv_list_all_file( $dir = '', $base_dir = '' )
{
	if( empty( $dir ) ) return array();
	
	$file_list = array();
	
	if( is_dir( $dir ) )
	{
		$array_filedir = scandir( $dir );
		
		foreach( $array_filedir as $v )
		{
			if( $v == '.' or $v == '..' ) continue;
			
			if( is_dir( $dir . '/' . $v ) )
			{
				foreach( nv_list_all_file( $dir . '/' . $v, $base_dir . '/' . $v ) as $file )
				{
					$file_list[] = $file;
				}
			}
			else
			{
				if( ! preg_match( "/\.php$/i", $v ) ) continue;
				$file_list[] = preg_replace( '/^\//', '', $base_dir . '/' . $v );
			}
		}
	}
	
	return $file_list;
}

if( $nv_Request->isset_request( 'module', 'get' ) )
{
	$module = $nv_Request->get_string( 'module', 'get', '' );
	
	$project_name = $nv_Request->get_string( 'project_name', 'get', '' );
	$project_version = $nv_Request->get_string( 'project_version', 'get', '' );
	$project_author = $nv_Request->get_string( 'project_author', 'get', '' );
	$project_email = $nv_Request->get_string( 'project_email', 'get', '' );
	$project_year = $nv_Request->get_string( 'project_year', 'get', '' );
	$project_copyright = $nv_Request->get_string( 'project_copyright', 'get', '' );
	$project_createdate = $nv_Request->get_string( 'project_createdate', 'get', '' );
	
	$file_head_1 = "\n\n/**\n * @Project " . $project_name . " " . $project_version . "\n * @Author " . $project_author . " (" . $project_email . ")\n * @Copyright (C) " . $project_year . " " . $project_copyright . ". All rights reserved\n * @License GNU/GPL version 2 or any later version\n";
	$file_head_2 = " * @Createdate " . $project_createdate . "\n */\n\n";
	
	$module = $module ? array_filter(array_unique(array_map( 'trim', explode( ",", $module )))) : array();

	if( empty( $module ) ) die( $u_lang['error_choose'] );
	
	$result = $u_lang['result'] . "<br />";
	foreach( $module as $_module )
	{
		$all_dir = array();
		$all_dir[] = NV_ROOTDIR . "/modules/" . $_module;
	
		$all_theme = nv_scandir( NV_ROOTDIR . "/themes", $global_config['check_theme'] );
		foreach( $all_theme as $theme )
		{
			if( is_dir( NV_ROOTDIR . "/themes/" . $theme . "/modules/" . $_module ) )
			{
				$all_dir[] = NV_ROOTDIR . "/themes/" . $theme . "/modules/" . $_module;
			}
		}
		$all_mobile_theme = nv_scandir( NV_ROOTDIR . "/themes", $global_config['check_theme_mobile'] );
		foreach( $all_mobile_theme as $theme )
		{
			if( is_dir( NV_ROOTDIR . "/themes/" . $theme . "/modules/" . $_module ) )
			{
				$all_dir[] = NV_ROOTDIR . "/themes/" . $theme . "/modules/" . $_module;
			}
		}
		
		$all_file = array();
		foreach( $all_dir as $dir )
		{
			$files = nv_list_all_file( $dir, $dir );
			foreach( $files as $file )
			{
				$all_file[] = $file;
			}
		}
		
		foreach( $all_file as $file )
		{
			if( preg_match( "/\/language\//", $file ) )
			{
				$lang = "Unknow";
				if( preg_match( "/\_([a-z0-9]{2})\.php$/i", $file, $m ) )
				{
					if( isset( $language_array[$m[1]] ) ) $lang = $language_array[$m[1]]['name'];
				}
				elseif( preg_match( "/\/([a-z0-9]{2})\.php$/i", $file, $m ) )
				{
					if( isset( $language_array[$m[1]] ) ) $lang = $language_array[$m[1]]['name'];
				}
				
				$file_head_3 = " * @Language " . $lang . "\n";
			}
			else
			{
				$file_head_3 = "";
			}
		
			$file_content = file_get_contents( $file );
			$file_content = preg_replace( "/^(.*?)\<\?php(.*?)\*\/(.*?)if/is", "<?php" . $file_head_1 . $file_head_3 . $file_head_2 . "if", $file_content );
			
			if( file_put_contents( $file, $file_content, LOCK_EX ) === false )
			{
				die( $u_lang['error'] . $file );
			}
			else
			{
				$result .= $file . "<br />";
			}
		}
	}
	
	die( $u_lang['ok'] . "<br />" . $result );
}

$c .= "<table class=\"table table-striped table-bordered table-hover\"><tbody><tr><td>" . $u_lang['info'] . "</td></tr></tbody></table>\n";
$c .= "<p><br /><strong>" . $u_lang['step1'] . "</strong></p>";
$c .= "<table class=\"table table-striped table-bordered table-hover\">\n";
$c .= "<col width=\"160\"/>\n";
$c .= "<tbody>\n";
$c .= "<tr>\n";
$c .= "<td>" . $u_lang['project_name'] . "</td>\n";
$c .= "<td><input type=\"text\" id=\"project_name\" class=\"txt-half\" value=\"NUKEVIET\"/></td>\n";
$c .= "</tr>\n";
$c .= "<tr>\n";
$c .= "<td>" . $u_lang['project_version'] . "</td>\n";
$c .= "<td><input type=\"text\" id=\"project_version\" class=\"txt-half\" value=\"4.x\"/></td>\n";
$c .= "</tr>\n";
$c .= "<tr>\n";
$c .= "<td>" . $u_lang['project_author'] . "</td>\n";
$c .= "<td><input type=\"text\" id=\"project_author\" class=\"txt-half\" value=\"PHAN TAN DUNG\"/></td>\n";
$c .= "</tr>\n";
$c .= "<tr>\n";
$c .= "<td>" . $u_lang['project_email'] . "</td>\n";
$c .= "<td><input type=\"text\" id=\"project_email\" class=\"txt-half\" value=\"phantandung92@gmail.com\"/></td>\n";
$c .= "</tr>\n";
$c .= "<tr>\n";
$c .= "<td>" . $u_lang['project_year'] . "</td>\n";
$c .= "<td><input type=\"text\" id=\"project_year\" class=\"txt-half\" value=\"" . date( 'Y', NV_CURRENTTIME ) . "\"/></td>\n";
$c .= "</tr>\n";
$c .= "<tr>\n";
$c .= "<td>" . $u_lang['project_copyright'] . "</td>\n";
$c .= "<td><input type=\"text\" id=\"project_copyright\" class=\"txt-half\" value=\"PHAN TAN DUNG\"/></td>\n";
$c .= "</tr>\n";
$c .= "<tr>\n";
$c .= "<td>" . $u_lang['project_createdate'] . "</td>\n";
$c .= "<td><input type=\"text\" id=\"project_createdate\" class=\"txt-half\" value=\"" . date( 'M d, Y, h:i:s A', NV_CURRENTTIME ) . "\"/></td>\n";
$c .= "</tr>\n";
$c .= "</tbody>\n";
$c .= "</table>\n";

$c .= "<p><br /><strong>" . $u_lang['step2'] . "</strong></p>";
$c .= "<table class=\"table table-striped table-bordered table-hover\">\n";

// Lay danh sach module
$array_module = nv_scandir( NV_ROOTDIR . "/modules", $global_config['check_module'] );
$c .= "<tbody>\n";
$c .= "<tr><td>\n";

foreach( $array_module as $_module )
{
	if( $_module != 'utility' ) $c .= "<label style=\"float:left;width:20%\"><input class=\"mFileName\" type=\"checkbox\" value=\"" . $_module . "\"/> " . $_module . "</label>\n";
}

$c .= "</td></tr>\n";
$c .= "</tbody>\n";
$c .= "</table>\n";

$c .= "<p><br /><strong><a id=\"btSubmit\" href=\"javascript:void(0);\">" . $u_lang['step3'] . "</a></strong></p>";

$c .= "<table class=\"table table-striped table-bordered table-hover\"><tbody><tr><td id=\"messageArea\"></td></tr></tbody></table>\n";

$c .= "
<script type=\"text/javascript\">\n
\$(function(){\n
	\$('#btSubmit').click(function(){
		var module = new Array();
		$.each(\$('.mFileName:checked'),function(){
			module.push( \$(this).val() );
		});
		if( ! module.length ){
			alert( '" . $u_lang['error_choose'] . "' );
			return false;
		}
		$('#messageArea').html('<img src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\"/> " . $u_lang['waiting'] . "');
		$.get( '" . $base_url_js . "&module=' + module + '&project_name=' + encodeURIComponent( \$('#project_name').val() ) + '&project_version=' + encodeURIComponent( \$('#project_version').val() ) + '&project_author=' + encodeURIComponent( \$('#project_author').val() ) + '&project_email=' + encodeURIComponent( \$('#project_email').val() ) + '&project_year=' + encodeURIComponent( \$('#project_year').val() ) + '&project_copyright=' + encodeURIComponent( \$('#project_copyright').val() ) + '&project_createdate=' + encodeURIComponent( \$('#project_createdate').val() ), function(a){
			$('#messageArea').html(a);
		});
	});
});\n
</script>\n
";

$contents = $c;