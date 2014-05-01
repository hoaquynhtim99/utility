<?php

/**
 * @Project NUKEVIET 3.1
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate 24-06-2011 10:35
 */

if ( ! defined( 'NV_IS_DGAT_ADMIN' ) ) die( 'Stop!!!' );

$u = filter_text_input( 'u', 'get', '', 1 );

if( ! $u or ! file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/admin_data/' . $u . '/config.php' ) or ! file_exists( NV_ROOTDIR . '/modules/' . $module_file . '/admin_data/' . $u . '/data.php' ) )
{
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=utility" );
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
	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=utility" );
	die();
}

$page_title = $mod_title = $u_config['title'];
$base_url_rewrite = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=ucontent&amp;u=" . $u;
$base_url_js = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=ucontent&u=" . $u;

include( NV_ROOTDIR . '/modules/' . $module_file . '/admin_data/' . $u . '/data.php' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>