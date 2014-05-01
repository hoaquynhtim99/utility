<?php

/**
 * @Project NUKEVIET 3.1
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate 07-03-2011 20:15
 */

if ( ! defined( 'NV_IS_MOD_DGAT' ) ) die( 'Stop!!!' );

if( empty( $array_op[1] ) ) nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );

$sql = "SELECT `alias`, `title`, `guide` FROM `" . NV_PREFIXLANG . "_" . $module_data . "` WHERE `alias`=" . $db->dbescape( $array_op[1] ) . " AND `status`=1";
$list = nv_db_cache( $sql, 0, $module_name );
if( empty( $list ) ) nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );

$page_title = $mod_title = $lang_module['guidetitle'] . $list[0]['title'];
$key_words = $module_info['keywords'];
$description = $list[0]['guide'];

$contents = nv_guidelines_theme( $list[0] );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_site_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>