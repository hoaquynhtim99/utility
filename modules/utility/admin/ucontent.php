<?php

/**
 * @Project NUKEVIET 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 29, 2014, 12:13:24 AM
 */

if ( ! defined( 'NV_IS_DGAT_ADMIN' ) ) die( 'Stop!!!' );

$u = $nv_Request->get_title( 'u', 'get', '', 1 );

if( ! $u or ! file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/admin_data/' . $u . '/config.php' ) or ! file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/admin_data/' . $u . '/data.php' ) )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=utility" );
	die();
}

include( NV_ROOTDIR . '/modules/' . $module_file . '/admin_data/' . $u . '/config.php' );

if( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/admin_data/' . $u . '/language/' . NV_LANG_DATA . '.php' ) )
{
	include( NV_ROOTDIR . '/modules/' . $module_file . '/admin_data/' . $u . '/language/' . NV_LANG_DATA . '.php' );
}
elseif( file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/admin_data/' . $u . '/language/' . $u_config['d_lang'] . '.php' ) )
{
	include( NV_ROOTDIR . '/modules/' . $module_file . '/admin_data/' . $u . '/language/' . $u_config['d_lang'] . '.php' );
}
else
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=utility" );
	die();
}

$page_title = $mod_title = $u_config['title'];
$base_url_rewrite = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=ucontent&amp;u=" . $u;
$base_url_js = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=ucontent&u=" . $u;

include( NV_ROOTDIR . '/modules/' . $module_file . '/admin_data/' . $u . '/data.php' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';