<?php

/**
 * @Project NUKEVIET 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 29, 2014, 12:13:24 AM
 */

if( ! defined( 'NV_IS_DGAT_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['content_title'];

$error = "";
$groups_list = nv_groups_list();
$id = $nv_Request->get_int( 'id', 'get', 0 );

if( $id )
{
	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id=" . $id;
	$result = $db->query( $sql );
	$check = $result->rowCount();
		
	if( $check != 1 )
	{
		nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	}
		
	$row = $result->fetch();
		
	$array_old = $array = array(
		"alias" => $row['alias'],
		"title" => $row['title'],
		"images" => $row['images'],
		"logo" => $row['logo'],
		"introtext" => nv_br2nl( $row['introtext'] ),
		"description" => nv_editor_br2nl( $row['description'] ),
		"guide" => nv_editor_br2nl( $row['guide'] ),
		"iscache" => $row['iscache'],
		"delcache" => $row['delcache'],
		"groups_view" => ! empty( $row['groups_view'] ) ? explode( ",", $row['groups_view'] ) : array()
	);

	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;id=" . $id;
	$table_caption = $lang_module['content_edit'];
}
else
{
	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
	$table_caption = $lang_module['content_add'];
	
	$array = array(
		"alias" => "",
		"title" => "",
		"images" => "",
		"logo" => "",
		"introtext" => "",
		"description" => "",
		"guide" => "",
		"iscache" => 1,
		"delcache" => 0,
		"groups_view" => array( 6 )
	);
}

if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array['delcache'] = $nv_Request->get_int( 'delcache', 'post', 0 );
	$array['iscache'] = $nv_Request->get_int( 'iscache', 'post', 0 );
	
	$array['alias'] = nv_substr( $nv_Request->get_title( 'alias', 'post', '', 1 ), 0, 50 );
	$array['title'] = nv_substr( $nv_Request->get_title( 'title', 'post', '', 1 ), 0, 255 );
	
	$array['images'] = nv_unhtmlspecialchars( nv_substr( $nv_Request->get_title( 'images', 'post', '', 1 ), 0, 255 ) );
	$array['logo'] = nv_unhtmlspecialchars( nv_substr( $nv_Request->get_title( 'logo', 'post', '', 1 ), 0, 255 ) );
	
	$array['introtext'] = $nv_Request->get_textarea( 'introtext', '', NV_ALLOWED_HTML_TAGS );
	$array['description'] = $nv_Request->get_editor( 'description', '', NV_ALLOWED_HTML_TAGS );
	$array['guide'] = $nv_Request->get_editor( 'guide', '', NV_ALLOWED_HTML_TAGS );
	
	$array['groups_view'] = $nv_Request->get_typed_array( 'groups_view', 'post', 'int' );

	$array['images'] = empty( $array['images'] ) ? "" : substr ( $array['images'], strlen ( NV_BASE_SITEURL . NV_UPLOADS_DIR ) );
	$array['logo'] = empty( $array['logo'] ) ? "" : substr ( $array['logo'], strlen ( NV_BASE_SITEURL . NV_UPLOADS_DIR ) );
	
	// Check error
	if( empty ( $array['title'] ) )
	{
		$error = $lang_module['error_ctitle'];
	}
	elseif( ! preg_match( "/^([0-9a-z\-]+)$/", $array['alias'] ) )
	{
		$error = $lang_module['error_alias'];
	}
	elseif( ! is_file( NV_ROOTDIR . "/modules/" . $module_file . "/data/" . $array['alias'] . "/data.php" ) )
	{
		$error = $lang_module['error_dir_noexist'];
	}
	else
	{
		$array['introtext'] = nv_nl2br( $array['introtext'] );
		$array['description'] = nv_editor_nl2br( $array['description'] );
		$array['guide'] = nv_editor_nl2br( $array['guide'] );
		
		if( empty( $id ) )
		{
			$sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE alias=" . $db->quote( $array['alias'] );
			$result = $db->query( $sql );
			list( $check_exist ) = $result->fetch( 3 );
			
			if( $check_exist )
			{
				$error = $lang_module['error_exist'];
			}
			else
			{
				$sql = "SELECT MAX(weight) FROM " . NV_PREFIXLANG . "_" . $module_data . "";
				$result = $db->query( $sql );
				list ( $weight ) = $result->fetch( 3 );
				$new_weight = $weight + 1;

				$sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . " VALUES (
					NULL, 
					" . $db->quote( $array['alias'] ) . ", 
					" . $db->quote( $array['title'] ) . ", 
					" . $db->quote( $array['images'] ) . ", 
					" . $db->quote( $array['logo'] ) . ", 
					" . $db->quote( $array['introtext'] ) . ", 
					" . $db->quote( $array['description'] ) . ", 
					" . $db->quote( $array['guide'] ) . ", 
					" . NV_CURRENTTIME . ", 0, 0, 0, 0,
					" . $array['iscache'] . ",
					" . $array['delcache'] . ",
					0,
					" . $db->quote( implode( ",", $array['groups_view'] ) ) . ", 
					" . $new_weight . ", 1
				)";
				
				$id_result = $db->insert_id( $sql );
				
				if( $id_result )
				{
					//$xxx->closeCursor();
					nv_del_moduleCache( $module_name );
					nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['content_add'], $array['title'], $admin_info['userid'] );
					Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name );
					die();
				}
				else
				{
					$error = $lang_module['error_save'];
				}
			}
		}
		else
		{
			$sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE alias=" . $db->quote( $array['alias'] ) . " AND id!=" . $id;
			$result = $db->query( $sql );
			list ( $check_exist ) = $result->fetch( 3 );
			
			if( $check_exist )
			{
				$error = $lang_module['error_exist'];
			}
			else
			{
				$sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET 
					alias=" . $db->quote( $array['alias'] ) . ", 
					title=" . $db->quote( $array['title'] ) . ", 
					images=" . $db->quote( $array['images'] ) . ", 
					logo=" . $db->quote( $array['logo'] ) . ", 
					introtext=" . $db->quote( $array['introtext'] ) . ", 
					description=" . $db->quote( $array['description'] ) . ",
					guide=" . $db->quote( $array['guide'] ) . ",
					iscache=" . $array['iscache'] . ",
					delcache=" . $array['delcache'] . ",
					groups_view=" . $db->quote( implode( ",", $array['groups_view'] ) ) . "
				WHERE id =" . $id;
					
				if( $db->query( $sql ) )
				{
					// Xoa ung dung cu neu sua ailas
					if( $array['alias'] != $array_old['alias'] )
					{
						$check = nv_delete_utility_files( $array_old['alias'] );
						
						if( $check !== true )
						{
							$error = $lang_module['error_change_utility_alias'];
						}
					}
				
					if( empty( $error ) )
					{
						//$xxx->closeCursor();
						nv_del_moduleCache( $module_name );
						nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['content_edit'], $array_old['title'] . "&nbsp;=&gt;&nbsp;" . $array['title'], $admin_info['userid'] );
						Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name );
						exit();
					}
				}
				else
				{
					$error = $lang_module['error_update'];
				}
			}
		}
	}
}
	
