<?php

/**
 * @Project NUKEVIET 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 29, 2014, 12:13:24 AM
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die('Stop!!!');

$sql_drop_module = array();
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_error";

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . " (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  alias varchar(50) NOT NULL DEFAULT '',
  title varchar(255) NOT NULL DEFAULT '',
  images varchar(255) NOT NULL DEFAULT '',
  logo varchar(255) NOT NULL DEFAULT '',
  introtext mediumtext NOT NULL,
  description mediumtext NOT NULL,
  guide mediumtext NOT NULL,
  addtime int(11) unsigned NOT NULL DEFAULT '0',
  viewhit int(11) unsigned NOT NULL DEFAULT '0',
  downloadhit int(11) unsigned NOT NULL DEFAULT '0',
  numlike int(11) unsigned NOT NULL DEFAULT '0',
  numdislike int(11) unsigned NOT NULL DEFAULT '0',
  iscache tinyint(1) unsigned NOT NULL DEFAULT '0',
  delcache int(11) unsigned NOT NULL DEFAULT '0',
  error tinyint(1) unsigned NOT NULL DEFAULT '0',
  groups_view varchar(255) NOT NULL DEFAULT '',
  weight smallint(4) unsigned NOT NULL DEFAULT '0',
  status tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  UNIQUE KEY alias (alias)
)ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_error (
  id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  sid mediumint(8) unsigned NOT NULL DEFAULT '0',
  userid mediumint(8) unsigned NOT NULL DEFAULT '0',
  username varchar(255) NOT NULL DEFAULT '',
  email varchar(255) NOT NULL DEFAULT '',
  ip varchar(255) NOT NULL DEFAULT '',
  info mediumtext NOT NULL,
  addtime int(11) unsigned NOT NULL DEFAULT '0',
  status tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
)ENGINE=MyISAM";