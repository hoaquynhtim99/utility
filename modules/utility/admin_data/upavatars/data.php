<?php

/**
 * @Project NUKEVIET 4.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 29, 2014, 12:13:24 AM
 */

if( ! defined( 'NV_IS_DGAT_ADMIN' ) ) die( 'Stop!!!' );

$c = '';
$phpEx = 'php';

if( ! defined('NV_IS_USER_FORUM') )
{
	$c .= '<div class="alert alert-danger">' . $u_lang['info'] . '</div>';
}
else
{
	if( file_exists( NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/is_user.php' ) and file_exists( NV_ROOTDIR . '/' . DIR_FORUM . '/includes/constants.' . $phpEx))
	{
		$table_prefix = 'phpbb_';
		define('IN_PHPBB',true);
		
		include( NV_ROOTDIR . '/' . DIR_FORUM . '/includes/constants.' . $phpEx );
		require_once( NV_ROOTDIR . "/includes/class/image.class.php" );
		
		if( $nv_Request->isset_request( 'do', 'get' ) )
		{
			$page = $nv_Request->get_int( 'page', 'get', 0 );
			$num_users = $nv_Request->get_int( 'utility_tmp', 'session', 0 );
			$per_page = 50;
			
			if( $num_users <= 0 )
			{
				$c .= '<div class="alert alert-danger">' . $u_lang['error_empty_sesion'] . '</div>';
			}
			elseif( $page >= $num_users )
			{
				Header('Location: ' . $base_url_js );
				die();
			}
			else
			{
				$config_path = array();
				$sql = "SELECT config_name, config_value FROM " . CONFIG_TABLE . " WHERE config_name IN('avatar_gallery_path', 'avatar_path', 'avatar_salt')";
				$result = $db->query( $sql );
				while( list( $config_name, $config_value ) = $result->fetch( 3 ) )
				{
					$config_path[$config_name] = $config_value;
				}
				
				$sql = "SELECT a.userid, a.photo, b.user_avatar, b.user_avatar_type FROM " . NV_USERS_GLOBALTABLE . " a INNER JOIN " . USERS_TABLE . " b ON a.userid = b.user_id LIMIT " . $page . "," . $per_page;
				$result = $db->query( $sql );
				
				$num_ok = 0;
				$del_fail = array();
				while( list( $uid, $uimg, $uavatar, $avatar_type ) = $result->fetch( 3 ) )
				{
					if( empty( $uavatar ) or empty( $avatar_type ) ) continue;
					
					$new_avatar = '';
					if( $avatar_type == AVATAR_UPLOAD )
					{
						$avatar_group = false;
						$exit = false;

						if( isset( $uavatar[0]) and $uavatar[0] === 'g' )
						{
							$avatar_group = true;
							$uavatar = substr( $uavatar, 1 );
						}
						
						if( strpos( $uavatar, '.' ) == false )
						{
							$exit = true;
						}
						
						if( ! $exit )
						{
							$ext = substr( strrchr( $uavatar, '.' ), 1 );
							$uavatar = ( int ) $uavatar;
						}
						
						if( ! $exit and ! in_array( $ext, array( 'png', 'gif', 'jpg', 'jpeg' ) ) )
						{
							$exit = true;
						}

						if( $uavatar and ! $exit )
						{
							$uavatar = ( $avatar_group ? 'g' : '' ) . $uavatar . '.' . $ext;
							
							$prefix = $config_path['avatar_salt'] . '_';
							$image_dir = $config_path['avatar_path'];

							if( substr( $image_dir, -1, 1 ) == '/' or substr( $image_dir, -1, 1 ) == '\\' )
							{
								$image_dir = substr( $image_dir, 0, -1 ) . '/';
							}
							
							$image_dir = str_replace( array( '../', '..\\', './', '.\\' ), '', $image_dir );

							if( $image_dir and ( $image_dir[0] == '/' or $image_dir[0] == '\\' ) )
							{
								$image_dir = '';
							}
							
							$file_path = NV_ROOTDIR . '/' . DIR_FORUM . '/' .  $image_dir . '/' . $prefix . $uavatar;
							
							if( empty( $file_path ) )
							{
								continue;
							}
							
							$new_avatar = $file_path;
						}
						else
						{
							continue;
						}
						
						$new_avatar = $file_path;
					}
					elseif( $avatar_type == AVATAR_REMOTE )
					{
						$new_avatar = $uavatar;
					}
					elseif( $avatar_type == AVATAR_GALLERY )
					{
						$new_avatar = NV_ROOTDIR . '/' . DIR_FORUM . '/' . $config_path['avatar_gallery_path'] . '/' . $uavatar;
					}
					
					if( empty( $new_avatar ) )
					{
						continue;
					}
										
					$name = $basename = basename( $new_avatar );
					$i = 1;
					while( file_exists( NV_UPLOADS_REAL_DIR . '/users/' . $name ) )
					{
						$name = preg_replace( '/(.*)(\.[a-zA-Z]+)$/', '\1_' . $i . '\2', $basename );
						$i ++;
					}

					$image = new image( $new_avatar, NV_MAX_WIDTH, NV_MAX_HEIGHT );
					$image->cropFromCenter( 80,80 );

					$image->save( NV_UPLOADS_REAL_DIR . '/users', $name );
					$image_info = $image->create_Image_info;
										
					if( file_exists( $image_info['src'] ) )
					{
						$f_image = str_replace( NV_ROOTDIR . '/', '', $image_info['src'] );
												
						if( $db->query("UPDATE " . NV_USERS_GLOBALTABLE . " SET photo = " . $db->quote($f_image) . " WHERE userid = " . $uid ) )
						{
							if( file_exists( NV_ROOTDIR . '/' . $uimg ) and ! empty( $uimg ) and $f_image != $uimg ) // Xoa anh cu
							{
								if( ! unlink( NV_ROOTDIR . '/' . $uimg ) )
								{
									$del_fail[] = $uimg;
								}
							}
							
							$num_ok ++;
						}
						else
						{
							if( ! unlink( $image_info['src'] ) )
							{
								$del_fail[] = $f_image;
							}
						}
					}
					
					unset( $ext, $new_avatar, $name, $basename, $image, $i, $image_info, $f_image );
				}

				if( $page + $per_page < $num_users )
				{					
					if( ! empty( $del_fail ) )
					{
						$error_del = $nv_Request->get_string( 'error_del', 'session', '' );
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
						$nv_Request->set_Session( 'error_del', $error_del );
					}
										
					
					$c .= '<div class="alert alert-success">' . sprintf( $u_lang['inof_prosessing'], $per_page, $page, $num_users, $num_ok ) . '</div>';
					$c .= '<meta http-equiv="refresh" content="2;url=' . $base_url_js . '&do=1&page=' . ( $page + $per_page) . '" />';
				}
				else
				{
					$c .= '<div class="alert alert-success">' . sprintf( $u_lang['inof_prosessing_ok'], $num_users ) . '</div>';
					
					$error_del = $nv_Request->get_string( 'error_del', 'session', '' );
					$error_del = $error_del ? unserialize( $error_del ) : array();
					if( $error_del )
					{
						$c .= '<div class="alert alert-danger">' . sprintf( $u_lang['inof_prosessing_error_del'], implode( "<br />", $error_del ) ) . '</div>';
						
						$nv_Request->unset_request( 'error_del', 'session' );
					}
				}
			}
						
			include NV_ROOTDIR . '/includes/header.php';
			echo nv_admin_theme( $c );
			include NV_ROOTDIR . '/includes/footer.php';
		}
		
		$num_u_web = $db->query("SELECT COUNT(*) FROM " . NV_USERS_GLOBALTABLE . "")->fetchColumn();
		$num_u_4u = $db->query("SELECT COUNT(*) FROM " . USERS_TABLE . " WHERE user_ip!=''")->fetchColumn();
		
		$nv_Request->set_Session( 'utility_tmp', $num_u_web );
		
		$c .= '<div class="alert alert-success">' . sprintf( $u_lang['info_num'], $num_u_web, $num_u_4u ) . '</div>';
		if( $num_u_web > 0 )
		{
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
		}
	}
	else
	{
		$c .= '<div class="alert alert-danger">' . $u_lang['no_exist_forum'] . '</div>';
	}	
}

$contents = $c;