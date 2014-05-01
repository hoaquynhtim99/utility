<?php

/**
 * @Project NUKEVIET 3.1
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate 07-03-2011 20:15
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array( //
    "name" => "NukeViet Utilties Download", // Tieu de module
    "modfuncs" => "main, guidelines", //
    "is_sysmod" => 0, //
    "virtual" => 0, //
    "version" => "3.2.01", //
    "date" => "Mon, 07 Mar 2011 20:15:15 GMT", //
    "author" => "VINADES (contact@vinades.vn)", //
    "note" => "", //
    "uploads_dir" => array( 
        $module_name, //
        $module_name . "/images", //
        $module_name . "/thumb" //
    )
);

?>