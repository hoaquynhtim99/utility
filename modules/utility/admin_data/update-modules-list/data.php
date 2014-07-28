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

if( $nv_Request->isset_request( 'action', 'get' ) )
{
	$array = array();
	$sql = "SELECT * FROM " . $db_config['prefix'] . "_setup_modules GROUP BY module_file ORDER BY addtime ASC";
	$result = $db->query( $sql );
	
	while( $row = $result->fetch() )
	{
		$row['module_file'] = $row['module_file'];
	
		$array[$row['module_file']] = $row;
	}
	
	foreach( $array as $row )
	{
		$version_file = NV_ROOTDIR . "/modules/" . $row['module_file'] . "/version.php";
		
		if( file_exists( $version_file ) )
		{
			$module_version = array();
			require_once( $version_file );
			if( isset( $module_version['is_sysmod'] ) and isset( $module_version['virtual'] ) and ! empty( $module_version['version'] ) and ! empty( $module_version['date'] ) and ! empty( $module_version['author'] ) )
			{
				$date_ver = intval( strtotime( $module_version['date'] ) );
				if( $date_ver == 0 ) $date_ver = NV_CURRENTTIME;
				
				$mod_version = $module_version['version'] . " " . $date_ver;
				$mod_note = $module_version['note'];
				$mod_author = $module_version['author'];
				$is_sysmod = ( int ) $module_version['is_sysmod'];
				$virtual = ( int ) $module_version['virtual'];
				
				$db->query( "UPDATE " . $db_config['prefix'] . "_setup_modules SET 
					is_sysmod=" . $is_sysmod . ", 
					virtual=" . $virtual . ", 
					mod_version=" . $db->quote( $mod_version ) . ", 
					addtime=" . NV_CURRENTTIME . ", 
					author=" . $db->quote( $mod_author ) . ", 
					note=" . $db->quote( $mod_note ) . " 
				WHERE module_file=" . $db->quote( $row['module_file'] ) );
			}
		}
	}
	
	nv_del_moduleCache( 'modules' );
	die( 'OK' );
}

$c .= '
<div class="alert alert-info">' . $u_lang['info'] . '</div>
<table class="table table-striped table-bordered table-hover">
	<tbody>
		<tr>
			<td class="text-center">
				<input type="button" name="action" value="' . $u_lang['action'] . '" class="btn btn-primary"/>
				<script type="text/javascript">
				$(function(){
					$("[name=action]").click(function(){
						$("[name=action]").attr("disabled", "disabled");
						$.get("' . $base_url_js . '&action=1", function(){
							$("[name=action]").removeAttr("disabled");
							alert( "' . $u_lang['complete'] . '" );
						});
					});
				});
				</script>
			</td>
		</tr>
	</tbody>
</table>
';

$contents = $c;