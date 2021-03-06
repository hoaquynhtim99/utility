<?php

/**
 * @Project NUKEVIET 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 29, 2014, 12:13:24 AM
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );
	
/**
 * nv_curl_get()
 * 
 * @param mixed $target_url
 * @return
 */
function nv_curl_get( $target_url )
{
	$content = "";
	
	if( function_exists('curl_init') )
	{
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $target_url );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0' );
		$content = curl_exec( $ch );
		$errormsg = curl_error( $ch );
		curl_close( $ch );
			
		if ( $errormsg != "" )
		{
			return "";
		}
	}
		
	return $content;
}

function nv_delete_utility_files( $alias )
{
	global $module_file, $db;
	
	$alias = $alias;
	
	if( ! is_dir( NV_ROOTDIR . '/modules/' . $module_file . '/data/' . $alias ) )
	{
		return true;
	}
	
	$status = nv_deletefile( NV_ROOTDIR . '/modules/' . $module_file . '/data/' . $alias, true );
	if( $status[0] == 0 ) return $status[1];
	
	// Quen cac giao dien
	$list_themes = nv_scandir( NV_ROOTDIR . "/themes", $global_config['check_theme'] );
	
	if( ! empty( $list_themes ) )
	{
		foreach( $list_themes as $theme )
		{
			if( is_dir( NV_ROOTDIR . "/themes/" . $theme . "/modules/" . $module_file . "/" . $alias ) )
			{
				$status = nv_deletefile( NV_ROOTDIR . "/themes/" . $theme . "/modules/" . $module_file . "/" . $alias, true );
				if( $status[0] == 0 ) return $status[1];
			}
			if( is_dir( NV_ROOTDIR . "/themes/" . $theme . "/images/" . $module_file . "/" . $alias ) )
			{
				$status = nv_deletefile( NV_ROOTDIR . "/themes/" . $theme . "/images/" . $module_file . "/" . $alias, true );
				if( $status[0] == 0 ) return $status[1];
			}
		}
	}
	
	return true;
}