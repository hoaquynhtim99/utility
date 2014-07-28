<?php

/**
 * @Project NUKEVIET 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 29, 2014, 12:13:24 AM
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

$sql = "SELECT id, alias, title, guide FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE alias=" . $db->quote( $array_op[1] ) . " AND status=1";
$list = nv_db_cache( $sql, 0, $module_name );
if( empty( $list ) ) nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );

$list[0]['form_action'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . '&amp;' . NV_OP_VARIABLE . "=error/" . $list[0]['alias'];

if( empty( $error ) )
{
	if ( $nv_Request->isset_request( 'submit', 'post' ) )
	{
		$name = nv_substr( $nv_Request->get_title( 'name', 'post', '', 1 ), 0, 100);
		$email = nv_substr( $nv_Request->get_title( 'email', 'post', '', 1 ), 0, 100);
		$body = $nv_Request->get_textarea( 'body', '', NV_ALLOWED_HTML_TAGS );
		
		$userid = empty( $user_info['userid'] ) ? 0 : $user_info['userid'];
		
		$sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_error VALUES(
			NULL, 
			" . $list[0]['id'] . ", 
			" . $userid . ",
			" . $db->quote( $name ) . ", 
			" . $db->quote( $email ) . ", 
			" . $db->quote( $client_info['ip'] ) . ", 
			" . $db->quote( $body ) . ", 
			" . NV_CURRENTTIME . ",
			1
		)";
		
		$id_result = $db->insert_id( $sql );
		
		if( $id_result )
		{
			$complete = true;
			$sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET error=1 WHERE alias=" . $db->quote( $array_op[1] );
			$db->query( $sql );
			
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

include NV_ROOTDIR . '/includes/header.php';
echo ( $contents );
include NV_ROOTDIR . '/includes/footer.php';