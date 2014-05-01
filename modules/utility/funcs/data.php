<?php

/**
 * @Project NUKEVIET 3.1
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate 07-03-2011 20:15
 */

if ( ! defined( 'NV_IS_MOD_DGAT' ) ) die( 'Stop!!!' );

if ( $nv_Request->isset_request( 'rating', 'post' ) )
{	
	if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
	
    $rating = $nv_Request->get_int( 'rating', 'post', 0 );
    $id = $nv_Request->get_int( 'id', 'post', 0 );
	if( empty( $id ) or ! in_array( $rating, array( 1, 2 ) ) ) die( "Error!" );
	
	$dgraed = $nv_Request->get_string( 'dgraed', 'session', '' );
	$dgraed = ! empty( $dgraed ) ? unserialize( $dgraed ) : array();
                
	if ( in_array( $id, $dgraed ) )  die( "Error!" );
	
	$dgraed[] = $id;
	$dgraed = serialize( $dgraed );
	$nv_Request->set_Session( 'dgraed', $dgraed );
	
	$sql = "SELECT `like`, `dislike` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`=" . $id . " AND `status`=1";
	$result = $db->sql_query( $sql );
	$numrows = $db->sql_numrows( $result );
      
	if( $numrows != 1 )  die( "Error!" );
	list( $like, $dislike ) = $db->sql_fetchrow( $result );
	
	if( $rating == 1 )
	{
		$new = $like + 1;
	}
	else
	{
		$new = $dislike + 1;
	}

	$_sql = ( $rating == 1 ) ? "like" : "dislike";
	$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `" . $_sql . "`=" . $new . " WHERE `id`=" . $id;
	$db->sql_query( $sql );
	
    echo "", $new;
	die();
}

nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );

?>