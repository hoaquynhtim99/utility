<?php

/**
 * @Project NUKEVIET 3.1
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate 24-06-2011 10:35
 */

if ( ! defined( 'NV_IS_DGAT_ADMIN' ) ) die( 'Stop!!!' );

// Shadowbox
$my_head = "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.js\"></script>\n";
$my_head .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.css\" />\n";
$my_head .= "<script type=\"text/javascript\">\n";
$my_head .= "Shadowbox.init();\n";
$my_head .= "</script>\n";

$id = $nv_Request->get_int( 'id', 'get', 0 );

if( ! empty( $id ) )
{
	$sql = "SELECT `info` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_error` WHERE `id`=" . $id;
	$result = $db->sql_query( $sql );
	list( $info ) = $db->sql_fetchrow( $result );

	if( empty( $info ) )
	{
		nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	}

	$sql = "UPDATE `" . NV_PREFIXLANG . "_" . $module_data . "_error` SET `status`=0 WHERE `id`=" . $id;
	$db->sql_query( $sql );

	$xtpl = new XTemplate( "viewerror.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'DATA', $info );
		
	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo ( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
	exit();
}

// Xoa bao loi
if ( $nv_Request->isset_request( 'del', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
    
    $id = $nv_Request->get_int( 'id', 'post', 0 );
    
    if ( empty( $id ) )
    {
        die( "NO" );
    }
    
    $sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $module_data . "_error` WHERE `id`=" . $id;
    $db->sql_query( $sql );

    nv_del_moduleCache( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, "Delete error", "", $admin_info['userid'] );
    
    die( "OK" );
}

$page_title = $lang_module['error_list'];
$array = array();
$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 30;
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;

$sql = "SELECT SQL_CALC_FOUND_ROWS a.id, b.id AS sid, b.title, a.username AS name, a.userid, a.email, a.ip, a.info, a.addtime, c.username, a.status FROM `" . NV_PREFIXLANG . "_" . $module_data . "_error` AS a INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "` AS b ON a.sid=b.id LEFT JOIN `" . NV_USERS_GLOBALTABLE . "` AS c ON a.userid=c.userid ORDER BY a.status,a.addtime DESC LIMIT " . $page . "," . $per_page;
$result = $db->sql_query( $sql );

$query = $db->sql_query( "SELECT FOUND_ROWS()" );
list( $all_page ) = $db->sql_fetchrow( $query );

$i = 1;
while ( $row = $db->sql_fetchrow( $result ) )
{	
	$array[] = array(
		"id" => $row['id'],  //
		"sid" => $row['sid'],  //
		"title" => $row['title'],  //
		"name" => $row['name'],  //
		"userid" => $row['userid'],  //
		"email" => $row['email'],  //
		"url_edit" => NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=content&amp;id=" . $row['sid'],
		"username" => empty( $row['username'] ) ? $lang_module['error_visitor'] : "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=edit&userid=" . $row['userid'] . "\">" . $row['username'] . "</a>",  //
		"ip" => $row['ip'],  //
		"info" => $row['info'],  //
		"addtime" => nv_date( "d/m/Y H:i", $row['addtime'] ),  //
		"status" => $lang_module['error_status_' . $row['status']],  //
		"class" => ( $i % 2 == 0 ) ? " class=\"second\"" : ""  //
	);
	$i ++;
}

$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

$xtpl = new XTemplate( "error.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'URL_VIEW', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;id=" );

foreach ( $array as $row )
{
	$xtpl->assign( 'ROW', $row );
	$xtpl->parse( 'main.row' );
}

if ( ! empty( $generate_page ) )
{
    $xtpl->assign( 'GENERATE_PAGE', $generate_page );
    $xtpl->parse( 'main.generate_page' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>