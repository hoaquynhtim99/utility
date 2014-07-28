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
    
    $id = $nv_Request->get_int( 'id', 'post', 0 );
    
    if ( empty( $id ) )
    {
        die( 'NO' );
    }
    
    $sql = "SELECT title, alias FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id=" . $id;
    $result = $db->query( $sql );
    list( $title, $alias ) = $result->fetch( 3 );
    
    if ( empty( $title ) )
    {
        die( 'NO' );
    }
    $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_error WHERE sid=" . $id;
    $db->query( $sql );
	
    $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id=" . $id;
    $db->query( $sql );
	
    $sql = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . " ORDER BY weight ASC";
    $result = $db->query( $sql );
    $weight = 0;
    while ( $row = $result->fetch() )
    {
        $weight ++;
        $db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET weight=" . $weight . " WHERE id=" . $row['id'] );
    }

    nv_del_moduleCache( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, "Delete U", $title, $admin_info['userid'] );
    
	$status = nv_delete_utility_files( $alias );
	
	if( $status !== true ) die( 'NO' );
	
    die( 'OK' );
}

// Xoa cac bao loi
if ( $nv_Request->isset_request( 'delerror', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
    
    $id = $nv_Request->get_int( 'id', 'post', 0 );
    
    if ( empty( $id ) )
    {
        die( 'NO' );
    }
    
    $sql = "SELECT title FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id=" . $id;
    $result = $db->query( $sql );
    $title = $result->fetchColumn();
    
    if ( empty( $title ) )
    {
        die( 'NO' );
    }
    $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_error WHERE sid=" . $id;
    $db->query( $sql );
	
    $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET error=0 WHERE id=" . $id;
    $db->query( $sql );
	
    nv_del_moduleCache( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, "Delete error of", $title, $admin_info['userid'] );
    
    die( 'OK' );
}

// Thay doi thu tu cac tien ich
if ( $nv_Request->isset_request( 'changeweight', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
    
    $id = $nv_Request->get_int( 'id', 'post', 0 );
    $new = $nv_Request->get_int( 'new', 'post', 0 );
    
    if ( empty( $id ) ) die( 'NO' );
        
    $query = "SELECT id FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id!=" . $id . " ORDER BY weight ASC";
    $result = $db->query( $query );
    $weight = 0;
    while ( $row = $result->fetch() )
    {
        $weight ++;
        if ( $weight == $new ) $weight ++;
        $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET weight=" . $weight . " WHERE id=" . $row['id'];
        $db->query( $sql );
    }
    $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET weight=" . $new . " WHERE id=" . $id;
    $db->query( $sql );
    
    nv_del_moduleCache( $module_name );
    
    die( 'OK' );
}

// Active - Deactive
if ( $nv_Request->isset_request( 'changestatus', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
    
    $catid = $nv_Request->get_int( 'id', 'post', 0 );
    
    if ( empty( $catid ) ) die( 'NO' );
    
    $query = "SELECT status FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id=" . $catid;
    $result = $db->query( $query );
    $numrows = $result->rowCount();
    if ( $numrows != 1 ) die( 'NO' );
    
    $status = $result->fetchColumn();
    $status = $status ? 0 : 1;
    
    $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET status=" . $status . " WHERE id=" . $catid;
    $db->query( $sql );
    
    nv_del_moduleCache( $module_name );
    
    die( 'OK' );
}

$page_title = $lang_module['main_list'];
$array = array();

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " ORDER BY weight ASC";
$result = $db->query( $sql );
$num = $result->rowCount();

$i = 1;
while ( $row = $result->fetch() )
{
    $list_weight = array();
    for ( $j = 1; $j <= $num; $j ++ )
    {
        $list_weight[$j] = array(
			"weight" => $j,
			"title" => $j,
			"selected" => ( $j == $row['weight'] ) ? " selected=\"selected\"" : ""
		);
    }
	
	$array[] = array(
		"id" => $row['id'],
		"title" => $row['title'],
		"alias" => $row['alias'],
		"viewhit" => $row['viewhit'],
		"downloadhit" => $row['downloadhit'],
		"like" => $row['like'],
		"dislike" => $row['dislike'],
		"error" => $row['error'],
		"weight" => $list_weight,
		"status" => $row['status'] ? " checked=\"checked\"" : "",
		"url_edit" => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=content&amp;id=" . $row['id'],
		"class" => ( $i % 2 == 0 ) ? " class=\"second\"" : ""
	);
	$i ++;
}

$xtpl = new XTemplate( "main.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );


foreach ( $array as $row )
{
	$xtpl->assign( 'ROW', $row );
	
	if( $row['error'] )
	{
		$xtpl->parse( 'main.row.delerror' );
	}
    foreach ( $row['weight'] as $weight )
    {
		
        $xtpl->assign( 'WEIGHT', $weight );
        $xtpl->parse( 'main.row.weight' );
    }

	$xtpl->parse( 'main.row' );
}

// Kiem tra cac ham can thiet de chay
$array_info = array();

$disable_functions = ( ini_get( "disable_functions" ) != "" and ini_get( "disable_functions" ) != false ) ? array_map( 'trim', preg_split( "/[\s,]+/", ini_get( "disable_functions" ) ) ) : array();
if( extension_loaded( 'suhosin' ) )
{
	$disable_functions = array_merge( $disable_functions, array_map( 'trim', preg_split( "/[\s,]+/", ini_get( "suhosin.executor.func.blacklist" ) ) ) );
}

if( ! ( extension_loaded( 'curl' ) and ( empty( $disable_functions ) or ( ! empty( $disable_functions ) and ! preg_grep( '/^curl\_/', $disable_functions ) ) ) ) )
{
	$array_info[] = $lang_module['info_check_curl'];
}

foreach( $array_info as $info )
{
	$xtpl->assign( 'INFO', $info );
	$xtpl->parse( 'main.info' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';