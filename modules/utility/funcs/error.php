<?php

/**
 * @Project NUKEVIET 3.1
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate 07-03-2011 20:15
 */

if ( ! defined( 'NV_IS_MOD_DGAT' ) ) die( 'Stop!!!' );

if( empty( $array_op[1] ) ) nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );

$error = "";
$complete = false;

$dgerrored = $nv_Request->get_string( 'dgerrored', 'session', '' );
$dgerrored = ! empty( $dgerrored ) ? unserialize( $dgerrored ) : array();
if( in_array( $array_op[1], $dgerrored ) )
{
	$error = $lang_module['error_submited'];
}

$sql = "SELECT `id`, `alias`, `title`, `guide` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `alias`=" . $db->dbescape( $array_op[1] ) . " AND `status`=1";
$list = nv_db_cache( $sql, 0, $module_name );
if( empty( $list ) ) nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );

$list[0]['form_action'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . '&amp;' . NV_OP_VARIABLE . "=error/" . $list[0]['alias'];

if( empty( $error ) )
{
	if ( $nv_Request->isset_request( 'submit', 'post' ) )
	{
		$name = filter_text_input( 'name', 'post', '', 1, 100 );
		$email = filter_text_input( 'email', 'post', '', 1, 100 );
		$body = filter_text_textarea( 'body', '', NV_ALLOWED_HTML_TAGS );
		
		$userid = empty( $user_info['userid'] ) ? 0 : $user_info['userid'];
		
		$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "_error` VALUES(
			NULL, 
			" . $list[0]['id'] . ", 
			" . $userid . ",
			" . $db->dbescape( $name ) . ", 
			" . $db->dbescape( $email ) . ", 
			" . $db->dbescape( $client_info['ip'] ) . ", 
			" . $db->dbescape( $body ) . ", 
			" . NV_CURRENTTIME . ",
			1
		)";
		
		$id_result = $db->sql_query_insert_id( $sql );
		
		if( $id_result )
		{
			$complete = true;
			$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `error`=1 WHERE `alias`=" . $db->dbescape( $array_op[1] );
			$db->sql_query( $sql );
			$db->sql_freeresult();
			
			$dgerrored[] = $array_op[1];
			$dgerrored = serialize( $dgerrored );
			$nv_Request->set_Session( 'dgerrored', $dgerrored );
		}
		else
		{
			$error = $lang_module['error_serror'];
		}
	}
}

$contents = nv_error_theme( $list[0], $error, $complete );

include ( NV_ROOTDIR . "/includes/header.php" );
echo ( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>