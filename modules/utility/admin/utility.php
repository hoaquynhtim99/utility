<?php

/**
 * @Project NUKEVIET 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 29, 2014, 12:13:24 AM
 */

if ( ! defined( 'NV_IS_DGAT_ADMIN' ) ) die( 'Stop!!!' );

// Xoa tien ich
if ( $nv_Request->isset_request( 'del', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
    
    $id = $nv_Request->get_title( 'id', 'post', '', 1 );
    
    if ( empty( $id ) ) die( 'NO' );
    
	$status = nv_deletefile( NV_ROOTDIR . '/modules/' . $module_file . '/admin_data/' . $id, true );

	if( $status[0] == 1 )
	{
		nv_insert_logs( NV_LANG_DATA, $module_name, "Delete U", $id, $admin_info['userid'] );
		die( "OK|OK" );	
	}

	die( "NO|" . $status[1] );
}

$array = array();

$xtpl = new XTemplate( "utility.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$list_u = nv_scandir ( NV_ROOTDIR . '/modules/' . $module_file . '/admin_data', '/^([a-z0-9\-\_]+)$/i', true );

foreach( $list_u as $u )
{
	$row = array( 'alias' => $u );
	unset( $u_config );
	if( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/admin_data/' . $u . '/config.php' ) )
	{
		include( NV_ROOTDIR . '/modules/' . $module_file . '/admin_data/' . $u . '/config.php' );
	}
	
	$row['error'] = '';
	$row['title'] = '';

	if( ! isset( $u_config ) )
	{	
		$row['error'] = $lang_module['utility_error_config'];
	}
	else
	{
		$row['title'] = $u_config['title'];
	}
	
	$row['url'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=ucontent&amp;u=" . $u;
 	
	$xtpl->assign( 'ROW', $row );
	
	if( $row['error'] )
	{
		$xtpl->parse( 'main.row.error' );
	}
	else
	{
		$xtpl->parse( 'main.row.ok' );
	}
	
	$xtpl->parse( 'main.row' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';