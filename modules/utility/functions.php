<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @copyright 2009
 * @createdate 12/31/2009 0:51
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
	
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `alias`=" . $db->dbescape( $array_op[0] ) . " AND `status`=1";
	$result = $db->sql_query( $sql );
	$numrows = $db->sql_numrows( $result );
	if( empty( $numrows ) ) nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	
	$golbaldata = $db->sql_fetchrow( $result );
	$golbaldata['dir'] = $db->unfixdb( $golbaldata['alias'] );
	
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

?>