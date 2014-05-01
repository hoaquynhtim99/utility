<?php

/**
 * @Project NUKEVIET 3.1
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate 07-03-2011 20:15
 */

if ( ! defined( 'NV_IS_MOD_DGAT' ) ) die( 'Stop!!!' );

function nv_main_theme( $array )
{
    global $lang_global, $lang_module, $module_file, $module_info;

    $xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'GLANG', $lang_global );
	
	$item_height = 210;
	
	// Get content height
	$num_item = sizeof( $array );
	$num_row = round( $num_item / 3 );
	$content_height = $num_row * $item_height;
	
	$xtpl->assign( 'HEIGHT', $content_height );
	
	foreach( $array as $row )
	{
		$row['images'] = empty( $row['images'] ) ? NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/default.png" : NV_BASE_SITEURL . NV_UPLOADS_DIR . $row['images'];
		$xtpl->assign( 'ROW', $row );
		$xtpl->parse( 'main.row' );
	}	

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

function nv_guidelines_theme( $data )
{
    global $lang_global, $lang_module, $module_file, $module_info, $module_name;

    $xtpl = new XTemplate( "guide.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'GLANG', $lang_global );
	
	$data['url'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . '&amp;' . NV_OP_VARIABLE . "=" . $data['alias'];
	
    $xtpl->assign( 'DATA', $data );

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

function nv_error_theme( $data, $error, $complete )
{
    global $global_config, $lang_global, $lang_module, $module_file, $module_info, $module_name;

    $xtpl = new XTemplate( "error.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'GLANG', $lang_global );
    $xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
		
    $xtpl->assign( 'DATA', $data );
	
	if( ! empty( $complete ) )
	{
		$xtpl->parse( 'main.complete' );
	}
	elseif( ! empty( $error ) )
	{
		$xtpl->assign( 'ERROR', $error );
		$xtpl->parse( 'main.error' );
	}
	else
	{
		$xtpl->parse( 'main.form' );
	}

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}

function nv_die_theme( $info_die )
{
    global $module_file, $module_info, $lang_module;

    $xtpl = new XTemplate( "die.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
    $xtpl->assign( 'INFO', $info_die );
	$xtpl->assign( 'LANG', $lang_module );

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
	
	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_site_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
	exit();
}

?>