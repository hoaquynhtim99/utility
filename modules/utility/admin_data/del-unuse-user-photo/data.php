<?php

/**
 * @Project NUKEVIET 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 29, 2014, 12:13:24 AM
 */

if ( ! defined( 'NV_IS_DGAT_ADMIN' ) ) die( 'Stop!!!' );

$c = '';

if ( $nv_Request->isset_request( 'go', 'get' ) )
{
	$all_file = $nv_Request->get_string( 'utility_tmp', 'session', '' );
	$all_file = $all_file ? unserialize( $all_file ) : array();

	if( empty( $all_file ) )
	{
		$deleted = $nv_Request->get_string( 'utility_tmp1', 'session', '' );
		$deleted = $deleted ? unserialize( $deleted ) : array();
		
		$c .= '<div class="alert alert-success">' . sprintf( $u_lang['prosessing_ok'], sizeof( $deleted ) ) . '' . ( ! empty( $deleted ) ? '<br />' . implode( '<br />', $deleted ) : '' ) . '</div>';
		
		$error_del = $nv_Request->get_string( 'utility_tmp2', 'session', '' );
		$error_del = $error_del ? unserialize( $error_del ) : array();
		if( $error_del )
		{
			$c .= '<div class="alert alert-danger">' . sprintf( $u_lang['inof_prosessing_error_del'], implode( "<br />", $error_del ) ) . '</div>';
		}
		
		$nv_Request->unset_request( 'utility_tmp', 'session' );
		$nv_Request->unset_request( 'utility_tmp1', 'session' );
		$nv_Request->unset_request( 'utility_tmp2', 'session' );
	}
	else
	{
		$ded = array();
		$del_fail = array();
		
		$i = 0;
		foreach( $all_file as $fid => $file )
		{
			if( ++ $i >= 50 ) break;
			$sql = "SELECT userid FROM " . NV_USERS_GLOBALTABLE . " WHERE photo=" . $db->quote($file);
			
			$result = $db->query( $sql );
			if( ! $result->rowCount() )
			{
				if( ! unlink( NV_ROOTDIR . '/' . $file ) )
				{
					$del_fail[] = $file;
				}
				else
				{
					$ded[] = $file;	
				}
			}
			
			unset( $all_file[$fid] );
		}
		
		if( empty( $all_file ) ) $nv_Request->unset_request( 'utility_tmp', 'session' );
		else
		{
			$nv_Request->set_Session( 'utility_tmp', serialize( $all_file ) );
		}
		
		if( ! empty( $ded ) )
		{
			$deleted = $nv_Request->get_string( 'utility_tmp1', 'session', '' );
			$deleted = $deleted ? unserialize( $deleted ) : array();
			if( $deleted )
			{
				foreach( $ded as $t )
				{
					$deleted[] = $t;
				}
			}
			else
			{
				$deleted = $ded;
			}
						
			$deleted = serialize( $deleted );
			$nv_Request->set_Session( 'utility_tmp1', $deleted );
		}
		
		if( ! empty( $del_fail ) )
		{
			$error_del = $nv_Request->get_string( 'utility_tmp2', 'session', '' );
			$error_del = $error_del ? unserialize( $error_del ) : array();
			if( $error_del )
			{
				foreach( $del_fail as $t )
				{
					$error_del[] = $t;
				}
			}
			else
			{
				$error_del = $del_fail;
			}
						
			$error_del = serialize( $error_del );
			$nv_Request->set_Session( 'utility_tmp2', $error_del );
		}
		
		$c .= '<div class="alert alert-success">' . $u_lang['prosessing_wating'] . '</div>';
		$c .= '<meta http-equiv="refresh" content="2;url=' . $client_info['selfurl'] . '&go=1" />';
	}
	
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme($c);
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}

function nv_to_full_img($img){ return NV_UPLOADS_DIR . '/users/' . $img; }

if ( $nv_Request->isset_request( 'do', 'get' ) )
{
	$all_file = $nv_Request->get_string( 'utility_tmp', 'session', '' );
	$all_file = $all_file ? unserialize( $all_file ) : array();
	
	if( empty( $all_file ) )
	{
		$all_file = scandir( NV_UPLOADS_REAL_DIR . '/users' );
		$all_file = array_values(array_diff( $all_file, array('.','..','index.html','index.htm') ));
		if( ! empty( $all_file ) )
		{
			$all_file = array_map( 'nv_to_full_img', $all_file );
			$nv_Request->set_Session( 'utility_tmp', serialize( $all_file ) );
		}
	}
	
	if( empty( $all_file ) )
	{
		$c .= '<div class="alert alert-success">' . $u_lang['empty_img'] . '</div>';
	}
	else
	{
		$c .= '<div class="alert alert-success">' . sprintf( $u_lang['found_img'], sizeof( $all_file ) ) . '</div>';
		$c .= '<meta http-equiv="refresh" content="2;url=' . $base_url_js . '&go=1" />';
	}
	
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme($c);
	include NV_ROOTDIR . '/includes/footer.php';
	exit();
}

$c .= '<div class="alert alert-success">' . $u_lang['info'] . '</div>';
$c .= '<div class="text-center"><input class="btn btn-primary" type="button" id="prosessing" value="' . $u_lang['prosing'] . '"/></div>';
$c .= "
<script type=\"text/javascript\">
$(document).ready(function(){
	$('#prosessing').click(function(){
		$(this).attr('disabled','disabled');
		$('#status').html('" . $u_lang['wating'] . "<br /><img src=\"'+nv_siteroot+'images/load_bar.gif\" alt=\"Wating\"/><meta http-equiv=\"refresh\" content=\"2;url=" . $base_url_js . "&do=1\" />');
	});
});
</script>
";
$c .= '<div class="text-center" id="status"></div>';

$nv_Request->unset_request( 'utility_tmp', 'session' );
$nv_Request->unset_request( 'utility_tmp1', 'session' );
$nv_Request->unset_request( 'utility_tmp2', 'session' );

$contents = $c;