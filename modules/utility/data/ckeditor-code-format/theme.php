<?php

/**
 * @Project NUKEVIET 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 29, 2014, 12:13:24 AM
 */

if ( ! defined( 'NV_IS_MOD_DGAT' ) ) die( 'Stop!!!' );

function nv_ckeditor_format_code_theme()
{
    global $lang_global, $lang_module, $module_file, $module_info, $array_op, $my_head;

	if( is_file( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file . "/" . $array_op[0] . "/main.tpl" ) )
	{
		$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file . "/" . $array_op[0] );
	}
	else
	{
    	$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/default/modules/" . $module_file . "/" . $array_op[0] );
	}

	$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "modules/" . $module_file . "/frameworks/autosize/jquery.autosize.js\"></script>";

    $xtpl->assign( 'LANG', $lang_module );
    $xtpl->assign( 'GLANG', $lang_global );

    $xtpl->parse( 'main' );
    return $xtpl->text( 'main' );
}