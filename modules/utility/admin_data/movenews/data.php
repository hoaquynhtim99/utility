<?php

/**
 * @Project NUKEVIET 3.1
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate 24-06-2011 10:35
 */

if ( ! defined( 'NV_IS_DGAT_ADMIN' ) ) die( 'Stop!!!' );

$c = '';

require( NV_ROOTDIR . '/modules/' . $module_file . '/admin_data/' . $u . '/functions.php' );

function nv_u_exit($info)
{
	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_admin_theme('<table class="tab1"><tbody><tr><td>' . $info . '</td></tr></tbody></table>');
	include ( NV_ROOTDIR . "/includes/footer.php" );
	exit();
}

if( $nv_Request->isset_request( 'loadblock', 'get' ) )
{
	$mod = filter_text_input( 'loadblock', 'get', '' );
	
	$sql = "SELECT bid, title FROM `" . NV_PREFIXLANG . "_" . str_replace("-","_",$mod) . "_block_cat` ORDER BY `weight` ASC";
	$result = $db->sql_query( $sql );
	
	while ( list( $bid_i, $title_i ) = $db->sql_fetchrow( $result ) )
	{
		$c .= '<input type="checkbox" class="block" value="' . $bid_i . '"/> ' . $title_i . ' ';
	}
	die($c);
}

if( $nv_Request->isset_request( 'loadsources', 'get' ) )
{
	$mod = filter_text_input( 'loadsources', 'get', '' );
	
	$result = $db->sql_query( "SELECT * FROM `" . NV_PREFIXLANG . "_" . str_replace("-","_",$mod) . "_sources` ORDER BY `weight`" );
	
	$c .= '<select id="sources">';
	$c .= '<option value="">' . $u_lang['move_to_default'] . '</option>';
	while ( $row = $db->sql_fetchrow( $result ) )
	{
		$c .= '<option value="' . $row['sourceid'] . '">' . $row['title'] . '</option>';
	}
	
	$c .= '</select>';
	die($c);
}

if( $nv_Request->isset_request( 'loadtopics', 'get' ) )
{
	$mod = filter_text_input( 'loadtopics', 'get', '' );
	
    $sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . str_replace("-","_",$mod) . "_topics` ORDER BY `weight` ASC";
	$result = $db->sql_query( $sql );
	
	$c .= '<select id="topics">';
	$c .= '<option value="">' . $u_lang['move_to_default'] . '</option>';
	while ( $row = $db->sql_fetchrow( $result ) )
	{
		$c .= '<option value="' . $row['topicid'] . '">' . $row['title'] . '</option>';
	}
	
	$c .= '</select>';
	die($c);
}

