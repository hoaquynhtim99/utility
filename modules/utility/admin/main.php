<?php

/**
 * @Project NUKEVIET 3.1
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate 24-06-2011 10:35
 */

if ( ! defined( 'NV_IS_DGAT_ADMIN' ) ) die( 'Stop!!!' );

// Xoa tien ich
if ( $nv_Request->isset_request( 'del', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
    
    $id = $nv_Request->get_int( 'id', 'post', 0 );
    
    if ( empty( $id ) )
    {
        die( "NO" );
    }
    
    $sql = "SELECT `title`, `alias` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`=" . $id;
    $result = $db->sql_query( $sql );
    list( $title, $alias ) = $db->sql_fetchrow( $result );
    
    if ( empty( $title ) )
    {
        die( "NO" );
    }
    $sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_error` WHERE `sid`=" . $id;
    $db->sql_query( $sql );
	
    $sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`=" . $id;
    $db->sql_query( $sql );
	
    $sql = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` ORDER BY `weight` ASC";
    $result = $db->sql_query( $sql );
    $weight = 0;
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $weight ++;
        $db->sql_query( "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `weight`=" . $weight . " WHERE `id`=" . $row['id'] );
    }

    nv_del_moduleCache( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, "Delete U", $title, $admin_info['userid'] );
    
	$status = nv_delete_utility_files( $alias );
	
	if( $status !== true ) die( "NO" );
	
    die( "OK" );
}

// Xoa cac bao loi
if ( $nv_Request->isset_request( 'delerror', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
    
    $id = $nv_Request->get_int( 'id', 'post', 0 );
    
    if ( empty( $id ) )
    {
        die( "NO" );
    }
    
    $sql = "SELECT `title` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`=" . $id;
    $result = $db->sql_query( $sql );
    list( $title ) = $db->sql_fetchrow( $result );
    
    if ( empty( $title ) )
    {
        die( "NO" );
    }
    $sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_error` WHERE `sid`=" . $id;
    $db->sql_query( $sql );
	
    $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `error`=0 WHERE `id`=" . $id;
    $db->sql_query( $sql );
	
    nv_del_moduleCache( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, "Delete error of", $title, $admin_info['userid'] );
    
    die( "OK" );
}

// Thay doi thu tu cac tien ich
if ( $nv_Request->isset_request( 'changeweight', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
    
    $id = $nv_Request->get_int( 'id', 'post', 0 );
    $new = $nv_Request->get_int( 'new', 'post', 0 );
    
    if ( empty( $id ) ) die( "NO" );
        
    $query = "SELECT `id` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`!=" . $id . " ORDER BY `weight` ASC";
    $result = $db->sql_query( $query );
    $weight = 0;
    while ( $row = $db->sql_fetchrow( $result ) )
    {
        $weight ++;
        if ( $weight == $new ) $weight ++;
        $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `weight`=" . $weight . " WHERE `id`=" . $row['id'];
        $db->sql_query( $sql );
    }
    $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `weight`=" . $new . " WHERE `id`=" . $id;
    $db->sql_query( $sql );
    
    nv_del_moduleCache( $module_name );
    
    die( "OK" );
}

// Active - Deactive
if ( $nv_Request->isset_request( 'changestatus', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
    
    $catid = $nv_Request->get_int( 'id', 'post', 0 );
    
    if ( empty( $catid ) ) die( "NO" );
    
    $query = "SELECT `status` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `id`=" . $catid;
    $result = $db->sql_query( $query );
    $numrows = $db->sql_numrows( $result );
    if ( $numrows != 1 ) die( 'NO' );
    
    list( $status ) = $db->sql_fetchrow( $result );
    $status = $status ? 0 : 1;
    
    $sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "` SET `status`=" . $status . " WHERE `id`=" . $catid;
    $db->sql_query( $sql );
    
    nv_del_moduleCache( $module_name );
    
    die( "OK" );
}

$page_title = $lang_module['main_list'];
$array = array();

$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $module_data . "` ORDER BY `weight` ASC";
$result = $db->sql_query( $sql );
$num = $db->sql_numrows( $result );

$i = 1;
while ( $row = $db->sql_fetchrow( $result ) )
{
    $list_weight = array();
    for ( $j = 1; $j <= $num; $j ++ )
    {
        $list_weight[$j] = array(
			"weight" => $j,  //
			"title" => $j,  //
			"selected" => ( $j == $row['weight'] ) ? " selected=\"selected\"" : ""  //
		);
    }
	
	$array[] = array(
		"id" => $row['id'],  //
		"title" => $row['title'],  //
		"alias" => $row['alias'],  //
		"viewhit" => $row['viewhit'],  //
		"downloadhit" => $row['downloadhit'],  //
		"like" => $row['like'],  //
		"dislike" => $row['dislike'],  //
		"error" => $row['error'],  //
		"weight" => $list_weight,  //
		"status" => $row['status'] ? " checked=\"checked\"" : "",  //
		"url_edit" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=content&amp;id=" . $row['id'],  //
		"class" => ( $i % 2 == 0 ) ? " class=\"second\"" : ""  //
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

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>