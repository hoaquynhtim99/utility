<?php

/**
 * @Project NUKEVIET 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 29, 2014, 12:13:24 AM
 */

if ( ! defined( 'NV_IS_MOD_DGAT' ) ) die( 'Stop!!!' );

if( $is_load_data )
{
	$page_title = $mod_title = $golbaldata['title'];
	$key_words = $module_info['keywords'];
	$description = $golbaldata['introtext'];
	
	$golbaldata['url_guide'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . '&amp;' . NV_OP_VARIABLE . "=guidelines/" . $golbaldata['alias'];
	
	// Goi file css
	if( is_file( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/images/" . $module_file . "/" . $array_op[0] . "/style.css" ) )
	{
		$my_head .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/" . $array_op[0] . "/style.css\" />\n";
	}
	
	// Goi file js
	if( is_file( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/images/" . $module_file . "/" . $array_op[0] . "/script.js" ) )
	{
		$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/" . $array_op[0] . "/script.js\"></script>\n";
	}
	
	// Goi file lang
	if( is_file( NV_ROOTDIR . "/modules/" . $module_file . "/data/" . $array_op[0] . "/language/" . NV_LANG_DATA . ".php" ) )
	{
		require( NV_ROOTDIR . "/modules/" . $module_file . "/data/" . $array_op[0] . "/language/" . NV_LANG_DATA . ".php" );
	}
	elseif( is_file( NV_ROOTDIR . "/modules/" . $module_file . "/data/" . $array_op[0] . "/language/vi.php" ) )
	{
		require( NV_ROOTDIR . "/modules/" . $module_file . "/data/" . $array_op[0] . "/language/vi.php" );
	}
	
	// Goi file theme
	if( is_file( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file . "/" . $array_op[0] . "/theme.php" ) )
	{
		require( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file . "/" . $array_op[0] . "/theme.php" );
	}
	elseif( is_file( NV_ROOTDIR . "/modules/" . $module_file . "/data/" . $array_op[0] . "/theme.php" ) )
	{
		require( NV_ROOTDIR . "/modules/" . $module_file . "/data/" . $array_op[0] . "/theme.php" );
	}
	
	require( NV_ROOTDIR . "/modules/" . $module_file . "/data/" . $array_op[0] . "/data.php" );
	exit();
}

$page_title = $mod_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$sql = "SELECT alias, title, images, introtext FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE status=1 ORDER BY weight ASC";
$list = nv_db_cache( $sql, 'id', $module_name );

$array = array();

foreach( $list as $row )
{
	$array[] = array(
		"title" => $row['title'],
		"alias" => $row['alias'],
		"images" => $row['images'],
		"introtext" => $row['introtext'],
		"url" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . '&amp;' . NV_OP_VARIABLE . "=" . $row['alias']
	);
}

$contents = nv_main_theme( $array );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';