if( $nv_Request->isset_request( 'action', 'get' ) )
{
	$per_page = 200;
	
	$config = $nv_Request->get_string( 'utility_tmp', 'session', '' );
	$config = $config ? unserialize( $config ) : array();
	
	// Danh sach ID bi loi khong di chuyen duoc
	$array_error = $nv_Request->get_string( 'utility_tmp1', 'session', '' );
	$array_error = $array_error ? unserialize( $array_error ) : array();
	
	if( empty( $config ) ) nv_u_exit($u_lang['info_end']);
	
	if( ! empty( $config['fcat'] ) )
	{
		foreach( $config['fcat'] as $_key => $_cat )
		{
			$cat_data = nv_get_cat($config['fmod']);
			
			$tmod_data = str_replace("-","_",$config['tmod']);
			$fmod_data = str_replace("-","_",$config['fmod']);
			
			$c .= '<div class="infook">' . sprintf( $u_lang['pross_s1'], $cat_data[$_cat]['title'] );
			
			$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $fmod_data . "_" . $_cat . "` LIMIT 0," . $per_page;
			$gresult = $db->sql_query( $sql );
			
			while ( $row = $db->sql_fetchrow( $gresult ) )
			{
				if( in_array( $row['id'], $config['h_nid'] ) ) continue;
				
				$row['new_imgfile'] = false;
				$row['new_imgthumb'] = false;
				
				// Dau tien la copy sang chu de moi
				
				// Di chuyen anh minh hoa truoc			
				$homeimgfile = NV_UPLOADS_REAL_DIR . "/" . $config['fmod'] . "/" . $row['homeimgfile'];
				if( ! empty( $row['homeimgfile'] ) and file_exists($homeimgfile) )
				{
					// Creat new folder
					$folder = date( "Y_m", intval( $row['addtime'] ) );
					if ( ! file_exists( NV_UPLOADS_REAL_DIR . '/' . $config['tmod'] . "/" . $folder ) )
					{
						nv_mkdir( NV_UPLOADS_REAL_DIR . '/' . $config['tmod'], $folder );
					}
				
					$basename = basename($homeimgfile);
					$news_name = $basename;
					$i = 1;
					while (file_exists(NV_UPLOADS_REAL_DIR . '/' . $config['tmod'] . '/' . $folder . '/' . $news_name))
					{
						$news_name = preg_replace('/(.*)(\.[a-zA-Z]+)$/', '\1_' . $i . '\2', $basename);
						++$i;
					}
					
					if( nv_copyfile( $homeimgfile, NV_UPLOADS_REAL_DIR . '/' . $config['tmod'] . '/' . $folder . '/' . $news_name ) )
					{
						$row['new_imgfile'] = true;
						$row['homeimgfile'] = $folder . '/' . $news_name;
					}
					else
					{
						$row['homeimgfile'] = "";
					}
				}
				
				// Tao moi anh  
				$homeimgfile = NV_UPLOADS_REAL_DIR . "/" . $config['tmod'] . "/" . $row['homeimgfile'];
				if (! empty( $row['homeimgfile'] ) and file_exists($homeimgfile))
				{
					require_once (NV_ROOTDIR . "/includes/class/image.class.php");
					
					$basename = basename($homeimgfile);
					$image = new image($homeimgfile, NV_MAX_WIDTH, NV_MAX_HEIGHT);

					$thumb_basename = $basename;
					$i = 1;
					while (file_exists(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $config['tmod'] . '/thumb/' . $thumb_basename))
					{
						$thumb_basename = preg_replace('/(.*)(\.[a-zA-Z]+)$/', '\1_' . $i . '\2', $basename);
						++$i;
					}

					$image->resizeXY($config['homewidth'], $config['homeheight']);
					$image->save(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $config['tmod'] . '/thumb', $thumb_basename);
					$image_info = $image->create_Image_info;
					$thumb_name = str_replace(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $config['tmod'] . '/', '', $image_info['src']);

					$block_basename = $basename;
					$i = 1;
					while (file_exists(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $config['tmod'] . '/block/' . $block_basename))
					{
						$block_basename = preg_replace('/(.*)(\.[a-zA-Z]+)$/', '\1_' . $i . '\2', $basename);
						++$i;
					}
					$image->resizeXY($config['blockwidth'], $config['blockheight']);
					$image->save(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $config['tmod'] . '/block', $block_basename);
					$image_info = $image->create_Image_info;
					$block_name = str_replace(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $config['tmod'] . '/', '', $image_info['src']);

					$image->close();
					
					$row['new_imgthumb'] = true;
					$row['homeimgthumb'] = $thumb_name . "|" . $block_name;
				}
				
				$is_error = false;
				
				// Copy sang bang rows
				$sql = "INSERT INTO `" . NV_PREFIXLANG . "_" . $tmod_data . "_rows` 
				(`id`, `catid`, `listcatid`, `topicid`, `admin_id`, `author`, `sourceid`, `addtime`, `edittime`, `status`, `publtime`, `exptime`, `archive`, `title`, `alias`, `hometext`, `homeimgfile`, `homeimgalt`, `homeimgthumb`, `inhome`, `allowed_comm`, `allowed_rating`, `hitstotal`, `hitscm`, `total_rating`, `click_rating`, `keywords`) VALUES (
					NULL, 
					" . intval($config['tcat']) . ",
					" . $db->dbescape_string($config['tcat']) . ",
					" . intval($config['topics']) . ",
					" . intval($row['admin_id']) . ",
					" . $db->dbescape_string($row['author']) . ",
					" . intval($config['sources']) . ",
					" . intval($row['addtime']) . ",
					" . intval($row['edittime']) . ",
					" . intval($row['status']) . ",
					" . intval($row['publtime']) . ",
					" . intval($row['exptime']) . ", 
					" . intval($row['archive']) . ",
					" . $db->dbescape_string($row['title']) . ",
					" . $db->dbescape_string($row['alias']) . ",
					" . $db->dbescape_string($row['hometext']) . ",
					" . $db->dbescape_string($row['homeimgfile']) . ",
					" . $db->dbescape_string($row['homeimgalt']) . ",
					" . $db->dbescape_string($row['homeimgthumb']) . ",
					" . intval($row['inhome']) . ",  
					" . intval($row['allowed_comm']) . ", 
					" . intval($row['allowed_rating']) . ", 
					" . intval($row['hitstotal']) . ",  
					" . intval($row['hitscm']) . ",  
					" . intval($row['total_rating']) . ",  
					" . intval($row['click_rating']) . ",  
					" . $db->dbescape_string($row['keywords']) . "
				)";
				
				$new_id = $db->sql_query_insert_id($sql);
				
				if( $new_id )
				{
					// Tao bang bodyhtml
					$tbhtml = NV_PREFIXLANG . "_" . $tmod_data . "_bodyhtml_" . ceil($new_id / 2000);
					$db->sql_query("CREATE TABLE IF NOT EXISTS `" . $tbhtml . "` (`id` int(11) unsigned NOT NULL, `bodyhtml` longtext NOT NULL, `sourcetext` varchar(255) NOT NULL default '', `imgposition` tinyint(1) NOT NULL default '1', `copyright` tinyint(1) NOT NULL default '0', `allowed_send` tinyint(1) NOT NULL default '0', `allowed_print` tinyint(1) NOT NULL default '0', `allowed_save` tinyint(1) NOT NULL default '0', PRIMARY KEY  (`id`)) ENGINE=MyISAM");
					
					$ct_query = array();
					// Lay bodyhtml cua tin
					$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $fmod_data . "_bodyhtml_" . ceil(intval($row['id']) / 2000) . "` WHERE `id`=" . $row['id'];
					$result = $db->sql_query( $sql );
					$rowcontent = $db->sql_fetchrow( $result );
					
					$ct_query[] = (int)$db->sql_query("INSERT INTO `" . $tbhtml . "` VALUES (
						" . $new_id . ", 
						" . $db->dbescape_string($rowcontent['bodyhtml']) . ", 
						" . $db->dbescape_string($rowcontent['sourcetext']) . ",
						" . intval($rowcontent['imgposition']) . ",
						" . intval($rowcontent['copyright']) . ",  
						" . intval($rowcontent['allowed_send']) . ",  
						" . intval($rowcontent['allowed_print']) . ",  
						" . intval($rowcontent['allowed_save']) . "					
					)");
					
					// Them vao bang cat
					$ct_query[] = (int)$db->sql_query("INSERT INTO `" . NV_PREFIXLANG . "_" . $tmod_data . "_" . $config['tcat'] . "` SELECT * FROM `" . NV_PREFIXLANG . "_" . $tmod_data . "_rows` WHERE `id`=" . $new_id . "");				
					
					// Them cao bodytext
					$sql = "SELECT * FROM `" . NV_PREFIXLANG . "_" . $fmod_data . "_bodytext` WHERE `id`=" . $row['id'];
					$result = $db->sql_query( $sql );
					$rowcontent = $db->sql_fetchrow( $result );
					
					$ct_query[] = (int)$db->sql_query("INSERT INTO `" . NV_PREFIXLANG . "_" . $tmod_data . "_bodytext` VALUES (" . $new_id . ", " . $db->dbescape_string($rowcontent['bodytext']) . ")");
					
					if (array_sum($ct_query) != sizeof($ct_query)) $is_error = true;
					unset($ct_query);
				}
				else
				{
					$is_error = true;
				}
				
				// Ket thuc qua trinh copy sang chu de moi
				
				// Xu ly nhom tin
				if( ! $is_error )
				{
					foreach( $config['block'] as $bid_i )
					{
						$db->sql_query("INSERT INTO `" . NV_PREFIXLANG . "_" . $tmod_data . "_block` (`bid`, `id`, `weight`) VALUES ('" . $bid_i . "', '" . $new_id . "', '0')");
					}
					
					$config['block'][] = 0;
					$db->sql_query("DELETE FROM `" . NV_PREFIXLANG . "_" . $tmod_data . "_block` WHERE `id` = " . $new_id . " AND `bid` NOT IN (" . implode(",", $config['block']) . ")");
					
					$array_block_cat_module = array();
					$sql = "SELECT bid, adddefault, title FROM `" . NV_PREFIXLANG . "_" . $tmod_data . "_block_cat` ORDER BY `weight` ASC";
					$result = $db->sql_query($sql);
					while (list($bid_i, $adddefault_i, $title_i) = $db->sql_fetchrow($result))
					{
						$array_block_cat_module[$bid_i] = $title_i;
					}
					$config['block'] = array_keys($array_block_cat_module);
					foreach ($config['block'] as $bid_i)
					{
						nv_unews_fix_block($bid_i, $tmod_data, false);
					}
				}
				
				if( $is_error )
				{
					// Xoa cac anh moi di
					if($row['new_imgfile'])
					{
						@nv_deletefile( NV_UPLOADS_REAL_DIR . "/" . $config['tmod'] . "/" . $row['homeimgfile'] );
					}
					if( $row['new_imgthumb'] )
					{
						$row['homeimgthumb'] = explode("|",$row['homeimgthumb']);
						@nv_deletefile( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $config['tmod'] . '/thumb/' . $row['homeimgthumb'][0] );
						@nv_deletefile( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $config['tmod'] . '/block/' . $row['homeimgthumb'][1] );
					}
					$array_error[] = $row['id'];
				}
				else
				{
					$config['h_nid'][] = $row['id'];
				}
				
				nv_udel_content_module( $row['id'], $config['fmod'], $fmod_data ); // Xoa bai viet o chu de cu
			}
			
			$sql = "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . $fmod_data . "_" . $_cat . "`";
			$result = $db->sql_query( $sql );
			list( $check_num ) = $db->sql_fetchrow($result);
			if( ! $check_num )unset( $config['fcat'][$_key] ); // Neu het bai viet roi thi loai chu de ra khoi danh sach

			$c .= '<div class="center"><p class="center"><img src="' . NV_BASE_SITEURL . 'images/load_bar.gif" alt="Watting..."/></p><p>' . $u_lang['pross_s2'] . '</p></div>';
			$c .= '<meta http-equiv="refresh" content="2;url=' . $client_info['selfurl'] . '" />';	
			$c .= '</div>';
			
			$nv_Request->set_Session( 'utility_tmp', serialize( $config ) );
			$nv_Request->set_Session( 'utility_tmp1', serialize( $array_error ) );
			
			break;
		}
	}
	else
	{
		if( $config['delcat'] ) // Xoa cac chu de
		{
			$mod_data = str_replace("-","_", $config['fmod']);
			
			foreach( $config['fcat1'] as $catid )
			{
				$sql = "DELETE FROM `" . NV_PREFIXLANG . "_" . $mod_data . "_cat` WHERE catid=" . $catid;
				if ($db->sql_query($sql))
				{
					$db->sql_query("DROP TABLE `" . NV_PREFIXLANG . "_" . $mod_data . "_" . $catid . "`");
					$db->sql_query("DELETE FROM `" . NV_PREFIXLANG . "_" . $mod_data . "_admins` WHERE `catid`=" . $catid);
				}
			}
			
			nv_ufix_cat_order($mod_data);
		}
		
		$c .= '<div class="infook">';
		$c .= sprintf( $u_lang['pross_s3'], $config['num_news'] );
		if( ! empty( $array_error ) ) $c .= ' ' . sprintf( $u_lang['pross_s4'], sizeof( $array_error ) );
		$c .= '</div>';
		
		$nv_Request->unset_request( 'utility_tmp', 'session' );
		$nv_Request->unset_request( 'utility_tmp1', 'session' );
		
		nv_delete_all_cache();
	}
	
	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_admin_theme($c);
	include ( NV_ROOTDIR . "/includes/footer.php" );
	exit();
}

if( $nv_Request->isset_request( 'setup', 'get' ) )
{
	$nv_Request->unset_request( 'utility_tmp', 'session' );
	$nv_Request->unset_request( 'utility_tmp1', 'session' );
	
	$delcat = $nv_Request->get_int( 'delcat', 'get', 0 );
	$move_sub = $nv_Request->get_int( 'move_sub', 'get', 0 );
	
	$sources = $nv_Request->get_int( 'sources', 'get', 0 );
	$topics = $nv_Request->get_int( 'topics', 'get', 0 );
	$block = array_filter(array_unique(explode(",",filter_text_input( 'block', 'get', '', 1 ))));
	
	$fmod = filter_text_input( 'fmod', 'get', '', 1 );
	$tmod = filter_text_input( 'tmod', 'get', '', 1 );
	$fcat_g = $fcat = filter_text_input( 'fcat', 'get', '', 1 );
	$tcat = filter_text_input( 'tcat', 'get', '', 1 );
	if( !$fcat or !$tcat ) nv_u_exit($u_lang['error_setup']);
	
	$array_mod = nv_get_all_mod_news();
	if( ! isset( $array_mod[$fmod] ) or ! isset( $array_mod[$tmod] ) ) nv_u_exit($u_lang['error_issetmod']);
	
	$array_fcat = nv_get_cat($fmod);
	if( ! isset( $array_fcat[$fcat] ) ) nv_u_exit($u_lang['error_issetcat']);
	
	$array_tcat = nv_get_cat($tmod);
	if( ! isset( $array_tcat[$tcat] ) ) nv_u_exit($u_lang['error_issetcat']);
	
	$fcat = nv_get_subcat( $array_fcat, $fcat );
	if( $fmod == $tmod and in_array( $tcat, $fcat ) and $delcat ) nv_u_exit($u_lang['error_t1']);
	
	if( ! $move_sub and $delcat and sizeof( $fcat ) > 1 ) nv_u_exit($u_lang['move_sub_error']);
	if( ! $move_sub ) $fcat = array($fcat_g);
	
	if( $db->sql_numrows($db->sql_query("SELECT * FROM `" . NV_PREFIXLANG . "_" . str_replace("-","_",$tmod) . "_sources` WHERE `sourceid`=" . $sources)) != 1 and $sources ) nv_u_exit($u_lang['error_so']);
	if( $db->sql_numrows($db->sql_query("SELECT * FROM `" . NV_PREFIXLANG . "_" . str_replace("-","_",$tmod) . "_topics` WHERE `topicid`=" . $topics)) != 1 and $topics ) nv_u_exit($u_lang['error_tp']);
	if( $db->sql_numrows($db->sql_query("SELECT * FROM `" . NV_PREFIXLANG . "_" . str_replace("-","_",$tmod) . "_block_cat` WHERE `bid`IN(" . implode(",",$block) . ")")) != sizeof($block) and $block ) nv_u_exit($u_lang['error_bl']);
	
	$num_new = 0;
	foreach($fcat as $cat)
	{
		$sql = "SELECT COUNT(*) FROM `" . NV_PREFIXLANG . "_" . str_replace("-","_",$fmod) . "_" . $cat . "`";
		$result = $db->sql_query( $sql );
		list($num) = $db->sql_fetchrow( $result );
		$num_new += $num;
		
	}
	
	if( empty( $num_new ) ) nv_u_exit($u_lang['error_0news']);
	
	$array_config = array(
		'fmod' => $fmod, 
		'tmod' => $tmod, 
		'fcat' => $fcat, 
		'fcat1' => $fcat, 
		'tcat' => $tcat, 
		'delcat' => $delcat, 
		'move_sub' => $move_sub, 
		'sources' => $sources, 
		'topics' => $topics, 
		'block' => $block, 
		'num_news' => $num_new,
		'h_nid' => array(), // ID bai viet da duoc chuyen
	);
	
	$sql = "SELECT `config_name`, `config_value` FROM `" . NV_CONFIG_GLOBALTABLE . "` WHERE `module`='" . $tmod . "' AND `lang`='" . NV_LANG_DATA . "' AND `config_name` IN('homewidth','homeheight','blockwidth','blockheight')";
	$result = $db->sql_query( $sql );
	while( list( $config_name, $config_value ) = $db->sql_fetchrow( $result ) )
	{
		$array_config[$config_name] = $config_value;
	}
	
	$c .= '<table class="tab1">';
	$c .= '<tbody><tr><td>' . sprintf( $u_lang['info_num'], $num_new ) . '</td></tr></tbody>';
	$c .= '<tbody><tr><td align="center"><input type="button" value="' . $u_lang['action'] . '" onclick="window.location=\'' . $base_url_js . '&action\'"/></td></tr></tbody>';
	$c .= '</table>';
	
	$nv_Request->set_Session( 'utility_tmp', serialize( $array_config ) );
		
	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_admin_theme($c);
	include ( NV_ROOTDIR . "/includes/footer.php" );
	exit();
}

if( $nv_Request->isset_request( 'loadcat', 'get' ) )
{
	$mod = filter_text_input( 'loadcat', 'get', '' );
	$array_cat = nv_get_cat( $mod );
	
	$c .= '<select>';	
	$c .= '<option value="">' . $u_lang['s_mod'] . '</option>';
	
	$i = 0;
	foreach( $array_cat as $catid => $catcontent )
	{
		$space = '';
		if( $catcontent['lev'] > 0 )
		{
			$space = '|';
			for( $j = 0; $j < $catcontent['lev']; ++ $j ) $space .= '=';
			$space .= '&gt;';
		}
		
		$c .= '<option value="' . $catid . '">' . $space . ' ' . $catcontent['title'] . '</option>';
	}
	
	$c .= '</select>';
	
	die( $c );	
}

$all_mod = nv_get_all_mod_news();

$c .= '<table class="tab1 fixtab"><tr><td>' . $u_lang['info'] . '</td></tr></table>';
$c .= '<table class="tab1 fixtab">';
$c .= '<tbody>';
$c .= '<tr>';
$c .= '<td>';
$c .= $u_lang['f_mod'] . ': <select class="loadcat" id="fmod">';
$c .= '<option value="">' . $u_lang['s_mod'] . '</option>';

foreach( $all_mod as $mod )
{
	$c .= '<option value="' . $mod['name'] . '">' . $mod['title'] . '</option>';
}

$c .= '</select>';
$c .= $u_lang['f_cat'] . ': <span id="c-fmod"><select>';
$c .= '<option value="">' . $u_lang['s_mod'] . '</option>';
$c .= '</select></span>';

$c .= $u_lang['t_mod'] . ': <select class="loadcat" id="tmod">';
$c .= '<option value="">' . $u_lang['s_mod'] . '</option>';

foreach( $all_mod as $mod )
{
	$c .= '<option value="' . $mod['name'] . '">' . $mod['title'] . '</option>';
}

$c .= '</select>';
$c .= $u_lang['t_cat'] . ': <span id="c-tmod"><select>';
$c .= '<option value="">' . $u_lang['s_mod'] . '</option>';
$c .= '</select></span><br /><br />';

// Den cac nguon tin, cac nhom tin, dong su kien
$c .= $u_lang['move_to_block'] . ': <img src="' . NV_BASE_SITEURL . 'images/refresh.png" alt="' . $u_lang['update'] . '" title="' . $u_lang['update'] . '" class="poiter" id="reloadblock"/>';
$c .= '<span id="block-area" style="">';
$c .= '';
$c .= '</span><br /><br />';

$c .= $u_lang['move_to_sources'] . ': <img src="' . NV_BASE_SITEURL . 'images/refresh.png" alt="' . $u_lang['update'] . '" title="' . $u_lang['update'] . '" class="poiter" id="reloadsources"/>';
$c .= '<span id="sources-area" style="">';
$c .= '<select id="sources">';
$c .= '<option value="">' . $u_lang['move_to_default'] . '</option>';
$c .= '</select>';
$c .= '</span> ';

$c .= $u_lang['move_to_topics'] . ': <img src="' . NV_BASE_SITEURL . 'images/refresh.png" alt="' . $u_lang['update'] . '" title="' . $u_lang['update'] . '" class="poiter" id="reloadtopics"/>';
$c .= '<span id="topics-area" style="">';
$c .= '<select id="topics">';
$c .= '<option value="">' . $u_lang['move_to_default'] . '</option>';
$c .= '</select>';
$c .= '</span>';

$c .= '<br /><br />';

$c .= $u_lang['del_cat'] . ' <input type="checkbox" id="delcat" value="1"/>';
$c .= $u_lang['move_sub'] . ' <input type="checkbox" id="move_sub" value="1"/>';
$c .= '<input type="button" id="btaction" value="' . $u_lang['action'] . '">';
$c .= '</td>';
$c .= '</tr>';
$c .= '</tbody>';
$c .= '</table>';

$c .= '
<script type="text/javascript">
$(document).ready(function(){
$(\'.loadcat\').change(function(){
	if($(this).val()){
		$(\'#c-\'+$(this).attr(\'id\')).html(\'<img src="\'+nv_siteroot+\'images/load_bar.gif" title=""/>\');
		$(\'#c-\'+$(this).attr(\'id\')).load("' . $base_url_js . '&loadcat="+$(this).val());
	}
});

$(\'#btaction\').click(function(){
	var fcat = $(\'#c-fmod select\').val();
	var tcat = $(\'#c-tmod select\').val();
	var fmod = $(\'#fmod\').val();
	var tmod = $(\'#tmod\').val();
	var sources = $(\'#sources\').val();
	var topics = $(\'#topics\').val();
	var block = new Array();
	$.each($("#block-area input"), function(){
		if($(this).attr("checked")){
			block.push($(this).val());
		}
	});
	var delcat = $(\'#delcat\').attr("checked") ? 1 : 0;
	var move_sub = $(\'#move_sub\').attr("checked") ? 1 : 0;
	if( fcat == \'\' ){
		alert(\'' . $u_lang['error_fcat'] . '\');
		return;
	}else if( tcat == \'\' ){
		alert(\'' . $u_lang['error_tcat'] . '\');
		return;
	}else if( fmod == tmod && fcat == tcat )
	{
		alert(\'' . $u_lang['error_smodc'] . '\');
		return;
	}
	$(this).attr("disabled","disabled");
	window.location = "' . $base_url_js . '&setup&fmod="+fmod+"&tmod="+tmod+"&fcat="+fcat+"&tcat="+tcat+"&delcat="+delcat+"&move_sub="+move_sub+"&sources="+sources+"&topics="+topics+"&block="+block;
});

$("#reloadblock").click(function(){
	var tmod = $(\'#tmod\').val();
	if( tmod == "" ){
		alert("' . $u_lang['error_tmod'] . '");
		return;
	}
	$("#block-area").html(\'<img src="\'+nv_siteroot+\'images/load_bar.gif" title=""/>\');
	$("#block-area").load("' . $base_url_js . '&loadblock="+tmod);
});
$("#reloadsources").click(function(){
	var tmod = $(\'#tmod\').val();
	if( tmod == "" ){
		alert("' . $u_lang['error_tmod'] . '");
		return;
	}
	$("#sources-area").html(\'<img src="\'+nv_siteroot+\'images/load_bar.gif" title=""/>\');
	$("#sources-area").load("' . $base_url_js . '&loadsources="+tmod);
});
$("#reloadtopics").click(function(){
	var tmod = $(\'#tmod\').val();
	if( tmod == "" ){
		alert("' . $u_lang['error_tmod'] . '");
		return;
	}
	$("#topics-area").html(\'<img src="\'+nv_siteroot+\'images/load_bar.gif" title=""/>\');
	$("#topics-area").load("' . $base_url_js . '&loadtopics="+tmod);
});

});
</script>
';

$contents = $c;

?>