// Build description
if( ! empty( $array['introtext'] ) ) $array['introtext'] = nv_htmlspecialchars( $array['introtext'] );
if( ! empty( $array['description'] ) ) $array['description'] = nv_htmlspecialchars( $array['description'] );
if( ! empty( $array['guide'] ) ) $array['guide'] = nv_htmlspecialchars( $array['guide'] );

if( defined( 'NV_EDITOR' ) )
{
	require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
}

if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
{
	$array['description'] = nv_aleditor( 'description', '100%', '200px', $array['description'] );
	$array['guide'] = nv_aleditor( 'guide', '100%', '200px', $array['guide'] );
}
else
{
	$array['description'] = "<textarea style=\"width:100%; height:200px\" name=\"description\" id=\"description\">" . $array['description'] . "</textarea>";
	$array['guide'] = "<textarea style=\"width:100%; height:200px\" name=\"guide\" id=\"guide\">" . $array['guide'] . "</textarea>";
}
	
if( ! empty ( $array['images'] ) ) $array['images'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . $array['images']; 
if( ! empty ( $array['logo'] ) ) $array['logo'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . $array['logo']; 

// Int to string
$array['iscache'] = $array['iscache'] ? " checked=\"checked\"" : "";

$groups_view = $array['groups_view'];
$array['groups_view'] = array();
if( ! empty( $groups_list ) )
{
	foreach ( $groups_list as $key => $title )
	{
		$array['groups_view'][] = array(
			'key' => $key,
			'title' => $title,
			'checked' => in_array( $key, $groups_view ) ? " checked=\"checked\"" : ""
		);
	}
}

$xtpl = new XTemplate( "content.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'DATA', $array );
$xtpl->assign( 'TABLE_CAPTION', $table_caption );
$xtpl->assign( 'FORM_ACTION', $form_action );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'IMG_DIR', NV_UPLOADS_DIR . '/' . $module_name . '/images' );

// Prase error
if( ! empty ( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}
    
if( ! empty( $array['groups_view'] ) )
{
	foreach ( $array['groups_view'] as $group )
	{
		$xtpl->assign( 'groups_view', $group );
		$xtpl->parse( 'main.group1.groups_view' );
	}
	$xtpl->parse( 'main.group1' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';