<?php

/**
 * @Project NUKEVIET 3.1
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate 22/2/2011, 22:49
 */

if ( ! defined( 'NV_IS_DGAT_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $lang_module['content_title'];

$id = $nv_Request->get_int( 'id', 'get', 0 );
$error = "";

$groups_list = nv_groups_list();
$array_who = array( $lang_global['who_view0'], $lang_global['who_view1'], $lang_global['who_view2'] );
if ( ! empty( $groups_list ) )
{
	$array_who[] = $lang_global['who_view3'];
}

if( $id )
{
	$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`=" . $id;
	$result = $db->sql_query( $sql );
	$check = $db->sql_numrows( $result );
		
	if ( $check != 1 )
	{
		nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	}
		
	$row = $db->sql_fetchrow( $result );
		
	$array_old = $array = array(
		"alias" => $row['alias'],  //
		"title" => $row['title'],  //
		"images" => $row['images'],  //
		"logo" => $row['logo'],  //
		"introtext" => nv_br2nl( $row['introtext'] ),  //
		"description" => nv_editor_br2nl( $row['description'] ),  //
		"guide" => nv_editor_br2nl( $row['guide'] ),  //
		"iscache" => $row['iscache'],  //
		"delcache" => $row['delcache'],  //
		"who_view" => $row['who_view'],  //
		"groups_view" => ! empty( $row['groups_view'] ) ? explode( ",", $row['groups_view'] ) : array()  //
	);

	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;id=" . $id;
	$table_caption = $lang_module['content_edit'];
}
else
{
	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
	$table_caption = $lang_module['content_add'];
	
	$array = array(
		"alias" => "",  //
		"title" => "",  //
		"images" => "",  //
		"logo" => "",  //
		"introtext" => "",  //
		"description" => "",  //
		"guide" => "",  //
		"iscache" => 1,  //
		"delcache" => 0,  //
		"who_view" => 0,  //
		"groups_view" => array()  //
	);
}

if ( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array['delcache'] = $nv_Request->get_int( 'delcache', 'post', 0 );
	$array['iscache'] = $nv_Request->get_int( 'iscache', 'post', 0 );
	
	$array['alias'] = filter_text_input( 'alias', 'post', '', 1, 50 );
	$array['title'] = filter_text_input( 'title', 'post', '', 1, 255 );
	
	$array['images'] = nv_unhtmlspecialchars( filter_text_input( 'images', 'post', '', 1, 255 ) );
	$array['logo'] = nv_unhtmlspecialchars( filter_text_input( 'logo', 'post', '', 1, 255 ) );
	
	$array['introtext'] = filter_text_textarea( 'introtext', '', NV_ALLOWED_HTML_TAGS );
	$array['description'] = nv_editor_filter_textarea( 'description', '', NV_ALLOWED_HTML_TAGS );
	$array['guide'] = nv_editor_filter_textarea( 'guide', '', NV_ALLOWED_HTML_TAGS );
	
	$array['who_view'] = $nv_Request->get_int( 'who_view', 'post', 0 );
	$array['groups_view'] = $nv_Request->get_typed_array( 'groups_view', 'post', 'int' );

	$array['images'] = empty( $array['images'] ) ? "" : substr ( $array['images'], strlen ( NV_BASE_SITEURL . NV_UPLOADS_DIR ) );
	$array['logo'] = empty( $array['logo'] ) ? "" : substr ( $array['logo'], strlen ( NV_BASE_SITEURL . NV_UPLOADS_DIR ) );
	
	// Check error
	if ( empty ( $array['title'] ) )
	{
		$error = $lang_module['error_title'];
	}
	elseif ( ! preg_match( "/^([0-9a-z\-]+)$/", $array['alias'] ) )
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
		
		if ( ! in_array( $array['who_view'], array_keys( $array_who ) ) ) $array['who_view'] = 0;
		
		if( empty( $id ) )
		{
			$sql = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `alias`=" . $db->dbescape( $array['alias'] );
			$result = $db->sql_query( $sql );
			list ( $check_exist ) = $db->sql_fetchrow( $result );
			
			if ( $check_exist )
			{
				$error = $lang_module['error_exist'];
			}
			else
			{
				$sql = "SELECT MAX(`weight`) FROM `" . NV_PREFIXLANG . "_" . $module_data . "`";
				$result = $db->sql_query( $sql );
				list ( $weight ) = $db->sql_fetchrow( $result );
				$new_weight = $weight + 1;

				$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "` VALUES (
					NULL, 
					" . $db->dbescape( $array['alias'] ) . ", 
					" . $db->dbescape( $array['title'] ) . ", 
					" . $db->dbescape( $array['images'] ) . ", 
					" . $db->dbescape( $array['logo'] ) . ", 
					" . $db->dbescape( $array['introtext'] ) . ", 
					" . $db->dbescape( $array['description'] ) . ", 
					" . $db->dbescape( $array['guide'] ) . ", 
					" . NV_CURRENTTIME . ", 0, 0, 0, 0,
					" . $array['iscache'] . ",
					" . $array['delcache'] . ",
					0,
					" . $array['who_view'] . ",
					" . $db->dbescape( implode( ",", $array['groups_view'] ) ) . ", 
					" . $new_weight . ", 1
				)";
				
				$id_result = $db->sql_query_insert_id( $sql );
				
				if ( $id_result )
				{
					$db->sql_freeresult();
					nv_del_moduleCache( $module_name );
					nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['content_add'], $array['title'], $admin_info['userid'] );
					Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=main" );
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
			$sql = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `alias`=" . $db->dbescape( $array['alias'] ) . " AND `id`!=" . $id;
			$result = $db->sql_query( $sql );
			list ( $check_exist ) = $db->sql_fetchrow( $result );
			
			if ( $check_exist )
			{
				$error = $lang_module['error_exist'];
			}
			else
			{
				$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET 
					`alias`=" . $db->dbescape( $array['alias'] ) . ", 
					`title`=" . $db->dbescape( $array['title'] ) . ", 
					`images`=" . $db->dbescape( $array['images'] ) . ", 
					`logo`=" . $db->dbescape( $array['logo'] ) . ", 
					`introtext`=" . $db->dbescape( $array['introtext'] ) . ", 
					`description`=" . $db->dbescape( $array['description'] ) . ",
					`guide`=" . $db->dbescape( $array['guide'] ) . ",
					`iscache`=" . $array['iscache'] . ",
					`who_view`=" . $array['who_view'] . ",
					`delcache`=" . $array['delcache'] . ",
					`groups_view`=" . $db->dbescape( implode( ",", $array['groups_view'] ) ) . "
				WHERE `id` =" . $id;
					
				if ( $db->sql_query( $sql ) )
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
						$db->sql_freeresult();
						nv_del_moduleCache( $module_name );
						nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['content_edit'], $array_old['title'] . "&nbsp;=&gt;&nbsp;" . $array['title'], $admin_info['userid'] );
						Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=main" );
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
if ( ! empty( $array['introtext'] ) ) $array['introtext'] = nv_htmlspecialchars( $array['introtext'] );
if ( ! empty( $array['description'] ) ) $array['description'] = nv_htmlspecialchars( $array['description'] );
if ( ! empty( $array['guide'] ) ) $array['guide'] = nv_htmlspecialchars( $array['guide'] );

if ( defined( 'NV_EDITOR' ) )
{
	require_once ( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php' );
}

if ( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
{
	$array['description'] = nv_aleditor( 'description', '100%', '200px', $array['description'] );
	$array['guide'] = nv_aleditor( 'guide', '100%', '200px', $array['guide'] );
}
else
{
	$array['description'] = "<textarea style=\"width:100%; height:200px\" name=\"description\" id=\"description\">" . $array['description'] . "</textarea>";
	$array['guide'] = "<textarea style=\"width:100%; height:200px\" name=\"guide\" id=\"guide\">" . $array['guide'] . "</textarea>";
}
	
if ( ! empty ( $array['images'] ) ) $array['images'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . $array['images']; 
if ( ! empty ( $array['logo'] ) ) $array['logo'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . $array['logo']; 

// Int to string
$array['iscache'] = $array['iscache'] ? " checked=\"checked\"" : "";

$who_view = $array['who_view'];
$array['who_view'] = array();
foreach ( $array_who as $key => $who )
{
	$array['who_view'][] = array(  //
		'key' => $key, //
		'title' => $who, //
		'selected' => $key == $who_view ? " selected=\"selected\"" : ""  //
	);
}
    
$groups_view = $array['groups_view'];
$array['groups_view'] = array();
if ( ! empty( $groups_list ) )
{
	foreach ( $groups_list as $key => $title )
	{
		$array['groups_view'][] = array(  //
			'key' => $key, //
			'title' => $title, //
			'checked' => in_array( $key, $groups_view ) ? " checked=\"checked\"" : ""  //
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
if ( ! empty ( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

foreach ( $array['who_view'] as $who )
{
	$xtpl->assign( 'who_view', $who );
	$xtpl->parse( 'main.who_view' );
}
    
if ( ! empty( $array['groups_view'] ) )
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

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>