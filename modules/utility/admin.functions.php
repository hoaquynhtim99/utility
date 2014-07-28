<?php

/**
 * @Project NUKEVIET 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 29, 2014, 12:13:24 AM
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

//$submenu['utility'] = $lang_module['utility'];
//$submenu['content'] = $lang_module['content'];
//$submenu['error'] = $lang_module['error'];

$allow_func = array( 'main', 'error', 'content', 'utility', 'ucontent' );

define( 'NV_IS_DGAT_ADMIN', true );

require_once ( NV_ROOTDIR . "/modules/" . $module_file . "/global.functions.php" );

/**
 * nv_redriect()
 * 
 * @param string $msg1
 * @param string $msg2
 * @param mixed $nv_redirect
 * @return void
 */
function nv_redriect($msg1 = "", $msg2 = "", $nv_redirect)
{
    if ( empty( $nv_redirect ) ) $nv_redirect = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name;

    $contents = "<table><tr><td>";
    $contents .= "<div align=\"center\">";
    $contents .= "<strong>" . $msg1 . "</strong><br /><br />\n";
    $contents .= "<img border=\"0\" src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\" /><br /><br />\n";
    $contents .= "<strong><a href=\"" . $nv_redirect . "\">" . $msg2 . "</a></strong>";
    $contents .= "</div>";
    $contents .= "</td></tr></table>";
    $contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . $nv_redirect . "\" />";
    
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_admin_theme( $contents );
    include NV_ROOTDIR . '/includes/footer.php';
}