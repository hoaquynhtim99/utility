<?php

/**
 * @Project NUKEVIET 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 29, 2014, 12:13:24 AM
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array(
    "name" => "NukeViet Utilties Download",
    "modfuncs" => "main, guidelines",
    "is_sysmod" => 0,
    "virtual" => 0,
    "version" => "4.0.01",
    "date" => "Mon, 28 Jul 2014 00:00:00 GMT",
    "author" => "PHAN TAN DUNG (phantandung92@gmail.com)",
    "note" => "",
    "uploads_dir" => array( 
        $module_name,
        $module_name . "/images",
        $module_name . "/thumb"
    )
);