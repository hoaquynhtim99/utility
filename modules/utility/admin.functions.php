<?php

/**
 * @Project NUKEVIET 3.1
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate 07-03-2011 20:15
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$submenu['utility'] = $lang_module['utility'];
$submenu['content'] = $lang_module['content'];
$submenu['error'] = $lang_module['error'];

$allow_func = array( 'main', 'error', 'content', 'utility', 'ucontent' );

define( 'NV_IS_DGAT_ADMIN', true );

require_once ( NV_ROOTDIR . "/modules/" . $module_file . "/global.functions.php" );

function nv_redriect($msg1 = "", $msg2 = "", $nv_redirect)
{
    if ( empty( $nv_redirect ) ) $nv_redirect = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name;

    $contents = "<table><tr><td>";
    $contents .= "<div align=\"center\">";
    $contents .= "<strong>" . $msg1 . "</strong><br /><br />\n";
    $contents .= "<img border=\"0\" src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\" /><br /><br />\n";
    $contents .= "<strong><a href=\"" . $nv_redirect . "\">" . $msg2 . "</a></strong>";
    $contents .= "</div>";
    $contents .= "</td></tr></table>";
    $contents .= "<meta http-equiv=\"refresh\" content=\"2;url=" . $nv_redirect . "\" />";
    
    include ( NV_ROOTDIR . "/includes/header.php" );
    echo nv_admin_theme( $contents );
    include ( NV_ROOTDIR . "/includes/footer.php" );
    exit();
}

?>