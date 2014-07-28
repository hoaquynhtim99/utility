<?php

/**
 * @Project NUKEVIET 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 29, 2014, 12:13:24 AM
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
	
	$sql = "SELECT like, dislike FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id=" . $id . " AND status=1";
	$result = $db->query( $sql );
	$numrows = $result->rowCount();
      
	if( $numrows != 1 )  die( "Error!" );
	list( $like, $dislike ) = $result->fetch( 3 );
	
	if( $rating == 1 )
	{
		$new = $like + 1;
	}
	else
	{
		$new = $dislike + 1;
	}

	$_sql = ( $rating == 1 ) ? "like" : "dislike";
	$sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET " . $_sql . "=" . $new . " WHERE id=" . $id;
	$db->query( $sql );
	
    echo "", $new;
	die();
}

nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );