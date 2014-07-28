<?php

/**
 * @Project NUKEVIET 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 29, 2014, 12:13:24 AM
 */

if ( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

define( 'NV_IS_MOD_DGAT', true );

require_once ( NV_ROOTDIR . "/modules/" . $module_file . "/global.functions.php" );

$is_load_data = false;

if( ( $op == "main" ) and isset( $array_op[0] ) )
{
	if ( ! file_exists( NV_ROOTDIR . "/modules/" . $module_file . "/data/" . $array_op[0] . "/data.php" ) )
	{
		nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	}
	
	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE alias = " . $db->quote( $array_op[0] ) . " AND status = 1";
	$result = $db->query( $sql );
	$numrows = $result->rowCount();
	if( empty( $numrows ) ) nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	
	$golbaldata = $result->fetch();
	$golbaldata['dir'] = $golbaldata['alias'];
	
	if ( ! nv_set_allow( $golbaldata['who_view'], $golbaldata['groups_view'] ) )
	{
		nv_info_die( $lang_module['notaloww_title'], $lang_module['notaloww_title'], $lang_module['notaloww_content'] );
	}
	
	$is_load_data = true;
	
	$array_mod_title[] = array(
		'catid' => 0, 
		'title' => $golbaldata['title'], 
		'link' => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $golbaldata['alias'] 
	);
}