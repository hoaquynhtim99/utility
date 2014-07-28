<?php

/**
 * @Project NUKEVIET 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 29, 2014, 12:13:24 AM
 */

if ( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

function nv_get_all_mod_news( $select_mod = '' )
{
	global $db;
	
	$result = $db->query("SELECT title, custom_title FROM " . NV_PREFIXLANG . "_modules WHERE module_file='news'");
	$array = array();
	while(list($_modt, $_modn) = $result->fetch( 3 ))
	{
		$array[$_modt] = array( 'name' => $_modt, 'title' => $_modn, 'selected' => $select_mod == $_modt ? ' selected="selected"' : '' );
	}

	return $array;
}

function nv_get_cat( $module )
{
	global $db;
	if( empty( $module ) ) return array();
	$module = str_replace("-","_",$module);
	
	$array_cat = array();
	
	$sql = "SELECT catid, parentid, title, titlesite, alias, lev, viewcat,numsubcat, subcatid, numlinks, description, inhome, keywords, who_view, groups_view FROM " . NV_PREFIXLANG . "_" . $module . "_cat ORDER BY order ASC";
	$result = $db->query( $sql );
	while ( list( $catid_i, $parentid_i, $title_i, $titlesite_i, $alias_i, $lev_i, $viewcat_i, $numsubcat_i, $subcatid_i, $numlinks_i, $description_i, $inhome_i, $keywords_i, $who_view_i, $groups_view_i ) = $result->fetch( 3 ) )
	{
		$array_cat[$catid_i] = array( 
			"catid" => $catid_i, "parentid" => $parentid_i, "title" => $title_i, "titlesite" => $titlesite_i, "alias" => $alias_i, "numsubcat" => $numsubcat_i, "lev" => $lev_i, "viewcat" => $viewcat_i, "subcatid" => $subcatid_i, "numlinks" => $numlinks_i, "description" => $description_i, "inhome" => $inhome_i, "keywords" => $keywords_i, "who_view" => $who_view_i, "groups_view" => $groups_view_i 
		);
	}
	
	return $array_cat;
}

function nv_get_subcat( $array_catdata, $catid )
{
    $array_cat = array();
    $array_cat[] = $catid;
    $subcatid = explode( ",", $array_catdata[$catid]['subcatid'] );
    if ( ! empty( $subcatid ) )
    {
        foreach ( $subcatid as $id )
        {
            if ( $id > 0 )
            {
                if ( $array_catdata[$id]['numsubcat'] == 0 )
                {
                    $array_cat[] = $id;
                }
                else
                {
                    $array_cat_temp = nv_get_subcat( $array_catdata, $id );
                    foreach ( $array_cat_temp as $catid_i )
                    {
                        $array_cat[] = $catid_i;
                    }
                }
            }
        }
    }
    return array_unique( $array_cat );
}

function nv_unews_fix_block( $bid, $mod, $repairtable = true )
{
    global $db;
    $bid = intval( $bid );
    if ( $bid > 0 )
    {
        $query = "SELECT id FROM " . NV_PREFIXLANG . "_" . $mod . "_block where bid='" . $bid . "' ORDER BY weight ASC";
        $result = $db->query( $query );
        $weight = 0;
        while ( $row = $result->fetch() )
        {
            ++$weight;
            if ( $weight <= 100 )
            {
                $sql = "UPDATE " . NV_PREFIXLANG . "_" . $mod . "_block SET weight=" . $weight . " WHERE bid='" . $bid . "' AND id=" . intval( $row['id'] );
            }
            else
            {
                $sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $mod . "_block WHERE bid='" . $bid . "' AND id=" . intval( $row['id'] );
            }
            $db->query( $sql );
        }
        //$xxx->closeCursor();
        if ( $repairtable )
        {
            $db->query( "REPAIR TABLE " . NV_PREFIXLANG . "_" . $mod . "_block" );
            $db->query( "OPTIMIZE TABLE " . NV_PREFIXLANG . "_" . $mod . "_block" );
        }
    }
}

function nv_udel_content_module( $id, $module_name, $module_data )
{
    global $db;

    list($id, $listcatid, $title, $homeimgfile, $homeimgthumb) = $db->query("SELECT id, listcatid, title, homeimgfile, homeimgthumb FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id=" . intval($id) . "")->fetch( 3 );
    if ($id > 0)
    {
        if ($homeimgthumb != "" and $homeimgthumb != "|")
        {
            $homeimgthumb_arr = explode("|", $homeimgthumb);
            foreach ($homeimgthumb_arr as $file)
            {
                if (!empty($file) and is_file(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/' . $file))
                {
                    @nv_deletefile(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_name . '/' . $file);
                }
            }
        }
        $number_no_del = 0;
        $array_catid = explode(",", $listcatid);
        foreach ($array_catid as $catid_i)
        {
            $catid_i = intval($catid_i);
            if ($catid_i > 0)
            {
                $query = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_" . $catid_i . " WHERE id=" . $id;
                $db->query($query);
                if (!$db->sql_affectedrows())
                {
                    ++$number_no_del;
                }
                //$xxx->closeCursor();
            }
        }
        if ($number_no_del == 0)
        {
            $query = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id=" . $id;
            $db->query($query);
            if (!$db->sql_affectedrows())
            {
                {
                    ++$number_no_del;
                }
                //$xxx->closeCursor();
            }
        }
        $number_no_del = 0;
        if ($number_no_del == 0)
        {
            $db->query("DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_bodyhtml_" . ceil($id / 2000) . " WHERE id = " . $id);
            $db->query("DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_bodytext WHERE id = " . $id);
            $db->query("DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_comments WHERE id = " . $id);
            $db->query("DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_block WHERE id = " . $id);
        }
    }
    return true;
}

function nv_ufix_cat_order ( $module_data, $parentid = 0, $order = 0, $lev = 0 )
{
    global $db;
    $query = "SELECT catid, parentid FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE parentid=" . $parentid . " ORDER BY weight ASC";
    $result = $db->query( $query );
    $array_cat_order = array();
    while ( $row = $result->fetch() )
    {
        $array_cat_order[] = $row['catid'];
    }
    //$xxx->closeCursor();
    $weight = 0;
    if ( $parentid > 0 )
    {
        ++$lev;
    }
    else
    {
        $lev = 0;
    }
    foreach ( $array_cat_order as $catid_i )
    {
        ++$order;
        ++$weight;
        $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_cat SET weight=" . $weight . ", order=" . $order . ", lev='" . $lev . "' WHERE catid=" . intval( $catid_i );
        $db->query( $sql );
        $order = nv_ufix_cat_order( $module_data, $catid_i, $order, $lev );
    }
    $numsubcat = $weight;
    if ( $parentid > 0 )
    {
        $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_cat SET numsubcat=" . $numsubcat;
        if ( $numsubcat == 0 )
        {
            $sql .= ",subcatid='', viewcat='viewcat_page_new'";
        }
        else
        {
            $sql .= ",subcatid='" . implode( ",", $array_cat_order ) . "'";
        }
        $sql .= " WHERE catid=" . intval( $parentid );
        $db->query( $sql );
    }
    return $order;
}

?>