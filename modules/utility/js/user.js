/* *
 * @Project NUKEVIET 3.1
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2011 VINADES.,JSC. All rights reserved
 * @Createdate 07-03-2011 20:15
 */

function nv_rating_send(id)
{
	nv_ajax('post', nv_siteroot + 'index.php', nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=sendgift&id=' + id + '&who_send=' + who_send.value + '&who_receive=' + who_receive.value + '&email_receive=' + email_receive.value + '&body=' + encodeURIComponent(body), '', 'resultgift');
}
