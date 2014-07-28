<?php

/**
 * @Project NUKEVIET 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 29, 2014, 12:13:24 AM
 */

if ( ! defined( 'NV_IS_MOD_DGAT' ) ) die( 'Stop!!!' );

if( empty( $array_op[1] ) ) nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );

$sql = "SELECT alias, title, guide FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE alias=" . $db->quote( $array_op[1] ) . " AND status=1";
$list = nv_db_cache( $sql, 0, $module_name );
if( empty( $list ) ) nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );

$page_title = $mod_title = $lang_module['guidetitle'] . $list[0]['title'];
$key_words = $module_info['keywords'];
$description = $list[0]['guide'];

$contents = nv_guidelines_theme( $list[0] );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';