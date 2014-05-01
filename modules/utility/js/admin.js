/* *
 * @Project NUKEVIET 3.1
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate 07-03-2011 20:15
 */

function nv_change_weight( id )
{
   var nv_timer = nv_settimeout_disable( 'weight' + id, 5000 );
   var newpos = document.getElementById( 'weight' + id ).options[document.getElementById( 'weight' + id ).selectedIndex].value;
   nv_ajax( "post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&changeweight=1&id=' + id + '&new=' + newpos + '&num=' + nv_randomPassword( 8 ), '', 'nv_change_weight_result' );
   return;
}
function nv_change_weight_result( res )
{
   if ( res != 'OK' )
   {
      alert( nv_is_change_act_confirm[2] );
   }
   clearTimeout( nv_timer );
   window.location.href = window.location.href;
   return;
}
function nv_delete_ti( id )
{
	if ( confirm( nv_is_del_confirm[0] ) )
	{
		nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&del=1&id=' + id, '', 'nv_delete_ti_result' );
	}
	return false;
}
function nv_delete_ierror( id )
{
	if ( confirm( nv_is_del_confirm[0] ) )
	{
		nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&delerror=1&id=' + id, '', 'nv_delete_ti_result' );
	}
	return false;
}
function nv_delete_ti_result( res )
{
	if( res == 'OK' )
	{
		window.location.href = window.location.href;
	}
	else if( res == 'NO' )
	{
		alert( NV_MOD_LANG_NODEL_FILES );
		window.location.href = window.location.href;
	}
	else
	{
		alert( nv_is_del_confirm[2] );
	}
	return false;
}
function nv_change_status( id )
{
   var nv_timer = nv_settimeout_disable( 'change_status' + id, 4000 );
   nv_ajax( "post", script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&changestatus=1&id=' + id + '&num=' + nv_randomPassword( 8 ), '', 'nv_change_status_result' );
   return;
}
function nv_change_status_result( res )
{
	if( res != 'OK' )
	{
		alert( nv_is_change_act_confirm[2] );
		window.location.href = window.location.href;
	}
	return;
}
function nv_delete_error( id )
{
	if ( confirm( nv_is_del_confirm[0] ) )
	{
		nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=error&del=1&id=' + id, '', 'nv_delete_error_result' );
	}
	return false;
}
function nv_delete_error_result( res )
{
	if( res == 'OK' )
	{
		window.location.href = window.location.href;
	}
	else
	{
		alert( nv_is_del_confirm[2] );
	}
	return false;
}

function nv_delete_u(id){
	if (confirm(nv_is_del_confirm[0])){
		nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=utility&del=1&id=' + id, '', 'nv_delete_u_result' );
	}
	return false;
}
function nv_delete_u_result(res){
	res = res.split('|');
	if(res[0] == 'OK'){
		window.location.href = window.location.href;
	}else{
		alert(res[1]);
	}
	return false;
}
